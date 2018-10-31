<?php
namespace ZealByte\Identity
{
	use DateTime;
	use Symfony\Component\Workflow\Registry;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Form\FormInterface;
	use ZealByte\Catalog\Inventory\CatalogFactoryInterface;
	use ZealByte\Catalog\Inventory\SpecRegistryInterface;
	use ZealByte\Catalog\CatalogItemInterface;
	use ZealByte\Catalog\InventoryRequest;
	use ZealByte\Catalog\CatalogItem;
	use ZealByte\Identity\Form\Extension\Recover\Type\RecoverRequestType;
	use ZealByte\Identity\Form\Extension\Recover\Type\RecoverRsvpSentType;
	use ZealByte\Identity\Recover\RecoverTokenInterface;
	use ZealByte\Identity\Recover\RecoverToken;
	use ZealByte\Util;

	class RecoverBroker
	{
		private $specRegistry;

		private $catalogFactory;

		private $workflowRegistry;

		private $steps = [
			'prep_rsvp' => RecoverRsvpSentType::class,
			'send_rsvp' => RecoverRsvpSentType::class,
			'read_rsvp' => RecoverRsvpSentType::class,
			'prep_trial' => RecoverRequestType::class,
			'pass_trial' => RecoverRequestType::class,
			'fail_trial' => RecoverRequestType::class,
			'verify' => RecoverRequestType::class,
			'complete' => RecoverRequestType::class,
		];

		public function __construct (CatalogFactoryInterface $catalog_factory, SpecRegistryInterface $spec_registry, Registry $workflow_registry)
		{
			$this->specRegistry = $spec_registry;

			$this->catalogFactory = $catalog_factory;

			$this->workflowRegistry = $workflow_registry;
		}

		/**
		 *
		 */
		public function findToken (Request $request) : RecoverTokenInterface
		{
			$key = $request->query->has('key') ? $request->query->get('key') : null;
			$token = $this->statToken();

			if (!empty($key)) {
				$item = $this->statTokenItem($key);
				$this->hydrateToken($token, $item);
			}

			return $token;
		}

		/**
		 *
		 */
		public function findFormType (RecoverTokenInterface $token) : string
		{
			$workflow = $this->workflowRegistry->get($token);

			$status = $token->getStatus();
			$transitions = $workflow->getEnabledTransitions($token);

			if ($workflow->can($token, 'send_rsvp')) {
				return RecoverRsvpSentType::class;
			}

			return RecoverRequestType::class;
		}

		/**
		 *
		 */
		public function advanceToken (RecoverTokenInterface $token) : void
		{
			$workflow = $this->workflowRegistry->get($token);

			if ($workflow->can($token, 'prep_rsvp')) {
				$workflow->apply($token, 'prep_rsvp');
				$this->prepRsvp($token);
			}
		}

		/**
		 *
		 */
		protected function statTokenItem (string $key) : CatalogItem
		{
			$spec = $this->specRegistry->getSpec('recover_token', 'identity');
			$inventoryRequest = new InventoryRequest();
			$inventoryRequest->addFilterTerm('key', $key);

			$catalog = $this->catalogFactory->createCatalog($spec, $inventoryRequest);
			$count = $catalog->getTotal();

			if (!$count)
				throw new \Exception("Your session has expired!");

			return $catalog->getItem();
		}

		/**
		 *
		 */
		protected function statToken ()
		{
			return new RecoverToken();
		}

		protected function prepRsvp (RecoverTokenInterface $token)
		{
			$spec = $this->specRegistry->getSpec('recover_token', 'identity');
			$inventoryRequest = new InventoryRequest();
			$catalog = $this->catalogFactory->createCatalog($spec, $inventoryRequest);
			$item = $catalog->new();

			$this->hydrateItem($token, $item);

			$catalog->save();
		}

		protected function hydrateItem (RecoverTokenInterface $token, CatalogItem $item) : void
		{
			$item['id'] = Util\UUID::new();
			$item['key'] = Util\Random::entropicToken(128);
			$item['code'] = (string) Util\Random::number(1000000, 9999999);
			$item['attempts'] = 0;
			$item['revoked'] = false;
			$item['dateRequested'] = new DateTime('now');
			$item['status'] = $token->getStatus();
			$item['userId'] = $token->getUserId();
		}

		/**
		 *
		 */
		protected function hydrateToken (RecoverTokenInterface $token, CatalogItem $item) : void
		{
			if ($item['key'])
				$token->setKey($item['key']);

			if ($item['userId'])
				$token->setUserId($item['userId']);

			if ($item['code'])
				$token->setRsvp($item['code']);

			if ($item['attempts'])
				$token->setTrial($item['attempts']);

			if ($item['status'])
				$token->setStatus($item['status']);
		}




		/**
		 *
		 */
		private function stepForm (Request $request) : FormInterface
		{
			$token = $this->findToken($request);
			$workflow = $this->workflowRegistry->get($token);

			foreach ($this->steps as $step => $formType)
				if ($workflow->can($token, $step))
					return $this->createForm($formType, $token);
		}

		/**
		 *
		 */
		private function stepProcessForm (Request $request, FormInterface $form) : void
		{
			$token = $form->getData();
			$workflow = $this->workflowRegistry->get($token);

			foreach (\array_keys($this->steps) as $step)
				if ($workflow->can($token, $step))
					$this->processForm($request, $form, $workflow, $step);
		}

	}
}

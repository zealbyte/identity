<?php
namespace ZealByte\Identity\Component
{
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormError;
	use ZealByte\Platform\Component\DispatcherComponentAbstract;
	use ZealByte\Identity\Recover\RecoverToken;
	use ZealByte\Identity\Event\IdentityFormEvent;
	use ZealByte\Identity\Event\RecoverFormEvent;
	use ZealByte\Identity\Event\RecoverProcessFormEvent;
	use ZealByte\Identity\ZealByteIdentity;

	class RecoverComponent extends DispatcherComponentAbstract
	{
		const BLOCK_NAME = 'recover_component';

		const VIEW = '@Identity/components.html.twig';

		private $fromAddress;

		private $request;

		public function __construct (?Request $request = null)
		{
			if ($request)
				$this->setRequest($request);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getData () : array
		{
			$this->stat();
			$this->setForm();

			return parent::getParameters();
		}

		/**
		 * {@inheritdoc}
		 */
		public function getParameters () : array
		{
			$this->stat();
			$this->setParameter('from_email_address', 'this@guy.com');

			return parent::getParameters();
		}

		public function hasRequest () : bool
		{
			return ($this->request) ? true : false;
		}

		/**
		 *
		 */
		public function hasForm () : bool
		{
			return ($this->hasParameter('form') && $this->getParameter('form'));
		}

		/**
		 *
		 */
		public function setRequest (?Request $request = null) : self
		{
			$this->request = $request;

			return $this;
		}

		/**
		 *
		 */
		public function setForm (?FormView $form = null) : self
		{
			$this->setParameter('form', $form);

			return $this;
		}

		/**
		 *
		 */
		protected function stat () : void
		{
			if (!$this->hasForm())
				$this->discoverForm();
		}

		/**
			*
		 */
		protected function discoverForm () : void
		{
			$event = new RecoverFormEvent();

			$this->addEventProps($event);
			$this->getEventDispatcher()
				->dispatch(ZealByteIdentity::EVENT_RECOVER_FORM, $event);

			if ($event->hasForm()) {
				$form = $event->getForm();

				if ($event->hasRequest()) {
					$request = $event->getRequest();
					$this->handleFormRequest($form, $request);
				}

				$this->setForm($form->createView());
			}
		}

		/**
		 *
		 */
		protected function handleFormRequest (FormInterface $form, Request $request) : void
		{
			try {
				$form->handleRequest($request);

				if ($form->isSubmitted() && $form->isValid())
					$this->processFormData($form);
			}
			catch (\Exception $e) {
				$form->addError(new FormError($e->getMessage()));
			}
		}

		/**
		 *
		 */
		protected function processFormData (FormInterface $form)
		{
			$token = $form->getData();

			if (!($token instanceof RecoverToken))
				throw new \Exception("Invalid recovery token supplied.");

			$event = (new RecoverProcessFormEvent())
				->setForm($form)
				->setRecoverToken($token);

			$this->addEventProps($event);
			$this->getEventDispatcher()
				->dispatch(ZealByteIdentity::EVENT_RECOVER_PROCESS_FORM, $event);
		}

		protected function addEventProps (IdentityFormEvent $event) : void
		{
			if ($this->hasRequest())
				$event->setRequest($this->request);
		}

	}
}

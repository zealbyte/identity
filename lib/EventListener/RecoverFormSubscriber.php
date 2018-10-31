<?php
namespace ZealByte\Identity\EventListener
{
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormFactoryInterface;
	use Symfony\Component\Form\FormError;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use ZealByte\Platform\ZealBytePlatform;
	use ZealByte\Platform\Context\ContextFactoryInterface;
	use ZealByte\Identity\RecoverBroker;
	use ZealByte\Identity\Recover\RecoverToken;
	use ZealByte\Identity\Event\LoginFormEvent;
	use ZealByte\Identity\Event\RecoverFormEvent;
	use ZealByte\Identity\Event\RecoverProcessFormEvent;
	use ZealByte\Identity\ZealByteIdentity;

	class RecoverFormSubscriber extends IdentityFormSubscriberAbstract
	{
		const ON_LOGIN_FORM_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_RECOVER_FORM_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_RECOVER_PROCESS_FORM_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		private $recoverBroker;

		private $contextFactory;

		/**
		 * {@inheritdoc}
		 */
		public static function getSubscribedEvents ()
		{
			return [
				ZealByteIdentity::EVENT_LOGIN_FORM => [
					['onLoginForm', self::ON_LOGIN_FORM_PRIORITY],
				],
				ZealByteIdentity::EVENT_RECOVER_FORM => [
					['onRecoverForm', self::ON_RECOVER_FORM_PRIORITY],
				],
				ZealByteIdentity::EVENT_RECOVER_PROCESS_FORM => [
					['onRecoverProcessForm', self::ON_RECOVER_PROCESS_FORM_PRIORITY]
				]
			];
		}

		public function __construct (RecoverBroker $recover_broker, ContextFactoryInterface $context_factory, FormFactoryInterface $form_factory, ?RequestStack $request_stack, ?UrlGeneratorInterface $url_generator = null)
		{
			$this->recoverBroker = $recover_broker;
			$this->contextFactory = $context_factory;

			$this->setFormFactory($form_factory);

			if ($request_stack)
				$this->setRequestStack($request_stack);

			if ($url_generator)
				$this->setUrlGenerator($url_generator);
		}

		/**
		 *
		 */
		public function onLoginForm (LoginFormEvent $event)
		{
			$passwordName = '_password';

			if (!$event->hasForm())
				return;

			if (!$event->getForm()->has($passwordName))
				return;

			$loginForm = $event->getForm();
			$passwordForm = $loginForm->get($passwordName);
			$passwordType = $passwordForm->getConfig()->getType()->getInnerType();
			$passwordTypeName = get_class($passwordType);
			$passwordOptions = $passwordForm->getConfig()->getOptions();

			$loginForm->add($passwordName, $passwordTypeName, array_merge($passwordOptions, [
				'help_link_route' => ZealByteIdentity::ROUTE_RECOVER,
				'help_link_text' => 'identity.go.recover',
			]));
		}

		/**
		 *
		 */
		public function onRecoverForm (RecoverFormEvent $event) : void
		{
			if (!$event->hasRequest() && $this->hasRequestStack())
				$event->setRequest($this->getRequestStack()->getCurrentRequest());

			$request = ($event->hasRequest()) ? $event->getRequest() : null;
			$form = $this->createForm($request);

			$event->setForm($form);
		}

		/**
		 *
		 */
		public function onRecoverProcessForm (RecoverProcessFormEvent $event) : void
		{
			if (!$event->hasRequest() && $this->hasRequestStack())
				$event->setRequest($this->getRequestStack()->getCurrentRequest());

			$request = ($event->hasRequest()) ? $event->getRequest() : null;
			$form = ($event->hasForm()) ? $event->getForm() : null;

			if ($request && $form)
				$this->processForm($request, $form);
		}

		/**
		 *
		 */
		private function createForm (Request $request) : FormInterface
		{
			$token = $this->recoverBroker->findToken($request);
			$formType = $this->recoverBroker->findFormType($token);

			$action = ($this->hasUrlGenerator()) ?
				$this->getUrlGenerator()->generate(ZealByteIdentity::ROUTE_RECOVER, [
					'key' => $token->getKey(),
				]) : null;

			return ($this->getFormFactory()->createBuilder($formType, $token, [
				'action' => $action,
				'method' => 'post',
			]))->getForm();
		}

		/**
		 *
		 */
		private function processForm (Request $request, FormInterface $form) : void
		{
			try {
				$token = $form->getData();

				$this->recoverBroker->advanceToken($token);

				$context = $this->contextFactory->hasContext($request) ?
					$this->contextFactory->getContext($request) : null;

				$redirect = ($this->hasUrlGenerator()) ?
					$this->getUrlGenerator()->generate(ZealByteIdentity::ROUTE_RECOVER, [
						'key' => $token->getKey(),
					]) : null;

				if ($context && $redirect)
					$context->setResponse(new RedirectResponse($redirect, RedirectResponse::HTTP_FOUND));
			}
			catch (TransitionException $e) {
				$form->addError(new FormError($e->getMessage()));
			}
		}

	}
}

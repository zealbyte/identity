<?php
namespace ZealByte\Identity\EventListener
{
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormFactoryInterface;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use ZealByte\Identity\Form\Extension\Login\Type\LoginFormType;
	use ZealByte\Identity\Event\LoginFormEvent;
	use ZealByte\Identity\ZealByteIdentity;

	class LoginFormSubscriber extends IdentityFormSubscriberAbstract
	{
		const ON_LOGIN_FORM_PRIORITY = 10;

		private $showRegistrationLink;

		/**
		 * {@inheritdoc}
		 */
		public static function getSubscribedEvents ()
		{
			return [
				ZealByteIdentity::EVENT_LOGIN_FORM => [
					['onLoginForm', self::ON_LOGIN_FORM_PRIORITY],
				],
			];
		}

		public function __construct (FormFactoryInterface $form_factory, ?RequestStack $request_stack, ?UrlGeneratorInterface $url_generator = null, bool $show_registration_link = false)
		{
			$this->showRegistrationLink = $show_registration_link;

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
			if ($this->hasRequestStack()) {
				if ($this->getRequestStack()->getMasterRequest() == $this->getRequestStack()->getCurrentRequest())
					$event->setShowRegistrationLink($this->showRegistrationLink);
			}
			else {
				$event->setShowRegistrationLink($this->showRegistrationLink);
			}

			$action = ($this->hasUrlGenerator()) ?
				$this->getUrlGenerator()->generate(ZealByteIdentity::ROUTE_LOGIN) : null;

			$form = $this->getFormFactory()->create(LoginFormType::class, [], [
				'action' => $action,
				'method' => 'post',
				'show_rememberme' => true,
			]);

			$event->setForm($form);
		}

	}
}

<?php
namespace ZealByte\Identity\Form\Extension\Login\EventListener
{
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\Form\FormEvent;
	use Symfony\Component\Form\FormEvents;
	use ZealByte\Platform\ZealBytePlatform;

	class LoginFormSubscriber implements EventSubscriberInterface
	{
		const ON_PRE_SET_DATA_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		/**
		 * @var Symfony\Component\Security\Http\Authentication\AuthenticationUtils
		 */
		private $authenticationUtils;

		/**
		 * @var Symfony\Component\HttpFoundation\RequestStack
		 */
		private $requestStack;

		/**
		 * @var array
		 */
		private $options = [];

		/**
		 * {@inheritdoc}
		 */
		public static function getSubscribedEvents ()
		{
			return [
				FormEvents::PRE_SET_DATA => [
					['onPreSetData', self::ON_PRE_SET_DATA_PRIORITY],
				],
			];
		}

		public function __construct(RequestStack $request_stack, AuthenticationUtils $authentication_utils, array $options)
		{
			$this->requestStack = $request_stack;
			$this->authenticationUtils = $authentication_utils;
			$this->options = $options;
		}

		/**
		 *
		 */
		public function onPreSetData (FormEvent $event) : void
		{
			$eventData = (array) $event->getData();
			$request = $this->requestStack->getCurrentRequest();
			$authenticationUtils = $this->authenticationUtils;
			$options = $this->options;

			// Get last username
			$eventData[$options['username_field_name']] = $authenticationUtils->getLastUsername();

			// Check remember_me
			if ($options['show_rememberme']) {
				$rememberMe = $request->cookies->has($options['rememberme_cookie_name']);
				$eventData[$options['rememberme_field_name']] = $rememberMe;
			}

			$event->setData($eventData);
		}

	}
}

<?php
namespace ZealByte\Identity\EventListener
{
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
	use Symfony\Component\Security\Core\AuthenticationEvents;
	use Symfony\Component\Security\Http\SecurityEvents;
	use Symfony\Component\Security\Core\Event\AuthenticationEvent;
	use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
	use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
	use Symfony\Component\Security\Http\Event\SwitchUserEvent;
	use ZealByte\Message\Provider\MessageProvider;

	class IdentityMessagesSubscriber implements EventSubscriberInterface
	{
		CONST ON_AUTHENTICATION_SUCCESS_PRIORITY = 1;
		CONST ON_AUTHENTICATION_FAILURE_PRIORITY = 1;
		CONST ON_INTERACTIVE_LOGIN_PRIORITY = 1;
		CONST ON_SWITCH_USER_PRIORITY = 1;

		private $authUtils;

		private $messages;

		public static function getSubscribedEvents()
		{
			return [
				AuthenticationEvents::AUTHENTICATION_SUCCESS => [
					['onAuthenticationSuccess', self::ON_AUTHENTICATION_SUCCESS_PRIORITY],
				],
				AuthenticationEvents::AUTHENTICATION_FAILURE => [
					['onAuthenticationFailure', self::ON_AUTHENTICATION_FAILURE_PRIORITY],
				],
				SecurityEvents::INTERACTIVE_LOGIN => [
					['onInteractiveLogin', self::ON_INTERACTIVE_LOGIN_PRIORITY],
				],
				SecurityEvents::SWITCH_USER => [
					['onSwitchUser', self::ON_SWITCH_USER_PRIORITY],
				],
			];
		}

		public function __construct (AuthenticationUtils $auth_utils, MessageProvider $messages)
		{
			$this->authUtils = $auth_utils;
			$this->messages = $messages;
		}

		public function onAuthenticationSuccess (AuthenticationEvent $event)
		{
			$token = $event->getAuthenticationToken();

			$this->applyMessageUser($token);
		}

		public function onAuthenticationFailure (AuthenticationFailureEvent $event)
		{
			$token = $event->getAuthenticationToken();
			$exception = $event->getAuthenticationException();

			$this->applyMessageUser($token);

			if ($exception)
				$this->messages->addException($exception->getMessage(), $exception);
		}

		public function onInteractiveLogin (InteractiveLoginEvent $event)
		{
			$user = null;
			$username = null;
			$message = "You have successfully logged in.";
			$token = $event->getAuthenticationToken();
			$request = $event->getRequest();

			if ($token)
				$user = $token->getUser();

			if ($user)
				$username = $user->getUsername();

			if ($username)
				$message = "You have successfully logged in as $username.";

			$this->messages->addSuccess($message);
		}

		public function onSwitchUser (SwitchUserEvent $event)
		{
			$token = $event->getToken();
			$targetUser = $event->getTargetUser();
			$request = $event->getRequest();

			$this->applyMessageUser($token);
		}

		private function applyMessageUser (TokenInterface $token = null) : void
		{
			$user = null;

			if ($token)
				$user = $token->getUser();

			if ($user && $user instanceof UserInterface)
				$this->messages->setUser($user);
		}

	}
}

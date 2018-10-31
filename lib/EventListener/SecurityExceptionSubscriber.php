<?php
namespace ZealByte\Identity\EventListener
{
	use Symfony\Component\Security\Core\Security;
	use Symfony\Component\Security\Core\Exception\DisabledException;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
	use Symfony\Component\HttpKernel\KernelEvents;
	use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
	use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
	use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
	use Symfony\Component\HttpKernel\Event\GetResponseEvent;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use ZealByte\Message\Provider\MessageProvider;

	class SecurityExceptionSubscriber implements EventSubscriberInterface
	{
		private $messages;

		private $authUtils;

		private $url_generator;

		public static function getSubscribedEvents ()
		{
			return [
				KernelEvents::CONTROLLER => [
					['onKernelRequest', 1]
				],
				KernelEvents::RESPONSE => [
					['onKernelResponse', 1]
				],
			];
		}

		public function __construct (AuthenticationUtils $auth_utils, MessageProvider $messages)
		{
			$this->authUtils = $auth_utils;
			$this->messages = $messages;
		}

		public function onKernelRequest (FilterControllerEvent $event)
		//public function onKernelRequest (GetResponseEvent $event) : void
		{
			//$clear = true;
			//$exception = $this->authUtils->getLastAuthenticationError($clear);

			//if ($exception)
				//$this->messages->addException($exception->getMessage(), $exception);
		}

		public function onKernelResponse (FilterResponseEvent $event) : void
		{
			if (!$this->url_generator)
				return;

			$error = null;
			$path = null;
			$request = $event->getRequest();
			$session = $request->getSession();

			if (!$error && $request->attributes->has(Security::AUTHENTICATION_ERROR))
				$error = $request->attributes->get(Security::AUTHENTICATION_ERROR);

			if (!$error && $session && $session->has(Security::AUTHENTICATION_ERROR)) {
				$error = $session->get(Security::AUTHENTICATION_ERROR);
				$session->remove(Security::AUTHENTICATION_ERROR);
			}

			if ($error) {
				if ($error instanceof DisabledException) {
					$path = $this->url_generator->generate('identity.user-disabled');

					if ($path)
						$event->setResponse(new RedirectResponse($path));
				} else {
					$this->messages->setRequest($request);
					$this->messages->addException($error->getMessage(), $error);
				}
			}

			return;
		}

	}
}

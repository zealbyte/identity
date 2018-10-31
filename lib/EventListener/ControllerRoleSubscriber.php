<?php
namespace ZealByte\Identity\EventListener
{
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\HttpKernel\KernelEvents;
	use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
	use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
	use Symfony\Component\Security\Core\Exception\DisabledException;
	use Symfony\Component\Security\Core\Exception\AccessDeniedException;

	class ControllerRoleSubscriber implements EventSubscriberInterface
	{
		CONST ON_CONTROLLER_ROLE_PRIORITY = 1;
		CONST ON_CONTROLLER_ROLE_OPTION_PRIORITY = 1;

		private $authorizationChecker;

		public static function getSubscribedEvents()
		{
			return [
				KernelEvents::CONTROLLER => [
					['onControllerRole', self::ON_CONTROLLER_ROLE_PRIORITY],
					['onControllerRoleOption', self::ON_CONTROLLER_ROLE_OPTION_PRIORITY],
				],
			];
		}

		public function __construct (AuthorizationCheckerInterface $authorization_checker)
		{
			$this->authorizationChecker = $authorization_checker;
		}

		public function onControllerRole (FilterControllerEvent $event)
		{
			if (!$event->getRequest()->attributes->has('_security'))
				return;

			$role = $event->getRequest()->attributes->get('_security');

			if (!$this->authorizationChecker->isGranted($role))
				throw new AccessDeniedException("You do not have authorization to access this page!");
		}

		public function onControllerRoleOption (FilterControllerEvent $event)
		{

		}

	}
}

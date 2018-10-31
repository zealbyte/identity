<?php
namespace ZealByte\Identity\Security\User
{
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\Security\Core\User\UserProviderInterface;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Security\Core\Exception\AuthenticationException;
	use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
	use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
	use ZealByte\Identity\User\IdentityUserInterface;
	use ZealByte\Identity\Usermanager;

	class IdentityUserProvider implements UserProviderInterface
	{
		/**
		 * @var ZealByte\Identity\UserManager
		 */
		private $userManager;

		/**
		 * @var Symfony\Component\HttpFoundation\Session\SessionInterface
		 */
		protected $session;

		/**
		 * @var int
		 */
		private $threshold = 5;

		/**
		 * @var string
		 */
		private $thresholdKey = 'identity.last_refresh_check';

		/**
		 *
		 */
		public function __construct (UserManager $user_manager, ?SessionInterface $session = null, ?array $config = null)
		{
			$this->userManager = $user_manager;

			if ($session)
				$this->session = $session;

			if ($config)
				$this->applyConfig($config);
		}

		/**
		 *
		 */
		public function loadUserByUsername ($username)
		{
			return $this->userManager->loadUserByUsername($username);
		}

		/**
		 *
		 */
		public function refreshUser (UserInterface $user)
		{
			$key = $this->thresholdKey;
			$seconds = $this->threshold;

			if ($this->session) {
				$time = time();
				$lastChecked = ($this->session->has($key)) ? $this->session->get($key) : 0;

				if ($time < ($lastChecked + $seconds)) {
					return $user;
				}

				$this->session->set($key, $time);
			}

			return $this->userManager->refreshUser($user);
		}

		/**
		 *
		 */
		public function supportsClass ($class)
		{
			return (IdentityUserInterface::class === $class);
		}

		/**
		 *
		 */
		private function applyConfig (array $config)
		{
			if (array_key_exists('threshold', $config))
				$this->threshold = $config['threshold'];

			if (array_key_exists('threshold_key', $config))
				$this->thresholdKey = $config['threshold_key'];
		}
	}
}

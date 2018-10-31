<?php
namespace ZealByte\Identity\Security\Authentication\Provider
{
	use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
	use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
	use Symfony\Component\Security\Core\User\UserProviderInterface;
	use Symfony\Component\Security\Core\User\UserCheckerInterface;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Security\Core\Exception\AuthenticationException;
	use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
	use Symfony\Component\Security\Core\Exception\BadCredentialsException;
	use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
	use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
	use ZealByte\Identity\Security\Authentication\Token\IdentityToken;
	use ZealByte\Identity\User\IdentityUserInterface;

	class IdentityAuthenticationProvider implements AuthenticationProviderInterface
	{
		private $encoderFactory;

		private $userProvider;

		private $providerKey;

		private $userChecker;

		private $hideUserNotFoundExceptions;

		/**
		 */
		public function __construct (string $fake, UserProviderInterface $userProvider, UserCheckerInterface $userChecker, string $providerKey, EncoderFactoryInterface $encoderFactory, bool $hideUserNotFoundExceptions = true)
		{
			$this->hideUserNotFoundExceptions = $hideUserNotFoundExceptions;
			$this->userChecker = $userChecker;
			$this->encoderFactory = $encoderFactory;
			$this->userProvider = $userProvider;
			$this->providerKey = $providerKey;
		}

		/**
		 * {@inheritdoc}
		 */
		public function authenticate(TokenInterface $token)
		{
			if (!$this->supports($token)) {
				throw new AuthenticationException('The token is not supported by this authentication provider.');
			}

			$username = $token->getUsername();

			if ('' === $username || null === $username) {
				$username = AuthenticationProviderInterface::USERNAME_NONE_PROVIDED;
			}

			try {
				$user = $this->retrieveUser($username, $token);
			} catch (UsernameNotFoundException $e) {
				if ($this->hideUserNotFoundExceptions) {
					throw new BadCredentialsException('Bad credentials.', 0, $e);
				}

				$e->setUsername($username);

				throw $e;
			}

			if (!$user instanceof IdentityUserInterface) {
				throw new AuthenticationServiceException('retrieveUser() must return a IdentityUserInterface.');
			}

			try {
				$this->userChecker->checkPreAuth($user);
				$this->checkAuthentication($user, $token);
				$this->userChecker->checkPostAuth($user);
			} catch (BadCredentialsException $e) {
				if ($this->hideUserNotFoundExceptions) {
					throw new BadCredentialsException('Bad credentials.', 0, $e);
				}

				throw $e;
			}

			$authenticatedToken = new IdentityToken($user, $token->getCredentials(), $this->providerKey, $this->getRoles($user, $token));
			$authenticatedToken->setAttributes($token->getAttributes());

			return $authenticatedToken;
		}

		/**
		 * {@inheritdoc}
		 */
		public function supports(TokenInterface $token)
		{
			return $token instanceof IdentityToken && $this->providerKey === $token->getProviderKey();
		}

		/**
		 * {@inheritdoc}
		 */
		protected function checkAuthentication (UserInterface $user, IdentityToken $token)
		{
			$currentUser = $token->getUser();

			if ($currentUser instanceof IdentityUserInterface) {
				if ($currentUser->getPassword() !== $user->getPassword()) {
					throw new BadCredentialsException('The credentials were changed from another session.');
				}
			} else {
				if ('' === ($presentedPassword = $token->getCredentials())) {
					throw new BadCredentialsException('The presented password cannot be empty.');
				}

				//@todo implement own logic for user credentials validation
				if (!$this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $presentedPassword, $user->getSalt())) {
					throw new BadCredentialsException('The presented password is invalid.');
				}
			}
		}

		/**
		 * {@inheritdoc}
		 */
		protected function retrieveUser ($username, IdentityToken $token)
		{
			$user = $token->getUser();

			if ($user instanceof IdentityUserInterface) {
				return $user;
			}

			try {
				$user = $this->userProvider->loadUserByUsername($username);

				if (!$user instanceof IdentityUserInterface) {
					throw new AuthenticationServiceException('The user provider must return a IdentityUserInterface object.');
				}

				return $user;
			} catch (UsernameNotFoundException $e) {
				$e->setUsername($username);
				throw $e;
			} catch (\Exception $e) {
				$e = new AuthenticationServiceException($e->getMessage(), 0, $e);
				$e->setToken($token);
				throw $e;
			}
		}

		/**
		 * Retrieves roles from user and appends SwitchUserRole if original token contained one.
		 *
		 * @return array The user roles
		 */
		private function getRoles (IdentityUserInterface $user, TokenInterface $token)
		{
			$roles = $user->getRoles();

			foreach ($token->getRoles() as $role) {
				if ($role instanceof SwitchUserRole) {
					$roles[] = $role;

					break;
				}
			}

			return $roles;
		}

	}
}

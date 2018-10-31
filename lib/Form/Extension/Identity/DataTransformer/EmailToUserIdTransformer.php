<?php
namespace ZealByte\Identity\Form\Extension\Identity\DataTransformer
{
	use Symfony\Component\Form\DataTransformerInterface;
	use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
	use ZealByte\Identity\User\UserInterface;
	use ZealByte\Identity\UserManager;

	class EmailToUserIdTransformer implements DataTransformerInterface
	{
		protected $userManager;

		public function __construct (UserManager $user_manager)
		{
			$this->userManager = $user_manager;
		}

		/**
		 * {@inheritdoc}
		 *
		 * @param User
		 * @return string The email address of the user entity
		 */
		public function transform (/* userId */ $value)
		{
			if (empty($value))
				return null;

			try {
				$value = (string) $value;
			}
			catch (\Exception $e) {
				throw new \Exception('Invalid value supplied for user ID!');
			}

			return $this->userManager->getUser($value)->getEmail();
		}

		/**
		 * {@inheritdoc}
		 *
		 * @param string The email address of the user to lookup
		 * @return User The user found from email address
		 */
		public function reverseTransform ($value)
		{
			if (empty($value))
				return null;

			try {
				$value = (string) $value;

				return $this->userManager->loadUserByUsername($value)->getId();
			}
			catch (UsernameNotFoundException $e) {
				throw new \Exception($e->getMessage());
			}
			catch (\Exception $e) {
				throw new \Exception('Invalid value supplied for user ID!');
			}

		}
	}
}

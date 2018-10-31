<?php
namespace ZealByte\Identity\Form\Extension\Identity\DataTransformer
{
	use Symfony\Component\Form\DataTransformerInterface;
	use ZealByte\Identity\User\UserInterface;
	use ZealByte\Identity\UserManager;

	class EmailToUserTransformer implements DataTransformerInterface
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
		public function transform ($value)
		{
			if ($value && ($value instanceof UserInterface))
				return $value->getEmail();

			return '';
		}

		/**
		 * {@inheritdoc}
		 *
		 * @param string The email address of the user to lookup
		 * @return User The user found from email address
		 */
		public function reverseTransform ($value)
		{
			if ($value && $this->userManager)
				return $this->userManager->loadUserByUsername($value);

			return null;
		}
	}
}

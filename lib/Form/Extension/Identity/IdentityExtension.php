<?php
namespace ZealByte\Identity\Form\Extension\Identity
{
	use Symfony\Component\Form\AbstractExtension;
	use Symfony\Component\Translation\TranslatorInterface;
	use ZealByte\Identity\UserManager;
	use ZealByte\Identity\Form\Extension\Identity\Type\IdentitySubmitType;
	use ZealByte\Identity\Form\Extension\Identity\Type\UserIdType;
	use ZealByte\Identity\Form\Extension\Identity\Type\UserType;

	class IdentityExtension extends AbstractExtension
	{
		private $userManager;


		public function __construct (?UserManager $user_manager = null)
		{
			if ($user_manager)
				$this->setUserManager($user_manager);
		}

		public function setUserManager (UserManager $user_manager) : self
		{
			$this->userManager = $user_manager;

			return $this;
		}

		protected function loadTypes ()
		{
			return [
				new UserIdType($this->userManager),
				new UserType($this->userManager),
				new IdentitySubmitType(),
			];
		}

	}
}

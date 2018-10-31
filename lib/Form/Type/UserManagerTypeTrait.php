<?php
namespace ZealByte\Identity\Form\Type
{
	use ZealByte\Identity\UserManager;

	trait UserManagerTypeTrait
	{
		private $userManager;

		public function __construct (?UserManager $user_manager = null)
		{
			if ($user_manager)
				$this->setUserManager($user_manager);
		}

		public function getUserManager () : UserManager
		{
			return $this->userManager;
		}

		public function setUserManager (UserManager $user_manager) : self
		{
			$this->userManager = $user_manager;

			return $this;
		}

	}
}

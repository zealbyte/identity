<?php
namespace ZealByte\Identity\Event
{
	use Symfony\Component\EventDispatcher\Event;
	use ZealByte\Identity\Entity\User;

	class UserEvent extends Event
	{
		protected $user;

		public function __construct (User $user)
		{
			$this->user = $user;
		}

		/**
		 * @return User
		 */
		public function getUser ()
		{
			return $this->user;
		}
	}
}

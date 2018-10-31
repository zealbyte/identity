<?php
namespace ZealByte\Identity\Event
{
	class LoginFormEvent extends IdentityFormEvent
	{
		private $showRegistrationLink;

		public function isShowRegistrationLink () : bool
		{
			return ($this->showRegistrationLink) ? true : false;
		}

		public function setShowRegistrationLink (bool $show_registration_link) : LoginFormEvent
		{
			$this->showRegistrationLink = $show_registration_link;

			return $this;
		}
	}
}

<?php
namespace ZealByte\Identity\Event
{
	use ZealByte\Identity\Recover\RecoverToken;

	class RecoverProcessFormEvent extends IdentityFormEvent
	{
		private $recoverToken;

		/**
		 *
		 */
		public function getRecoverToken () : IdentityRecoverToken
		{
			return $this->recoverToken;
		}

		/**
		 *
		 */
		public function hasRecoverToken () : bool
		{
			return ($this->recoverToken) ? true : false;
		}

		/**
		 *
		 */
		public function setRecoverToken (RecoverToken $recover_token) : self
		{
			$this->recoverToken = $recover_token;

			return $this;
		}
	}
}

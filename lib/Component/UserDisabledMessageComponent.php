<?php
namespace ZealByte\Identity\Component
{
	use ZealByte\Platform\Component\Component;

	class UserDisabledMessageComponent extends Component
	{
		private $email;

		private $from_address;

		private $resend_url;

		public function getTemplateName () : string
		{
			return '@Identity/status/user_disabled.html.twig';
		}

		public function getBlockName () : string
		{
			return 'component';
		}

		/**
		 * Getters
		 */
		public function getEmail () : ?string
		{
			return $this->email;
		}

		public function getFromAddress () : string
		{
			return $this->from_address;
		}

		public function getResendUrl () : string
		{
			return $this->resend_url;
		}

		/**
		 * Setters
		 */
		public function setEmail (string $email = null) : self
		{
			$this->email = $email;

			return $this;
		}

		public function setFromAddress (string $fromAddress) : self
		{
			$this->from_address = $fromAddress;

			return $this;
		}

		public function setResendUrl (string $resendUrl) : self
		{
			$this->resend_url = $resendUrl;

			return $this;
		}
	}
}

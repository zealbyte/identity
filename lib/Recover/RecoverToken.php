<?php
namespace ZealByte\Identity\Recover
{
	class RecoverToken implements RecoverTokenInterface
	{
		private $key;

		private $userId;

		private $rsvp;

		private $trial;

		private $status;


		public function getStatus () : string
		{
			return (string) $this->status;
		}

		public function getUserId () : string
		{
			return (string) $this->userId;
		}

		public function getKey () : string
		{
			return (string) $this->key;
		}

		public function getRsvp () : string
		{
			return (string) $this->rsvp;
		}

		public function getTrial () : int
		{
			return (int) $this->trial;
		}

		public function isExpired () : bool
		{
			return false;
		}

		public function isRevoked () : bool
		{
			return false;
		}

		public function setStatus (string $status) : self
		{
			$this->status = $status;

			return $this;
		}

		public function setUserId ($user_id) : self
		{
			$this->userId = $user_id;

			return $this;
		}

		public function setKey (string $key) : self
		{
			$this->key = $key;

			return $this;
		}

		public function setRsvp (string $rsvp) : self
		{
			$this->rsvp = $rsvp;

			return $this;
		}

		public function setTrial (int $trial) : self
		{
			$this->trial = $trial;

			return $this;
		}
	}
}

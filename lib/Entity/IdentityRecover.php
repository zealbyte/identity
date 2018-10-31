<?php
namespace ZealByte\Identity\Entity
{
	use DateTimeInterface;

	class IdentityRecover
	{
    /**
     * @var uuid
     */
		private $id;

    /**
     * @var string
     */
		private $userId;

    /**
     * @var string
     */
		private $key;

    /**
     * @var string
     */
		private $code;

    /**
     * @var int
     */
		private $attempts;

    /**
     * @var bool
     */
		private $revoked;

    /**
     * @var string
		 */
		private $status;

    /**
     * @var DateTime
		 */
		private $dateRequested;


    /**
     * Get id.
     *
     * @return string
     */
		public function getId ()
		{
			return $this->id;
		}

    /**
     * Set id.
     *
     * @return string
     */
		public function setId ($id) : self
		{
			$this->id = $id;

			return $this;
		}

    /**
     * Get userId.
     *
     * @return string
     */
		public function getUserId () : string
		{
			return $this->userId;
		}

    /**
     * Set userId.
     *
     * @param string $user_id
     *
     * @return IdentityRecover
     */
		public function setUserId (string $user_id) : self
		{
			$this->userId = $user_id;

			return $this;
		}

    /**
     * Get key.
     *
     * @return string
     */
		public function getKey () : string
		{
			return $this->key;
		}

    /**
     * Set key.
     *
     * @param string $key
     *
     * @return ZealByte\Identity\Entity\IdentityRecover
     */
		public function setKey (string $key) : self
		{
			$this->key = $key;

			return $this;
		}

    /**
     * Get code.
     *
     * @return string
     */
		public function getCode () : string
		{
			return $this->code;
		}

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return IdentityRecover
     */
		public function setCode (string $code) : self
		{
			$this->code = $code;

			return $this;
		}

    /**
     * Get attempts.
     *
     * @return string
     */
		public function getAttempts () : string
		{
			return $this->attempts;
		}

    /**
     * Set attempts.
     *
     * @param int $attempts
     *
     * @return IdentityRecover
     */
		public function setAttempts (string $attempts) : self
		{
			$this->attempts = $attempts;

			return $this;
		}

    /**
     * Is revoked.
     *
     * @return bool
     */
		public function isRevoked () : bool
		{
			return $this->revoked;
		}

    /**
     * Set revoked.
     *
     * @param bool $revoked
     *
     * @return IdentityRecover
     */
		public function setRevoked (bool $revoked) : self
		{
			$this->revoked = $revoked;

			return $this;
		}

    /**
     * Get status
     *
     * @return string
     */
		public function getStatus ()
		{
			return $this->status;
		}

		/**
		 * Set status
		 *
		 * @return IdentityRecover
		 */
		public function setStatus (string $status) : self
		{
			$this->status = $status;

			return $this;
		}

    /**
     * Get dateRequested.
     *
     * @return DateTimeInterface
     */
		public function getDateRequested () : DateTimeInterface
		{
			return $this->dateRequested;
		}

    /**
     * Set dateRequested.
     *
     * @param DateTimeInterface $dateRequested
     *
     * @return IdentityRecover
     */
		public function setDateRequested (DateTimeInterface $date_requested) : self
		{
			$this->dateRequested = $date_requested;

			return $this;
		}

	}
}

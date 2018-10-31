<?php
namespace ZealByte\Identity\Entity
{
	use DateTimeInterface;

	/**
	 * PamLogin
	 */
	class PamLogin
	{
		/**
		 * @var bool
		 */
		private $isVerified = '0';

		/**
		 * @var \DateTime
		 */
		private $dateAdded;

		/**
		 * @var \DateTime
		 */
		private $dateModified;

		/**
		 * @var int
		 */
		private $pamId;

		/**
		 * @var int
		 */
		private $pamIpId;


		/**
		 * Set isVerified.
		 *
		 * @param bool $isVerified
		 *
		 * @return PamLogin
		 */
		public function setIsVerified($isVerified)
		{
			$this->isVerified = $isVerified;

			return $this;
		}

		/**
		 * Get isVerified.
		 *
		 * @return bool
		 */
		public function getIsVerified()
		{
			return $this->isVerified;
		}

		/**
		 * Set dateAdded.
		 *
		 * @param \DateTime $dateAdded
		 *
		 * @return PamLogin
		 */
		public function setDateAdded($dateAdded)
		{
			$this->dateAdded = $dateAdded;

			return $this;
		}

		/**
		 * Get dateAdded.
		 *
		 * @return \DateTime
		 */
		public function getDateAdded()
		{
			return $this->dateAdded;
		}

		/**
		 * Set dateModified.
		 *
		 * @param \DateTime $dateModified
		 *
		 * @return PamLogin
		 */
		public function setDateModified($dateModified)
		{
			$this->dateModified = $dateModified;

			return $this;
		}

		/**
		 * Get dateModified.
		 *
		 * @return \DateTime
		 */
		public function getDateModified()
		{
			return $this->dateModified;
		}

		/**
		 * Set pamId.
		 *
		 * @param int $pamId
		 *
		 * @return PamLogin
		 */
		public function setPamId($pamId)
		{
			$this->pamId = $pamId;

			return $this;
		}

		/**
		 * Get pamId.
		 *
		 * @return int
		 */
		public function getPamId()
		{
			return $this->pamId;
		}

		/**
		 * Set pamIpId.
		 *
		 * @param int $pamIpId
		 *
		 * @return PamLogin
		 */
		public function setPamIpId($pamIpId)
		{
			$this->pamIpId = $pamIpId;

			return $this;
		}

		/**
		 * Get pamIpId.
		 *
		 * @return int
		 */
		public function getPamIpId()
		{
			return $this->pamIpId;
		}
	}
}

<?php
namespace ZealByte\Identity\Entity
{
	use DateTimeInterface;

	/**
	 * PamIp
	 */
	class PamIp
	{
		/**
		 * @var string
		 */
		private $ip;

		/**
		 * @var \DateTime
		 */
		private $dateAdded;

		/**
		 * @var int
		 */
		private $pamIpId;


		/**
		 * Set ip.
		 *
		 * @param string $ip
		 *
		 * @return PamIp
		 */
		public function setIp($ip)
		{
			$this->ip = $ip;

			return $this;
		}

		/**
		 * Get ip.
		 *
		 * @return string
		 */
		public function getIp()
		{
			return $this->ip;
		}

		/**
		 * Set dateAdded.
		 *
		 * @param \DateTime $dateAdded
		 *
		 * @return PamIp
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

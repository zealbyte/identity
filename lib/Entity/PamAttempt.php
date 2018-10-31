<?php
namespace ZealByte\Identity\Entity
{
	use DateTimeInterface;

	/**
	 * PamAttempt
	 */
	class PamAttempt
	{
		/**
		 * @var string|null
		 */
		private $userAgent;

		/**
		 * @var bool
		 */
		private $isSuccess = '0';

		/**
		 * @var \DateTime
		 */
		private $dateAdded = '0000-00-00 00:00:00';

		/**
		 * @var int
		 */
		private $pamAttemptId;

		/**
		 * @var \App\Entity\PamUser
		 */
		private $pam;

		/**
		 * @var \App\Entity\PamIp
		 */
		private $pamIp;


		/**
		 * Set userAgent.
		 *
		 * @param string|null $userAgent
		 *
		 * @return PamAttempt
		 */
		public function setUserAgent($userAgent = null)
		{
			$this->userAgent = $userAgent;

			return $this;
		}

		/**
		 * Get userAgent.
		 *
		 * @return string|null
		 */
		public function getUserAgent()
		{
			return $this->userAgent;
		}

		/**
		 * Set isSuccess.
		 *
		 * @param bool $isSuccess
		 *
		 * @return PamAttempt
		 */
		public function setIsSuccess($isSuccess)
		{
			$this->isSuccess = $isSuccess;

			return $this;
		}

		/**
		 * Get isSuccess.
		 *
		 * @return bool
		 */
		public function getIsSuccess()
		{
			return $this->isSuccess;
		}

		/**
		 * Set dateAdded.
		 *
		 * @param \DateTime $dateAdded
		 *
		 * @return PamAttempt
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
		 * Get pamAttemptId.
		 *
		 * @return int
		 */
		public function getPamAttemptId()
		{
			return $this->pamAttemptId;
		}

		/**
		 * Set pam.
		 *
		 * @param \App\Entity\PamUser|null $pam
		 *
		 * @return PamAttempt
		 */
		public function setPam(\App\Entity\PamUser $pam = null)
		{
			$this->pam = $pam;

			return $this;
		}

		/**
		 * Get pam.
		 *
		 * @return \App\Entity\PamUser|null
		 */
		public function getPam()
		{
			return $this->pam;
		}

		/**
		 * Set pamIp.
		 *
		 * @param \App\Entity\PamIp|null $pamIp
		 *
		 * @return PamAttempt
		 */
		public function setPamIp(\App\Entity\PamIp $pamIp = null)
		{
			$this->pamIp = $pamIp;

			return $this;
		}

		/**
		 * Get pamIp.
		 *
		 * @return \App\Entity\PamIp|null
		 */
		public function getPamIp()
		{
			return $this->pamIp;
		}
	}
}

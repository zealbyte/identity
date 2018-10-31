<?php
namespace ZealByte\Identity\Entity
{
	use DateTimeInterface;

	/**
	 * PamUserGroup
	 */
	class PamUserGroup
	{
		/**
		 * @var int
		 */
		private $pamGroupId;

		/**
		 * @var \App\Entity\PamUser
		 */
		private $pam;


		/**
		 * Set pamGroupId.
		 *
		 * @param int $pamGroupId
		 *
		 * @return PamUserGroup
		 */
		public function setPamGroupId($pamGroupId)
		{
			$this->pamGroupId = $pamGroupId;

			return $this;
		}

		/**
		 * Get pamGroupId.
		 *
		 * @return int
		 */
		public function getPamGroupId()
		{
			return $this->pamGroupId;
		}

		/**
		 * Set pam.
		 *
		 * @param \App\Entity\PamUser $pam
		 *
		 * @return PamUserGroup
		 */
		public function setPam(\App\Entity\PamUser $pam)
		{
			$this->pam = $pam;

			return $this;
		}

		/**
		 * Get pam.
		 *
		 * @return \App\Entity\PamUser
		 */
		public function getPam()
		{
			return $this->pam;
		}
	}
}

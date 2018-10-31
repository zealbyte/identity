<?php
namespace ZealByte\Identity\Entity
{
	use DateTimeInterface;

	/**
	 * PamGroup
	 */
	class PamGroup
	{
		/**
		 * @var string
		 */
		private $name;

		/**
		 * @var string
		 */
		private $roles;

		/**
		 * @var \DateTime
		 */
		private $dateAdded = '0000-00-00 00:00:00';

		/**
		 * @var \DateTime
		 */
		private $dateModified = '0000-00-00 00:00:00';

		/**
		 * @var int
		 */
		private $pamGroupId;


		/**
		 * Set name.
		 *
		 * @param string $name
		 *
		 * @return PamGroup
		 */
		public function setName($name)
		{
			$this->name = $name;

			return $this;
		}

		/**
		 * Get name.
		 *
		 * @return string
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * Set roles.
		 *
		 * @param string $roles
		 *
		 * @return PamGroup
		 */
		public function setRoles($roles)
		{
			$this->roles = $roles;

			return $this;
		}

		/**
		 * Get roles.
		 *
		 * @return string
		 */
		public function getRoles()
		{
			return $this->roles;
		}

		/**
		 * Set dateAdded.
		 *
		 * @param \DateTime $dateAdded
		 *
		 * @return PamGroup
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
		 * @return PamGroup
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
		 * Get pamGroupId.
		 *
		 * @return int
		 */
		public function getPamGroupId()
		{
			return $this->pamGroupId;
		}
	}
}

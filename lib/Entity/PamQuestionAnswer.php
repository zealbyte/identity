<?php
namespace ZealByte\Identity\Entity
{
	use DateTimeInterface;

	/**
	 * PamQuestionAnswer
	 */
	class PamQuestionAnswer
	{
		/**
		 * @var string
		 */
		private $answer = '';

		/**
		 * @var int
		 */
		private $questionId;

		/**
		 * @var string
		 */
		private $userGuid;


		/**
		 * Set answer.
		 *
		 * @param string $answer
		 *
		 * @return PamQuestionAnswer
		 */
		public function setAnswer($answer)
		{
			$this->answer = $answer;

			return $this;
		}

		/**
		 * Get answer.
		 *
		 * @return string
		 */
		public function getAnswer()
		{
			return $this->answer;
		}

		/**
		 * Set questionId.
		 *
		 * @param int $questionId
		 *
		 * @return PamQuestionAnswer
		 */
		public function setQuestionId($questionId)
		{
			$this->questionId = $questionId;

			return $this;
		}

		/**
		 * Get questionId.
		 *
		 * @return int
		 */
		public function getQuestionId()
		{
			return $this->questionId;
		}

		/**
		 * Set userGuid.
		 *
		 * @param string $userGuid
		 *
		 * @return PamQuestionAnswer
		 */
		public function setUserGuid($userGuid)
		{
			$this->userGuid = $userGuid;

			return $this;
		}

		/**
		 * Get userGuid.
		 *
		 * @return string
		 */
		public function getUserGuid()
		{
			return $this->userGuid;
		}
	}
}

<?php
namespace ZealByte\Identity\Entity
{
	use DateTimeInterface;

	/**
	 * PamQuestion
	 */
	class PamQuestion
	{
		/**
		 * @var int
		 */
		private $localeId;

		/**
		 * @var string
		 */
		private $question = '';

		/**
		 * @var int
		 */
		private $questionId;


		/**
		 * Set localeId.
		 *
		 * @param int $localeId
		 *
		 * @return PamQuestion
		 */
		public function setLocaleId($localeId)
		{
			$this->localeId = $localeId;

			return $this;
		}

		/**
		 * Get localeId.
		 *
		 * @return int
		 */
		public function getLocaleId()
		{
			return $this->localeId;
		}

		/**
		 * Set question.
		 *
		 * @param string $question
		 *
		 * @return PamQuestion
		 */
		public function setQuestion($question)
		{
			$this->question = $question;

			return $this;
		}

		/**
		 * Get question.
		 *
		 * @return string
		 */
		public function getQuestion()
		{
			return $this->question;
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
	}
}

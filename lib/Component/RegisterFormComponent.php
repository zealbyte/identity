<?php
namespace ZealByte\Identity\Component
{
	use Symfony\Component\Form\FormView;
	use ZealByte\Platform\Component\Component;

	class RegisterFormComponent extends Component
	{
		const BLOCK_NAME = 'register_component';

		const VIEW = '@Identity/components.html.twig';

		private $form;

		private $username_required;

		/**
		 * {@inheritdoc}
		 */
		public function getData () : array
		{
			return $this->getParameters();
		}

		/**
		 * {@inheritdoc}
		 */
		public function getParameters () : array
		{
			return [
				'form' => $this->getForm(),
				'username_required' => $this->getUserNameRequired(),
			];
		}

		/**
		 * Getters
		 */
		public function getForm () : FormView
		{
			return $this->form;
		}

		public function getUsernameRequired () : ?bool
		{
			return $this->username_required;
		}

		/**
		 * Setters
		 */
		public function setForm (FormView $form) : self
		{
			$this->form = $form;

			return $this;
		}

		public function setUsernameRequired (bool $usernameRequired) : self
		{
			$this->username_required = $usernameRequired;

			return $this;
		}
	}
}

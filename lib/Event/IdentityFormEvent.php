<?php
namespace ZealByte\Identity\Event
{
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\HttpFoundation\Request;

	class IdentityFormEvent extends IdentityEvent
	{
		private $form;

		private $request;

		/**
		 *
		 */
		public function getForm () : FormInterface
		{
			return $this->form;
		}

		/**
		 *
		 */
		public function getRequest () : Request
		{
			return $this->request;
		}

		/**
			*
		 */
		public function hasForm () : bool
		{
			return ($this->form) ? true : false;
		}

		/**
		 *
		 */
		public function hasRequest () : bool
		{
			return ($this->request) ? true : false;
		}

		/**
			*
		 */
		public function setForm (FormInterface $form) : IdentityFormEvent
		{
			$this->form = $form;

			return $this;
		}

		/**
			*
		 */
		public function setRequest (Request $request) : IdentityFormEvent
		{
			$this->request = $request;

			return $this;
		}
	}
}

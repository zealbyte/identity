<?php
namespace ZealByte\Identity\Component
{
	use Symfony\Component\Form\FormView;
	use ZealByte\Platform\Component\DispatcherComponentAbstract;
	use ZealByte\Identity\Event\LoginFormEvent;
	use ZealByte\Identity\ZealByteIdentity;

	class LoginFormComponent extends DispatcherComponentAbstract
	{
		const BLOCK_NAME = 'form_login';

		const VIEW = '@Identity/components.html.twig';

		/**
		 * {@inheritdoc}
		 */
		public function getData () : array
		{
			$this->stat();
			$this->setLoginForm();

			return parent::getData();
		}

		/**
		 * {@inheritdoc}
		 */
		public function getParameters () : array
		{
			$this->stat();

			return parent::getParameters();
		}

		/**
		 *
		 */
		public function hasLoginForm () : bool
		{
			return ($this->hasParameter('form') && $this->getParameter('form'));
		}

		/**
		 *
		 */
		public function isShowRegistrationLink () : bool
		{
			return ($this->hasParameter('show_registration_link') && $this->getParameter('show_registration_link'));
		}

		/**
		 *
		 */
		public function setShowRegistrationLink (bool $show_registration_link) : self
		{
			$this->setParameter('show_registration_link', $show_registration_link);

			return $this;
		}

		/**
		 *
		 */
		public function setLoginForm (?FormView $form = null) : self
		{
			$this->setParameter('form', $form);

			return $this;
		}

		protected function stat () : void
		{
			if (!$this->hasParameter('show_registration_link'))
				$this->setShowRegistrationLink(false);

			if (!$this->hasLoginForm())
				$this->discoverLoginForm();
		}

		protected function discoverLoginForm () : void
		{
			$event = new LoginFormEvent();

			$this->getEventDispatcher()
				->dispatch(ZealByteIdentity::EVENT_LOGIN_FORM, $event);

			if ($event->isShowRegistrationLink())
				$this->setShowRegistrationLink($event->isShowRegistrationLink());

			if ($event->hasForm())
				$this->setLoginForm($event->getForm()->createView());
		}

	}
}

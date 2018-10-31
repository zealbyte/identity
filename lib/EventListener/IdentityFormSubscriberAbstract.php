<?php
namespace ZealByte\Identity\EventListener
{
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormFactoryInterface;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use ZealByte\Platform\Context\ContextFactoryInterface;

	abstract class IdentityFormSubscriberAbstract implements IdentitySubscriberInterface
	{
		private $formFactory;

		private $requestStack;

		private $urlGenerator;

		/**
		 *
		 */
		public function hasFormFactory () : bool
		{
			return ($this->formFactory) ? true : false;
		}

		/**
		 *
		 */
		public function hasUrlGenerator () : bool
		{
			return ($this->urlGenerator) ? true : false;
		}

		/**
		 *
		 */
		public function hasRequestStack () : bool
		{
			return ($this->requestStack) ? true : false;
		}

		/**
		 *
		 */
		public function setFormFactory (FormFactoryInterface $form_factory) : IdentitySubscriberInterface
		{
			$this->formFactory = $form_factory;

			return $this;
		}

		/**
		 *
		 */
		public function setRequestStack (RequestStack $request_stack) : IdentitySubscriberInterface
		{
			$this->requestStack = $request_stack;

			return $this;
		}

		/**
		 *
		 */
		public function setUrlGenerator (UrlGeneratorInterface $url_generator) : IdentitySubscriberInterface
		{
			$this->urlGenerator = $url_generator;

			return $this;
		}

		/**
		 *
		 */
		protected function getFormFactory () : FormFactoryInterface
		{
			return $this->formFactory;
		}

		/**
		 *
		 */
		protected function getUrlGenerator () : UrlGeneratorInterface
		{
			return $this->urlGenerator;
		}

		/**
		 *
		 */
		protected function getRequestStack () : RequestStack
		{
			return $this->requestStack;
		}
	}
}

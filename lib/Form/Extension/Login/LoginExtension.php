<?php
namespace ZealByte\Identity\Form\Extension\Login
{
	use Symfony\Component\Form\AbstractExtension;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
	use ZealByte\Identity\UserManager;
	use ZealByte\Identity\Form\Extension\Login\Type\LoginFormType;
	use ZealByte\Identity\Form\Extension\Login\Type\LoginPasswordType;
	use ZealByte\Identity\Form\Extension\Login\Type\LoginRememberMeType;
	use ZealByte\Identity\Form\Extension\Login\Type\LoginTargetType;
	use ZealByte\Identity\Form\Extension\Login\Type\LoginUsernameType;

	class LoginExtension extends AbstractExtension
	{
		/**
		 * @var Symfony\Component\HttpFoundation\RequestStack
		 */
		private $requestStack;

		/**
		 * @var Symfony\Component\Security\Http\Authentication\AuthenticationUtils
		 */
		private $authenticationUtils;

		private $userManager;

		private $translator;


		public function __construct(?UserManager $user_manager = null, ?RequestStack $request_stack = null, ?AuthenticationUtils $authentication_utils = null, ?TranslatorInterface $translator = null)
		{
			if ($user_manager)
				$this->setUserManager($user_manager);

			if ($request_stack)
				$this->setRequestStack($request_stack);

			if ($authentication_utils)
				$this->setAuthenticationUtils($authentication_utils);

			if ($translator)
				$this->setTranslator($translator);
		}

		/**
		 *
		 */
		public function setUserManager (UserManager $user_manager) : self
		{
			$this->userManager = $user_manager;

			return $this;
		}

		/**
		 *
		 */
		public function setRequestStack (RequestStack $request_stack) : self
		{
			$this->requestStack = $request_stack;

			return $this;
		}

		/**
		 *
		 */
		public function setAuthenticationUtils (AuthenticationUtils $authentication_utils) : self
		{
			$this->authenticationUtils = $authentication_utils;

			return $this;
		}

		/**
		 *
		 */
		public function setTranslator (TranslatorInterface $translator) : self
		{
			$this->translator = $translator;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		protected function loadTypes ()
		{
			return [
				new LoginFormType($this->requestStack, $this->authenticationUtils),
				new LoginPasswordType(),
				new LoginRememberMeType(),
				new LoginTargetType(),
				new LoginUsernameType(),
			];
		}

	}
}

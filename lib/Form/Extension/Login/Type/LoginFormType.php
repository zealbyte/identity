<?php
namespace ZealByte\Identity\Form\Extension\Login\Type
{
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\FormTypeInterface;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\Extension\Core\Type\FormType;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Validator\Constraints as Assert;
	use ZealByte\Identity\Form\Extension\Identity\Type\IdentitySubmitType;
	use ZealByte\Identity\Form\Extension\Login\EventListener\LoginFormSubscriber;
	use ZealByte\Util;

	class LoginFormType extends AbstractType implements FormTypeInterface
	{
		/**
		 * @var Symfony\Component\Security\Http\Authentication\AuthenticationUtils
		 */
		private $authenticationUtils;

		/**
		 * @var Symfony\Component\HttpFoundation\RequestStack
		 */
		private $requestStack;

		public function __construct(RequestStack $request_stack, AuthenticationUtils $authentication_utils)
		{
			$this->requestStack = $request_stack;
			$this->authenticationUtils = $authentication_utils;
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->add($options['target_field_name'], LoginTargetType::class)
				->add($options['username_field_name'], LoginUsernameType::class, [
					'constraints' => [new Assert\NotBlank()],
				])
				->add($options['password_field_name'], LoginPasswordType::class, [
					'constraints' => [new Assert\NotBlank()],
				]);

			if ($options['show_rememberme'])
				$builder->add($options['rememberme_field_name'], LoginRememberMeType::class);

			if ($options['show_submit'])
				$builder->add('login', IdentitySubmitType::class, [
					'label' => 'identity.label.login',
				]);

			$builder->addEventSubscriber(new LoginFormSubscriber($this->requestStack, $this->authenticationUtils, $options));
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildView (FormView $view, FormInterface $form, array $options)
		{
			$view->vars['id'] = Util\Random::alphaNumericString();

			$view->vars['attr'] = array_merge($view->vars['attr'], [
				'novalidate' => 'novalidate',
			]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'csrf_token_id' => 'authenticate',
				'csrf_field_name' => '_security_token',
				'target_field_name' => '_target_path',
				'username_field_name' => '_username',
				'password_field_name' => '_password',
				'rememberme_field_name' => '_remember_me',
				'rememberme_cookie_name' => 'REMEMBER_ME',
				'show_rememberme' => false,
				'show_submit' => true,
			]);

			$resolver->setAllowedTypes('target_field_name', 'string');
			$resolver->setAllowedTypes('username_field_name', 'string');
			$resolver->setAllowedTypes('password_field_name', 'string');
			$resolver->setAllowedTypes('rememberme_field_name', 'string');
			$resolver->setAllowedTypes('rememberme_cookie_name', 'string');
			$resolver->setAllowedTypes('show_rememberme', 'bool');
			$resolver->setAllowedTypes('show_submit', 'bool');
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix ()
		{
			return '';
		}

		/**
		 * {@inheritdoc}
		 */
		public function getParent ()
		{
			return FormType::class;
		}

	}
}

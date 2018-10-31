<?php
namespace ZealByte\Identity\Form\Extension\Register\Type
{
	use Symfony\Component\Form\Extension\Core\Type\FormType;
	use Symfony\Component\Form\FormTypeInterface;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Util\StringUtil;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type;

	class RegisterFormType extends AbstractType implements FormTypeInterface
	{
		/**
		 * {@inheritdoc}
		 */
		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->add($options['name_name'], Type\TextType::class, $options['name_options'])
				->add($options['email_name'], Type\EmailType::class, $options['email_options'])
				->add($options['username_name'], Type\TextType::class, $options['username_options'])
				->add($options['password_name'], Type\RepeatedType::class, $options['password_options']);
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'name_name' => 'name',
				'email_name' => 'email',
				'username_name' => 'username',
				'password_name' => 'password',
				'name_options' => [],
				'email_options' => [],
				'username_options' => [],
				'password_options' => [
					'type' => Type\PasswordType::class,
					'required' => true,
					'invalid_message' => 'The password fields must match.',
					'first_options'  => ['label' => 'Password'],
					'second_options' => ['label' => 'Re-type Password'],
				],
			]);

			$resolver->setAllowedTypes('name_name', 'string');
			$resolver->setAllowedTypes('email_name', 'string');
			$resolver->setAllowedTypes('username_name', 'string');
			$resolver->setAllowedTypes('password_name', 'string');
			$resolver->setAllowedTypes('name_options', 'array');
			$resolver->setAllowedTypes('email_options', 'array');
			$resolver->setAllowedTypes('username_options', 'array');
			$resolver->setAllowedTypes('password_options', 'array');
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix ()
		{
			return 'register';
		}

		/**
		 * {@inheritdoc}
		 */
		public function getParent ()
		{
			return Type\FormType::class;
		}

	}
}

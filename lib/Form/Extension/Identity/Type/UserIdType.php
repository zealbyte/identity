<?php
namespace ZealByte\Identity\Form\Extension\Identity\Type
{
	use Symfony\Component\Form\FormTypeInterface;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use ZealByte\Identity\Form\Extension\Identity\DataTransformer\EmailToUserIdTransformer;
	use ZealByte\Identity\Form\Type\UserManagerTypeTrait;

	class UserIdType extends AbstractType implements FormTypeInterface
	{
		use UserManagerTypeTrait;

		/**
		 * {@inheritdoc}
		 */
		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->addViewTransformer(new EmailToUserIdTransformer($this->getUserManager()));
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'attr' => [
					'autocomplete' => 'off',
				],
			]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix ()
		{
			return 'user_email';
		}

		/**
		 * {@inheritdoc}
		 */
		public function getParent ()
		{
			return TextType::class;
		}
	}
}

<?php
namespace ZealByte\Identity\Form\Extension\Identity\Type
{
	use Symfony\Component\Form\FormTypeInterface;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Util\StringUtil;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use ZealByte\Identity\Entity\User;
	use ZealByte\Identity\User\UserInterface;
	use ZealByte\Identity\Form\Type\UserManagerTypeTrait;
	use ZealByte\Identity\Form\Extension\Identity\DataTransformer\EmailToUserTransformer;

	class UserType extends AbstractType implements FormTypeInterface
	{
		use UserManagerTypeTrait;

		/**
		 * {@inheritdoc}
		 */
		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->addModelTransformer(new EmailToUserTransformer($this->getUserManager()));
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => User::class,
			]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix ()
		{
			return 'user';
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

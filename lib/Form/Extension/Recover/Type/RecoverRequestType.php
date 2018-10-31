<?php
namespace ZealByte\Identity\Form\Extension\Recover\Type
{
	use Symfony\Component\Form\FormTypeInterface;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\Extension\Core\Type\FormType;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Validator\Constraints as Assert;
	use ZealByte\Identity\Form\Extension\Identity\Type\UserIdType;
	use ZealByte\Identity\Form\Extension\Identity\Type\IdentitySubmitType;
	use ZealByte\Identity\Recover\RecoverToken;

	class RecoverRequestType extends AbstractType implements FormTypeInterface
	{
		/**
		 * {@inheritdoc}
		 */
		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('userId', UserIdType::class, [
					'required' => true,
					'label' => 'identity.label.username_or_email',
					'constraints' => [new Assert\NotBlank()],
				]);

			if ($options['show_submit'])
				$builder->add('send', IdentitySubmitType::class, [
					'label' => 'identity.label.send',
				]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildView (FormView $view, FormInterface $form, array $options)
		{
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
				'data_class' => RecoverToken::class,
				'show_submit' => true,
				'show_success' => true,
				'show_invalid' => true,
				'success_message' => 'identity.message.recover_request.success',
				'invalid_message' => 'identity.message.recover_request.invalid',
				'help' => 'identity.reset_request_instruction',
			]);

			$resolver->setAllowedTypes('show_submit', 'bool');
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix ()
		{
			return 'recover_request';
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

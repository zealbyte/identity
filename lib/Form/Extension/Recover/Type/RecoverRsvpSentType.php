<?php
namespace ZealByte\Identity\Form\Extension\Recover\Type
{
	use Symfony\Component\Form\FormTypeInterface;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\Extension\Core\Type;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use ZealByte\Identity\Form\Extension\Identity\Type\IdentitySubmitType;
	use ZealByte\Identity\Recover\RecoverToken;

	class RecoverRsvpSentType extends AbstractType implements FormTypeInterface
	{
		/**
		 * {@inheritdoc}
		 */
		public function buildForm (FormBuilderInterface $builder, array $options)
		{
			$builder
				->add('doresend', Type\HiddenType::class, [
					'required' => false,
					'mapped' => false,
				])
				->add('key', Type\HiddenType::class, [
					'required' => true,
				])
				->add('send', IdentitySubmitType::class, [
					'label' => 'identity.label.resend_rsvp',
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
				'show_success' => true,
				'show_invalid' => true,
				'success_message' => 'identity.message.recover_request.success',
				'invalid_message' => 'identity.message.recover_request.invalid',
				'help' => 'identity.rsvp_sent_info',
			]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix ()
		{
			return 'recover_request_sent';
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

<?php
namespace ZealByte\Identity\Form\Extension\Identity\Type
{
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\FormTypeInterface;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\Extension\Core\Type\FormType;
	use Symfony\Component\Form\Extension\Core\Type;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use ZealByte\Util;

	class IdentitySubmitType extends Type\SubmitType
	{
		/**
		 * {@inheritdoc}
		 */
		public function buildView (FormView $view, FormInterface $form, array $options)
		{
			$view->vars['id'] = Util\Random::alphaNumericString();

			parent::buildView($view, $form, $options);
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'required' => false,
				'attr' => [
					'class' => 'uk-button-primary uk-float-right',
				],
			]);

			parent::configureOptions($resolver);
		}
	}
}

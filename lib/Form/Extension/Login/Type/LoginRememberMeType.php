<?php
namespace ZealByte\Identity\Form\Extension\Login\Type
{
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\Extension\Core\Type;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use ZealByte\Util;

	class LoginRememberMeType extends Type\CheckboxType
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
				'label' => 'identity.label.remember_me',
			]);

			parent::configureOptions($resolver);
		}

	}
}

<?php
namespace ZealByte\Identity\Form\Extension\Login\Type
{
	use Symfony\Component\Form\FormView;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\Extension\Core\Type;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Validator\Constraints as Assert;
	use ZealByte\Util;

	class LoginUsernameType extends Type\TextType
	{
		/**
		 * {@inheritdoc}
		 */
		public function buildView (FormView $view, FormInterface $form, array $options)
		{
			$attrs = [];

			$view->vars['id'] = Util\Random::alphaNumericString();

			if ($options['show_placeholders'])
				$attrs['placeholder'] = $options['placeholder'];

			$view->vars['attr'] = array_merge($view->vars['attr'], [
				'autocomplete' => 'off',
				'novalidate' => 'novalidate',
			]);

			parent::buildView($view, $form, $options);
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions (OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'required' => true,
				'constraints' => [new Assert\NotBlank()],
				'show_placeholders' => true,
				'placeholder' => 'identity.label.username',
			]);

			$resolver->setAllowedTypes('show_placeholders', 'bool');

			parent::configureOptions($resolver);
		}

	}
}

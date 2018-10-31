<?php
namespace ZealByte\Identity\Component
{
	use ZealByte\Platform\Component\Component;

	class UserDisabledMessageComponent extends Component
	{
		public function getTemplateName () : string
		{
			return '@Identity/component/user_form_component.html.twig';
		}

		public function getBlockName () : string
		{
			return 'component';
		}

		public function getVars () : array
		{
			$this->builder
				->add('username', FormType\TextType::class)
				->add('email', FormType\EmailType::class)
				->add('firstname', FormType\TextType::class)
				->add('lastname', FormType\TextType::class)
				/*
				->add('name', GroupFormType::class, [
					'forms' => [
						[
							'name' => 'firstname',
							'type' => FormType\TextType::class,
						],
						[
							'name' => 'lastname',
							'type' => FormType\TextType::class,
						],
					],
				])
				 */
				->add('lastLogin', FormType\DateTimeType::class, [
					'date_widget' => 'single_text',
					'time_widget' => 'single_text',
				])
				->add('enabled', FormType\CheckboxType::class);
		}

	}
}


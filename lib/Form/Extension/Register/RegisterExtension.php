<?php
namespace ZealByte\Identity\Form\Extension\Register
{
	use Symfony\Component\Form\AbstractExtension;
	use ZealByte\Identity\Form\Extension\Register\Type\RegisterFormType;

	class RegisterExtension extends AbstractExtension
	{
		protected function loadTypes ()
		{
			return [
				new RegisterFormType(),
			];
		}

	}
}

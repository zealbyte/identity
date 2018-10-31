<?php
namespace ZealByte\Identity\Form\Extension\Recover
{
	use Symfony\Component\Form\AbstractExtension;
	use ZealByte\Identity\Form\Extension\Recover\Type\RecoverRequestType;
	use ZealByte\Identity\Form\Extension\Recover\Type\RecoverRsvpSentType;

	class IdentityExtension extends AbstractExtension
	{
		protected function loadTypes ()
		{
			return [
				new RecoverRequestType(),
				new RecoverRsvpSentType(),
			];
		}

	}
}

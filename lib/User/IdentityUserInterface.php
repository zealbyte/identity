<?php
namespace ZealByte\Identity\User
{
	use Symfony\Component\Security\Core\User\UserInterface;
	use Serializable;

	interface IdentityUserInterface extends UserInterface, Serializable
	{
	}
}

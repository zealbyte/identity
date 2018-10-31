<?php
namespace ZealByte\Identity\Security\User
{
	use Symfony\Component\Security\Core\User\UserCheckerInterface;
	use Symfony\Component\Security\Core\User\UserInterface;
	use Symfony\Component\Security\Core\Exception\AccountStatusException;
	use Symfony\Component\Security\Core\Exception\AccountExpiredException;
	use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
	use Symfony\Component\Security\Core\Exception\DisabledException;
	use Symfony\Component\Security\Core\Exception\LockedException;
	use ZealByte\Catalog\Inventory\CatalogFactoryInterface;
	use ZealByte\Catalog\Inventory\SpecRegistryInterface;
	use ZealByte\Catalog\InventoryRequest;

	class IdentityUserChecker implements UserCheckerInterface
	{
		/**
		 * @var ZealByte\Catalog\Inventory\CatalogFactoryInterface
		 */
		private $catalogFactory;

		/**
		 * @var ZealByte\Catalog\Inventory\SpecRegistryInterface
		 */
		private $specRegistry;

		/**
		 *
		 */
		public function __construct (CatalogFactoryInterface $catalog_factory, SpecRegistryInterface $spec_registry)
		{
			$this->catalogFactory = $catalog_factory;
			$this->specRegistry = $spec_registry;
		}

		/**
		 * @inheritdoc
		 *
		 * @throws AccountStatusException
		 */
		public function checkPreAuth (UserInterface $user)
		{
			$spec = $this->specRegistry->getSpec('user', 'identity');

			$inventoryRequest = (new InventoryRequest())
				->addIdentifier($user->getId());

			$catalog = $this->catalogFactory->createCatalog($spec, $inventoryRequest);

			return;
		}

		/**
		 * @inheritdoc
		 *
		 * @throws AccountStatusException
		 */
		public function checkPostAuth (UserInterface $user)
		{
			$spec = $this->specRegistry->getSpec('user', 'identity');

			$inventoryRequest = (new InventoryRequest())
				->addIdentifier($user->getId());

			$catalog = $this->catalogFactory->createCatalog($spec, $inventoryRequest);

			return;
		}
	}
}

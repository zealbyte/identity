<?php
namespace ZealByte\Identity
{
	use DateTime;
	use DateTimeInterface;
	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
	use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
	use Doctrine\DBAL\Connection;
	use ZealByte\Catalog\CatalogItemInterface;
	use ZealByte\Catalog\Inventory\CatalogFactoryInterface;
	use ZealByte\Catalog\Inventory\SpecRegistryInterface;
	use ZealByte\Catalog\InventoryRequest;
	use ZealByte\Identity\IdentityEvents;
	use ZealByte\Identity\Security\Authentication\Token\IdentityToken;
	use ZealByte\Identity\User\IdentityUserInterface;
	use ZealByte\Identity\Event\UserEvent;

	use ZealByte\Identity\Entity\IdentityRecoverToken;

	class UserManager
	{
		/**
		 *@var Connection
		 */
		protected $conn;

		/**
		 * @var ZealByte\Catalog\Inventory\CatalogFactoryInterface
		 */
		private $catalogFactory;

		/**
		 * @var ZealByte\Catalog\Inventory\SpecRegistryInterface
		 */
		private $specRegistry;

		/**
		 * @var EventDispatcher
		 */
		protected $dispatcher;

		/**
		 * @var User[]
		 */
		protected $identityMap = [];

		/**
		 * @var string
		 */
		protected $userClass = '\ZealByte\Bundle\UserBundle\User\User';

		/**
		 * @var string
		 */
		protected $userSpecAlias = 'user';

		/**
		 * @var string
		 */
		protected $userSpecCategory = 'identity';

		/**
		 * @var string
		 */
		protected $userTableName = 'pam_user';

		/**
		 * @var array
		 */
		protected $userColumns = [
			'id' => 'pam_id',
			'username' => 'username',
			'email' => 'email',
			'password' => 'password',
			'salt' => 'salt',
			'roles' => 'roles',
			'name' => 'name',
			'enabled' => 'enabled',
			'date_created' => 'date_added',
			'date_modified' => 'date_modified',
		];

		/**
		 * Constructor.
		 *
		 * @param Connection $conn
		 */
		public function __construct (Connection $connection, CatalogFactoryInterface $catalog_factory, SpecRegistryInterface $spec_registry, EventDispatcherInterface $event_dispatcher)
		{
			$this->conn = $connection;
			$this->catalogFactory = $catalog_factory;
			$this->specRegistry = $spec_registry;
			$this->dispatcher = $event_dispatcher;
		}

		public function initiatePasswordRecoveryWorkflow (IdentityRecoverToken $token)
		{
			$registry = (new Workflow\PasswordRecoveryWorkflow())->defineWorkflow();

			$workflow = $registry->get($token);
		}

		/**
		 * Loads the user for the given username or email address.
		 *
		 * Required by UserProviderInterface.
		 *
		 * @param string $username The username
		 * @return IdentityUserInterface
		 * @throws UsernameNotFoundException if the user is not found
		 */
		public function loadUserByUsername ($username) : IdentityUserInterface
		{
			if (strpos($username, '@') !== false) {
				$user = $this->findOneBy(['email' => $username]);

				if (!$user)
					throw new UsernameNotFoundException(sprintf('Email "%s" does not exist.', $username));

				return $user;
			}

			$user = $this->findOneBy(['username' => $username]);

			if (!$user)
				throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));

			return $user;
		}

		/**
		 * Refreshes the user for the account interface.
		 *
		 * It is up to the implementation to decide if the user data should be
		 * totally reloaded (e.g. from the database), or if the IdentityUserInterface
		 * object can just be merged into some internal array of users / identity
		 * map.
		 *
		 * @param IdentityUserInterface $user
		 * @return IdentityUserInterface
		 * @throws UnsupportedUserException if the account is not supported
		 */
		public function refreshUser (IdentityUserInterface $user) : IdentityUserInterface
		{
			if (!$this->supportsClass(get_class($user)))
				throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));

			$item = $this->getUserItem($user->getId());

			return $this->hydrateUser($user, $item, true);
		}

		/**
		 * @inheritdoc
		 */
		public function supportsClass ($class)
		{
			$class_implements = class_implements($class);

			return ($class_implements && in_array(IdentityUserInterface::class, $class_implements));
		}

		public function createNewUser () : IdentityUserInterface
		{
			$userClass = $this->getUserClass();

			$user = new $userClass();

			return $user;
		}

		/**
		 *
		 */
		public function getUser (string $id) : ?IdentityUserInterface
		{
			$userClass = $this->getUserClass();
			$userItem = $this->getUserItem($id);

			$user = new $userClass();

			return $this->hydrateUser($user, $userItem, true);
		}

		/**
		 * Get a user item from the catalog by the user ID.
		 *
		 * @param string $id
		 * @return CatalogItemInterface|null The user item, or null if there is no user with that ID.
		 */
		public function getUserItem (string $id) : ?CatalogItemInterface
		{
			$spec = $this->specRegistry->getSpec($this->userSpecAlias, $this->userSpecCategory);

			$inventoryRequest = (new InventoryRequest())
				->addIdentifier($id);

			$catalog = $this->catalogFactory->createCatalog($spec, $inventoryRequest);

			return $catalog->getItem();
		}

		/**
		 * Get a single User instance that matches the given criteria. If more than one User matches, the first result is returned.
		 *
		 * @param array $criteria
		 * @return User|null
		 */
		public function findOneBy (array $criteria) : ?IdentityUserInterface
		{
			$users = $this->findBy($criteria);

			if (empty($users))
				return null;

			return reset($users);
		}

		/**
		 * Find User instances that match the given criteria.
		 *
		 * @param array $criteria
		 * @param array $options An array of the following options (all optional):<pre>
		 *      limit (int|array) The maximum number of results to return, or an array of (offset, limit).
		 *      order_by (string|array) The name of the column to order by, or an array of column name and direction, ex. array(date_created, DESC)
		 * </pre>
		 * @return User[] An array of matching User instances, or an empty array if no matching users were found.
		 */
		public function findBy (array $criteria = [], array $options = [])
		{
			// Check the identity map first.
			if (array_key_exists($this->getUserColumns('id'), $criteria)
				&& array_key_exists($criteria[$this->getUserColumns('id')], $this->identityMap)) {
				return [$this->identityMap[$criteria[$this->getUserColumns('id')]]];
			}

			list ($common_sql, $params) = $this->createCommonFindSql($criteria);

			$sql = 'SELECT * ' . $common_sql;

			if (array_key_exists('order_by', $options)) {
				list ($order_by, $order_dir) = is_array($options['order_by']) ? $options['order_by'] : array($options['order_by']);
				$sql .= 'ORDER BY ' . $this->conn->quoteIdentifier($order_by) . ' ' . ($order_dir == 'DESC' ? 'DESC' : 'ASC') . ' ';
			}
			if (array_key_exists('limit', $options)) {
				list ($offset, $limit) = is_array($options['limit']) ? $options['limit'] : array(0, $options['limit']);
				$sql .=   ' LIMIT ' . (int) $limit . ' ' .' OFFSET ' . (int) $offset ;
			}

			$data = $this->conn->fetchAll($sql, $params);

			$users = [];
			foreach ($data as $userData) {
				if (array_key_exists($userData[$this->getUserColumns('id')], $this->identityMap)) {
					$user = $this->identityMap[$userData[$this->getUserColumns('id')]];
				} else {
					$id = (string) $userData[$this->getUserColumns('id')];
					$userClass = $this->getUserClass();
					$userItem = $this->getUserItem($id);

					$user = new $userClass();

					$this->hydrateUser($user, $userItem, true);
					$this->identityMap[$user->getId()] = $user;
				}

				$users[] = $user;
			}

			return $users;
		}

		/**
		 * Get SQL query fragment common to both find and count querires.
		 *
		 * @param array $criteria
		 * @return array An array of SQL and query parameters, in the form array($sql, $params)
		 */
		protected function createCommonFindSql (array $criteria = [])
		{
			$params = [];

			$sql = 'FROM ' . $this->conn->quoteIdentifier($this->userTableName). ' ';

			$first_crit = true;
			foreach ($criteria as $key => $val) {
				$key = $this->getUserColumns($key);
				$column = $this->conn->quoteIdentifier($key,\PDO::PARAM_STR);
				$sql .= ($first_crit ? 'WHERE' : 'AND') . ' ' . $column . ' = :' . $key . ' ';
				$params[$key] = $val;
				$first_crit = false;
			}

			return [$sql, $params];
		}

		/**
		 * Insert a new User instance into the database.
		 *
		 * @param IdentityUserInterface $user
		 */
		public function insert (IdentityUserInterface $user)
		{
			$this->dispatcher->dispatch(IdentityEvents::BEFORE_INSERT, new UserEvent($user));

			$sql = 'INSERT INTO ' . $this->conn->quoteIdentifier($this->userTableName) . '
				('.$this->getUserColumns('email').', '.$this->getUserColumns('password').', '.$this->getUserColumns('salt').', '.$this->getUserColumns('name').
', '.$this->getUserColumns('roles').', '.$this->getUserColumns('date_created').', '.$this->getUserColumns('username').', '.$this->getUserColumns('enabled').', )
VALUES (:email, :password, :salt, :name, :roles, :timeCreated, :username, :enabled)';

			$params = array(
				'email' => $user->getEmail(),
				'password' => $user->getPassword(),
				'salt' => $user->getSalt(),
				'name' => $user->getName(),
				'roles' => implode(',', $user->getRoles()),
				'timeCreated' => $user->getTimeCreated(),
				'username' => $user->getRealUsername(),
				'enabled' => $user->isEnabled(),
			);

			$this->conn->executeUpdate($sql, $params);

			$user->setId($this->conn->lastInsertId());

			$this->identityMap[$user->getId()] = $user;

			$this->dispatcher->dispatch(IdentityEvents::AFTER_INSERT, new UserEvent($user));
		}

		/**
		 * Update data in the database for an existing user.
		 *
		 * @param IdentityUserInterface $user
		 */
		public function update (IdentityUserInterface $user)
		{
			$this->dispatcher->dispatch(IdentityEvents::BEFORE_UPDATE, new UserEvent($user));

			$sql = 'UPDATE ' . $this->conn->quoteIdentifier($this->userTableName). '
				SET '.$this->getUserColumns('email').' = :email
				, '.$this->getUserColumns('password').' = :password
				, '.$this->getUserColumns('salt').' = :salt
				, '.$this->getUserColumns('name').' = :name
				, '.$this->getUserColumns('roles').' = :roles
				, '.$this->getUserColumns('date_created').' = :timeCreated
				, '.$this->getUserColumns('username').' = :username
				, '.$this->getUserColumns('enabled').' = :enabled
				WHERE '.$this->getUserColumns('id').' = :id';

			$params = array(
				'email' => $user->getEmail(),
				'password' => $user->getPassword(),
				'salt' => $user->getSalt(),
				'name' => $user->getName(),
				'roles' => implode(',', $user->getRoles()),
				'timeCreated' => $user->getTimeCreated(),
				'username' => $user->getRealUsername(),
				'enabled' => $user->isEnabled(),
				'id' => $user->getId(),
			);

			$this->conn->executeUpdate($sql, $params);

			$this->dispatcher->dispatch(IdentityEvents::AFTER_UPDATE, new UserEvent($user));
		}

		/**
		 * Delete a User from the database.
		 *
		 * @param IdentityUserInterface $user
		 */
		public function delete (IdentityUserInterface $user)
		{
			$this->dispatcher->dispatch(IdentityEvents::BEFORE_DELETE, new UserEvent($user));

			$this->clearIdentityMap($user);

			$this->conn->executeUpdate('DELETE FROM ' . $this->conn->quoteIdentifier($this->userTableName). ' WHERE '.$this->getUserColumns('id').' = ?', array($user->getId()));

			$this->dispatcher->dispatch(IdentityEvents::AFTER_DELETE, new UserEvent($user));
		}

		/**
		 * Clear User instances from the identity map, so that they can be read again from the database.
		 *
		 * Call with no arguments to clear the entire identity map.
		 * Pass a single user to remove just that user from the identity map.
		 *
		 * @param mixed $user Either a User instance, an integer user ID, or null.
		 */
		public function clearIdentityMap ($user = null)
		{
			if ($user === null) {
				$this->identityMap = array();
			} else if ($user instanceof User && array_key_exists($user->getId(), $this->identityMap)) {
				unset($this->identityMap[$user->getId()]);
			} else if (is_numeric($user) && array_key_exists($user, $this->identityMap)) {
				unset($this->identityMap[$user]);
			}
		}

		/**
		 * @param string $userClass The class to use for the user model. Must extend SimpleUser\User.
		 */
		public function setUserClass ($userClass)
		{
			$this->userClass = $userClass;
		}

		/**
		 * @return string
		 */
		public function getUserClass ()
		{
			return $this->userClass;
		}

		public function setUserTableName ($userTableName)
		{
			$this->userTableName = $userTableName;
		}

		public function getUserTableName ()
		{
			return $this->userTableName;
		}

		public function setUserColumns (array $userColumns)
		{
			$conn = $this->conn;
			//Escape the column names

			$escapedUserColumns = array_map(function($column) use ($conn){
				return $column;
				//return $conn->quoteIdentifier($column,\PDO::PARAM_STR);
			}, $userColumns);

			//Merge the existing column names
			$this->userColumns = array_merge($this->userColumns, $escapedUserColumns);
		}

		public function getUserColumns ($column = null, bool $convert = false)
		{
			if (!$column)
				return $this->userColumns;

			$col = $this->userColumns[$column];

			if ($convert) {
				if ('name' == $column)
					return 'name';

				return lcfirst(str_replace('_', '', ucwords($col, '_')));
			}
			elseif('name' == $column) {
					return 'firstname';
			}

			return $col;
		}

		/**
		 * Reconstitute a User object from stored data.
		 *
		 * @param array $data
		 * @return User
		 * @throws \RuntimeException if database schema is out of date.
		 */
		protected function hydrateUser (IdentityUserInterface $user, $data, ?bool $convert = false)
		{
			if (empty($data))
				die('what');

			$user->setId((string) $data[$this->getUserColumns('id', $convert)]);
			$user->setPassword($data[$this->getUserColumns('password', $convert)]);
			$user->setSalt($data[$this->getUserColumns('salt', $convert)]);

			$user->setName($data[$this->getUserColumns('name', $convert)]);

			if (is_array($data[$this->getUserColumns('roles', $convert)])) {
				$user->setRoles($data[$this->getUserColumns('roles', $convert)]);
			}
			else {
				if ($roles = array_map('trim', explode(',', $data[$this->getUserColumns('roles', $convert)])))
					$user->setRoles($roles);
			}

			$timeCreated = $data[$this->getUserColumns('date_created', $convert)];

			if (!($timeCreated instanceof DateTimeInterface))
				$timeCreated = new DateTime((string) $timeCreated);

			$user->setTimeCreated($timeCreated);
			$user->setEmail($data[$this->getUserColumns('email', $convert)]);
			$user->setUsername($data[$this->getUserColumns('username', $convert)]);
			$user->setEnabled($data[$this->getUserColumns('enabled', $convert)] ? true : false);

			return $user;
		}

	}
}

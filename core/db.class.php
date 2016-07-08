<?php





class DB {

	const DRIVER_PGSQL = 1;


	private $_driver = null;
	private $_host = null;
	private $_port = null;
	private $_user = null;
	private $_password = null;
	private $_schema = null;
	private $_prefix = null;
	private $_charset = null;

	private static $_drivers_defaults = [
		self::DRIVER_PGSQL => [
			'host' => 'localhost',
			'port' => '5432',
			'user' => '',
			'password' => '',
			'schema' => '',
			'prefix' => '',
			'charset' => 'utf8'
		]
	];

	/**
	 * @var PDO|null
	 */
	private $_pdo = null;

	/**
	 * @var Logger|null
	 */
	private $_logger = null;



	public function __construct ($driver, $host, $port, $user, $password, $schema, $prefix, $charset) {
		if (!in_array($driver, [ self::DRIVER_PGSQL ])) {
			$this->_logger && $this->_logger->error('Unknown database driver', new Exception('Unknown database driver'));
			return null;
		}

		$this->_driver = $driver;
		$this->_host = $host !== null ? $host : self::$_drivers_defaults[$driver]['host'];
		$this->_port = $port !== null ? $port : self::$_drivers_defaults[$driver]['port'];
		$this->_user = $user !== null ? $user : self::$_drivers_defaults[$driver]['user'];
		$this->_password = $password !== null ? $password : self::$_drivers_defaults[$driver]['password'];
		$this->_schema = $schema !== null ? $schema : self::$_drivers_defaults[$driver]['schema'];
		$this->_prefix = $prefix !== null ? $prefix : self::$_drivers_defaults[$driver]['prefix'];
		$this->_charset = $charset !== null ? $charset : self::$_drivers_defaults[$driver]['charset'];
	}



	public function connect() {
		if ($this->_driver === null) {
			$this->_logger && $this->_logger->error('[DB::connect] Invalid configuration data');
			return false;
		}

		if ($this->_pdo) {
			return true;
		}

		$dsn = '';

		switch ($this->_driver) {
			case self::DRIVER_PGSQL:
				$dsn = 'pgsql:host='.$this->_host.
					';port='.$this->_port.
					';dbname='.$this->_schema.
					';user='.$this->_user.
					';password='.$this->_password;
				break;
		}

		try {
			$this->_pdo = new PDO($dsn);
		} catch (PDOException $e) {
			$this->_logger && $this->_logger->error('Fail connecting to database', $e);
			return false;
		}

		return true;
	}



	public function setLogger(Logger $logger) {
		$this->_logger = $logger;
	}


	public function dbReady() {
		return !!$this->_pdo;
	}



	public function getTablesList() {
		if (!$this->dbReady()) {
			return false;
		}

		$stmt = $this->_pdo->query('
			SELECT table_name
			FROM information_schema.tables
			WHERE table_type = \'BASE TABLE\' AND table_catalog = '.$this->_pdo->quote($this->_schema));

		if (!$stmt) {
			return false;
		}

		$tables = [];

		$preff_len = strlen($this->_prefix);


		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $table) {
			$table = $table['table_name'];
			if (strpos($table, $this->_prefix) === 0) {
				$table = substr($table, $preff_len);
			}
			$tables[] = $table;
		}

		return $tables;
	}


	public function getMany($table, array $fields = [], array $where = [], array $sort = [], $limit = 0, $offset = 0) {

		if (!$fields) {
			$fields[] = '*';
		}

		$where_sql = [];
		// TODO realization of where condition; not need for this task

		$sort_sql = [];

		foreach ($sort as $field => $dir) {
			$sort_sql[] = $field.' '.$dir;
		}

		$stmt = $this->_pdo->query('SELECT '.implode(', ', $fields).' FROM '.$this->_prefix.$table.
			($sort_sql ? ' ORDER BY '.implode(', ', $sort_sql) : '').
			($limit ? ' LIMIT '.$limit.' OFFSET '.$offset : ''));

		if (!$stmt || (int)$this->_pdo->errorCode()) {
			$this->_logger->error('Fail selecting into '.$table.': '.var_export($this->_pdo->errorInfo()));
			return false;
		}

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getCount($table, array $where = []) {

		$where_sql = [];
		// TODO realization of where condition; not need for this task

		$stmt = $this->_pdo->query('SELECT COUNT(*) AS num FROM '.$this->_prefix.$table);

		if (!$stmt || (int)$this->_pdo->errorCode()) {
			$this->_logger->error('Fail counting '.$table.': '.var_export($this->_pdo->errorInfo()));
			return false;
		}

		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data ? $data['num'] : 0;
	}



	public function write($table, array $data) {

		$fields_sql = [];
		$data_sql = [];

		foreach ($data as $field => $value) {
			$fields_sql[] = $field;
			$data_sql[] = $this->_pdo->quote($value);
		}

		$this->_pdo->query('INSERT INTO '.$this->_prefix.$table.' ('.implode(', ', $fields_sql).') VALUES ('.implode(',', $data_sql).')');

		$code = !((int)$this->_pdo->errorCode());

		if (!$code) {
			$this->_logger->error('Fail writing new row into '.$table.': '.var_export($this->_pdo->errorInfo()));
		}

		return $code;
	}



	public function createTable($name, array $fields, array $indexes = []) {
		$fields_sql = [];

		foreach ($fields as $field_name => $field_conditions) {
			$sql = $field_name.' ';
			switch ($field_conditions['type']) {
				case 'int':
					if (empty($field_conditions['increment'])) {
						$sql .= 'INT'.
							(!empty($field_conditions['unsigned']) ? ' UNSIGNED' : '');
					} else {
						$sql .= 'serial';
					}
					break;

				case 'text':
					if ($field_conditions['length'] > 255) {
						$sql .= 'TEXT';
					} else {
						$sql .= 'VARCHAR('.$field_conditions['length'].')';
					}
					break;
			}
			if (empty($field_conditions['null'])) {
				$sql .= ' NOT NULL';
			}
			if (array_key_exists('default', $field_conditions)) {
				$sql .= ' DEFAULT '.
					($field_conditions['default'] === null
						? 'NULL'
						: $this->_pdo->quote($field_conditions['default']));
			}
			$fields_sql[] = $sql;
		}

		foreach ($indexes as $field_name => $index_type) {
			switch ($index_type) {
				case 'primary':
					$fields_sql[] = 'PRIMARY KEY ('.$field_name.')';
					break;
			}
		}

		$this->_pdo->query('CREATE TABLE '.$this->_prefix.$name.' ('.implode(', ', $fields_sql).')');

		$code = !((int)$this->_pdo->errorCode());

		if (!$code) {
			$this->_logger->error('Fail creating table '.$name.': '.var_export($this->_pdo->errorInfo()));
		}

		return $code;
	}
}
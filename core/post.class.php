<?php






class Post {

	const TABLE_NAME = 'posts';


	/**
	 * @var DB|null
	 */
	private static $_db = null;


	private $_fields = [
		'id' => [ 'type' => 'int', 'value' => null, 'error' => false ],
		'user_name' => [ 'type' => 'text', 'required' => true, 'value' => null, 'error' => true ],
		'email' => [ 'type' => 'email', 'required' => true, 'value' => null, 'error' => true ],
		'homepage' => [ 'type' => 'text', 'required' => true, 'value' => null, 'error' => true ],
		'text' => [ 'type' => 'text', 'required' => true, 'value' => null, 'error' => true ],
		'ip' => [ 'type' => 'text', 'required' => true, 'value' => null, 'error' => true ],
		'user_agent' => [ 'type' => 'text', 'required' => true, 'value' => null, 'error' => true ],
		'date_created' => [ 'type' => 'int', 'required' => true, 'value' => null, 'error' => true ]
	];




	public function __construct($id = null) {
		if ($id !== null) {
			$this->_fields['id']['value'] = $id;
			$this->load();
		}
	}



	public function validate() {
		$errors = [];

		foreach ($this->_fields as $field_name => $field_data) {
			if ($field_data['error']) {
				$errors[] = $field_name;
			}
		}

		return $errors;
	}



	public function save() {
		$data = [];

		foreach ($this->_fields as $field_name => $field_data) {
			$data[$field_name] = is_numeric($field_data['value']) ? $field_data['value'] : htmlentities($field_data['value']);
		}

		unset($data['id']);

		return self::$_db->write(self::TABLE_NAME, $data);
	}


	/**
	 * TODO refactor to returning array of objects, not raw data
	 * @param array $where
	 * @param array $sort
	 * @param int   $limit
	 * @param int   $offset
	 * @return array
	 */
	public static function getMany(array $where = [], array $sort = [], $limit = 25, $offset = 0) {
		return self::$_db->getMany(self::TABLE_NAME, [
			'id', 'user_name', 'email', 'homepage', 'text', 'ip', 'user_agent', 'date_created'
		], $where, $sort, $limit, $offset);
	}



	public static function getCount(array $where = []) {
		return self::$_db->getCount(self::TABLE_NAME, $where);
	}



	public function setData(array $data) {
		foreach ($data as $field => $value) {
			if (isset($this->_fields[$field])) {
				switch ($this->_fields[$field]['type']) {
					case 'int':
						$this->_fields[$field]['value'] = is_numeric($value) ? (int) $value : null;
						if ($this->_fields[$field]['required'] && $this->_fields[$field]['value'] === null) {
							$this->_fields[$field]['error'] = true;
						} else {
							$this->_fields[$field]['error'] = false;
						}
						break;

					case 'text':
						$this->_fields[$field]['value'] = trim((string) $value);
						if ($this->_fields[$field]['required'] && $this->_fields[$field]['value'] === '') {
							$this->_fields[$field]['error'] = true;
						} else {
							$this->_fields[$field]['error'] = false;
						}
						break;

					case 'email':
						$this->_fields[$field]['value'] = trim((string) $value);
						if (
							(
								$this->_fields[$field]['value'] !== '' &&
								!filter_var($this->_fields[$field]['value'], FILTER_VALIDATE_EMAIL)
							) ||
							(
								$this->_fields[$field]['value'] === '' &&
								$this->_fields[$field]['required']
							)
						) {
							$this->_fields[$field]['error'] = true;
						} else {
							$this->_fields[$field]['error'] = false;
						}
					break;
				}
			}
		}
	}



	public function load() {

	}




	public static function addDB(DB $db) {
		self::$_db = $db;
	}



	public static function checkScheme() {
		$tables = self::$_db->getTablesList();

		if (in_array(self::TABLE_NAME, $tables)) {
			return true;
		}

		return self::$_db->createTable(self::TABLE_NAME, [
			'id' => [ 'type' => 'int', 'length' => 10, 'null' => false, 'unsigned' => true, 'increment' => true ],
			'user_name' => [ 'type' => 'text', 'length' => 255, 'null' => false ],
			'email' => [ 'type' => 'text', 'length' => 255, 'null' => false ],
			'homepage' => [ 'type' => 'text', 'length' => 255, 'null' => false ],
			'text' => [ 'type' => 'text', 'length' => 1024 * 64, 'null' => false ],
			'ip' => [ 'type' => 'text', 'length' => 20, 'null' => false ],
			'user_agent' => [ 'type' => 'text', 'length' => 255, 'null' => false ],
			'date_created' => [ 'type' => 'int', 'length' => 10, 'null' => false, 'default' => 0 ]
		], [
			'id' => 'primary'
		]);
	}
}
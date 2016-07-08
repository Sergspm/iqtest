<?php







class Session {

	/**
	 * @var Session|null
	 */
	private static $_instance = null;



	public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new static();
		}
		return self::$_instance;
	}


	private function __construct() {
		if (!session_id()) {
			session_start();
		}
	}


	public function get($name) {
		return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
	}



	public function set($name, $value) {
		$_SESSION[$name] = $value;
	}


	public function remove($name) {
		unset($_SESSION[$name]);
	}
}
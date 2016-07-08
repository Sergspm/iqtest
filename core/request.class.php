<?php




class Request {

	/**
	 * @var Request|null
	 */
	private static $_instance = null;


	public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new static();
		}
		return self::$_instance;
	}


	private function __construct() {

	}



	public function hasPost() {
		return !empty($_POST);
	}


	public function getPost() {
		return $_POST;
	}


	public function getPostVal($name) {
		return isset($_POST[$name]) ? $_POST[$name] : null;
	}


	public function getQueryParam($name, $default = null) {
		return isset($_GET[$name]) ? $_GET[$name] : $default;
	}


	public function getQueryParams() {
		return $_GET;
	}


	public function hasQueryParam($name) {
		return array_key_exists($name, $_GET);
	}


	public function getIp() {
		return $_SERVER['REMOTE_ADDR'];
	}


	public function getUserAgent() {
		return $_SERVER['HTTP_USER_AGENT'];
	}
}
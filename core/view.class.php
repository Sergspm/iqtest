<?php




class View {

	/**
	 * @var Logger|null
	 */
	private static $_logger = null;

	private static $_views_dir = null;

	private static $_vars = [];


	private static $_layout = null;
	private static $_view = null;

	private static $_instance = null;


	private function __constructor() {

	}


	/**
	 * @return View
	 */
	public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new static();
		}
		return self::$_instance;
	}


	public function registerVars($var, $value = null) {
		if (is_array($var)) {
			foreach ($var as $name => $value) {
				self::$_vars[$name] = $value;
			}
		} else {
			self::$_vars[$var] = $value;
		}
	}


	public function getVar($name, $default = null) {
		return isset(self::$_vars[$name]) ? self::$_vars[$name] : $default;
	}


	public function setViewsDir ($dir) {
		if (is_dir($dir)) {
			self::$_views_dir = $dir;
			return true;
		}

		self::$_logger && self::$_logger->error('Wrong views directory');

		return false;
	}


	public function setLogger(Logger $logger) {
		self::$_logger = $logger;
	}


	public function setLayout($layout) {
		if (is_file(self::$_views_dir.$layout.'.layout.php')) {
			self::$_layout = $layout;
			return true;
		}
		return false;
	}



	public static function render ($view) {
		if (is_file(self::$_views_dir.$view.'.view.php')) {
			self::$_view = $view;
			include self::$_views_dir.self::$_layout.'.layout.php';
			return true;
		}
		return false;
	}


	public static function renderView() {
		if (self::$_view) {
			include self::$_views_dir.self::$_view.'.view.php';
		}
		return '';
	}


	public function getVars() {
		return self::$_vars;
	}


	public function hasVar($name) {
		return array_key_exists($name, self::$_vars);
	}
}
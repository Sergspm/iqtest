<?php






class App {

	/**
	 * @var DB|null
	 */
	private $_db = null;
	/**
	 * @var Logger|null
	 */
	private $_logger = null;
	/**
	 * @var View|null
	 */
	private $_view = null;

	/**
	 * @var Request|null
	 */
	private $_request = null;

	/**
	 * @var Session|null
	 */
	private $_session = null;

	/**
	 * @var Captcha|null
	 */
	private $_captcha = null;



	private $_controller = null;


	public function __construct () {

	}


	public function run () {

		// Prepare base classes
		$this->_logger = new Log();
		$this->_view = View::getInstance();
		$this->_request = Request::getInstance();
		$this->_session = Session::getInstance();
		$this->_captcha = Captcha::getInstance();

		$this->_captcha->setSession($this->_session);

		if ($this->_request->hasQueryParam('captcha')) {
			$this->_captcha->renderImage();
			exit;
		}


		$this->_view->setLogger($this->_logger);
		$this->_view->setViewsDir(DIR_VIEWS);
		$this->_view->setLayout('main');


		// Connect to database
		$this->_db = new DB(
			DB::DRIVER_PGSQL,
			CONF_DB_HOST,
			CONF_DB_PORT,
			CONF_DB_USER,
			CONF_DB_PASSWORD,
			CONF_DB_SCHEMA,
			CONF_DB_PREFIX,
			CONF_DB_CHARSET
		);
		$this->_db->setLogger($this->_logger);
		$this->_db->connect();

		// If connection fail - show error
		if (!$this->_db->dbReady()) {
			$this->_view->registerVars([
				'head' => 'Серверная ошибка',
				'message' => 'Ошибка подключения к базе данных',
				'meta.title' => 'Серверная ошибка'
			]);
			$this->_view->render('server_error');
			return;
		}

		// Pepare post model
		Post::addDb($this->_db);

		// Check post's schema; if not existing and fail creating - show error;
		// Checking only if setted flag
		if (FORCE_CHECKING_SCHEMAS && !Post::checkScheme()) {
			$this->_view->registerVars([
				'head' => 'Серверная ошибка',
				'message' => 'Ошибка создания таблицы постов',
				'meta.title' => 'Серверная ошибка'
			]);
			$this->_view->render('server_error');
			return;
		}


		// Init controller
		$this->_controller = new BaseController();
		$this->_controller->setLogger($this->_logger);
		$this->_controller->setView($this->_view);
		$this->_controller->setRequest($this->_request);
		$this->_controller->setCaptcha($this->_captcha);
		$this->_controller->setSession($this->_session);

		// Try to handle request
		$this->_controller->handleRequestAction();

		// Run index controller
		$this->_controller->indexAction();
	}
}
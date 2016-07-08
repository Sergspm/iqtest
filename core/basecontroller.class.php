<?php






class BaseController {

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
	 * @var Captcha|null
	 */
	private $_captcha = null;

	/**
	 * @var Session|null
	 */
	private $_session = null;


	public function setLogger(Logger $logger) {
		$this->_logger = $logger;
	}



	public function setView(View $view) {
		$this->_view = $view;
	}



	public function setRequest(Request $request) {
		$this->_request = $request;
	}


	public function setCaptcha(Captcha $captcha) {
		$this->_captcha = $captcha;
	}


	public function setSession(Session $session) {
		$this->_session = $session;
	}



	public function handleRequestAction() {
		if (!$this->_request->hasPost()) {
			$this->_session->set('csrf_protected', md5(rand()));
			$this->_view->registerVars('csrf_protected', $this->_session->get('csrf_protected'));
			return false;
		}

		$data = array_merge([
			'date_created' => time(),
			'ip' => $this->_request->getIp(),
			'user_agent' => $this->_request->getUserAgent(),
			'captcha' => ''
		], $this->_request->getPost());

		$post = new Post();
		$post->setData($data);

		$has_errors = false;

		if (!$this->_captcha->validate($data['captcha'])) {
			$has_errors = true;
			$this->_view->registerVars('field.error.captcha', 'Неправильный код');
		}

		foreach ($post->validate() as $field) {
			$has_errors = true;
			$this->_view->registerVars('field.error.'.$field, 'Ошибка заполнения');
		}

		if ($this->_session->get('csrf_protected') !== $this->_request->getPostVal('csrf')) {
			$has_errors = true;
		}

		$this->_session->set('csrf_protected', md5(rand()));
		$this->_view->registerVars('csrf_protected', $this->_session->get('csrf_protected'));

		if (!$has_errors) {
			$post->save();
		} else {
			foreach ($data as $name => $value) {
				$this->_view->registerVars('field.value.'.$name, $value);
			}
		}

		$this->_view->registerVars('submit.status', !$has_errors);

		return true;
	}



	public function indexAction() {
		$total = Post::getCount();

		$orders = [ 'user_name', 'email', 'date_created' ];
		$dirs = [ 'ASC', 'DESC' ];

		$order = $this->_request->getQueryParam('sort', 'date_created');
		if (!in_array($order, $orders)) {
			$order = 'date_created';
		}
		$dir = $this->_request->getQueryParam('dir', 'DESC');
		if (!in_array($dir, $dirs)) {
			$dir = 'DESC';
		}

		$current_page = max($this->_request->getQueryParam('page', 1), 1);
		$per_page = POSTS_PER_PAGE;
		$total_pages = ceil($total / $per_page);

		if ($current_page > $total_pages) {
			$current_page = $total_pages;
		}



		$this->_view->registerVars('paging.current', (int) $current_page);
		$this->_view->registerVars('paging.total', (int) $total_pages);

		$this->_view->registerVars('order.field', $order);
		$this->_view->registerVars('order.dir', $dir);

		$this->_view->registerVars('query_params', $this->_request->getQueryParams());


		$data = Post::getMany([], [ $order => $dir ], $per_page, $per_page * $current_page - $per_page);

		$this->_view->registerVars('posts_data', $data ? $data : []);

		$this->_view->render('main');
	}
}

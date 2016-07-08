<?php








class Captcha {

	/**
	 * @var Captcha|null
	 */
	private static $_instance = null;

	/**
	 * @var Session|null
	 */
	private static $_session = null;


	const SESSION_KEY = 'captcha';


	private static $_current_code = null;





	public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new static();
		}
		return self::$_instance;
	}


	private function __construct() {

	}



	public static function setSession(Session $session) {
		self::$_session = $session;
	}



	public function validate($code) {
		if (self::$_current_code === null) {
			self::$_current_code = self::$_session->get(self::SESSION_KEY);
		}

		return self::$_current_code === $code;
	}




	public function renderImage() {
		$width = 100;
		$height = 60;
		$font_size = 16;
		$let_amount = 4;
		$fon_let_amount = 30;

		$font = "assets/fonts/Candara.ttf";

		$letters = ["a","b","c","d","e","f","g"];
		$colors = ["90","110","130","150","170","190","210"];


		$src = imagecreatetruecolor($width, $height);
		$fon = imagecolorallocate($src, 255, 255, 255);

		imagefill($src, 0, 0, $fon);

		for ($i = 0; $i < $fon_let_amount; $i++) {
			$color = imagecolorallocatealpha($src, rand(0, 255), rand(0, 255), rand(0, 255), 100);
			$letter = $letters[rand(0, sizeof($letters) - 1)];
			$size = rand($font_size - 2, $font_size + 2);
			imagettftext(
				$src,
				$size,
				rand(0,45),
				rand($width *0.1, $width - $width * 0.1),
				rand($height * 0.2, $height),
				$color,
				$font,
				$letter
			);
		}

		$cod = [];

		for ($i = 0; $i < $let_amount; $i++) {
			$color = imagecolorallocatealpha(
				$src,
				$colors[rand(0, sizeof($colors) - 1)],
				$colors[rand(0, sizeof($colors) - 1)],
				$colors[rand(0, sizeof($colors) - 1)],
				rand(20, 40)
			);
			$letter = $letters[rand(0, sizeof($letters) - 1)];
			$size = rand($font_size * 2 - 2, $font_size * 2 + 2);
			$x = ($i + 1) * $font_size + rand(1, 5);
			$y = (($height * 2) / 3) + rand(0, 5);
			$cod[] = $letter;

			imagettftext($src, $size, rand(0, 15), $x, $y, $color, $font, $letter);
		}

		self::$_current_code = implode("", $cod);

		self::$_session->set(self::SESSION_KEY, self::$_current_code);

		header ("Content-type: image/gif");
		imagegif($src);
	}
}


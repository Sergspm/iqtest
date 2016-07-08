<?php





class Log implements Logger {
	public function info ($message) {

	}


	public function error ($message, Exception $e = null) {
		var_dump($message);
	}
}
<?php







interface Logger {
	public function info($message);
	public function error($message, Exception $e = null);
}
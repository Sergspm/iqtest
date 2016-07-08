<?php
/**
 * Начало разработки: 08.08.2015 1:38
 * 11:48
 */


error_reporting(E_ALL);
ini_set('display_errors', 1);


define('DIR_ROOT', __DIR__.DIRECTORY_SEPARATOR);
define('DIR_CORE', DIR_ROOT.'core'.DIRECTORY_SEPARATOR);
define('DIR_VIEWS', DIR_ROOT.'views'.DIRECTORY_SEPARATOR);

include DIR_CORE.'config.php';


spl_autoload_register(function ($class) {
	$class_name = strtolower($class);

	$path = DIR_CORE.$class_name.'.class.php';
	if (is_file($path)) {
		include $path;
		return;
	}

	$path = DIR_CORE.$class_name.'.interface.php';
	if (is_file($path)) {
		include $path;
		return;
	}
});




$app = new App();

$app->run();


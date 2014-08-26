<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
register_shutdown_function('shutdown_notify');

function shutdown_notify() {
	$error = error_get_last();
	if (!empty($error) && in_array($error['type'], array(E_ERROR, E_USER_ERROR))) {
		echo 'SHUTTING DOWN, ERROR: ';
		var_dump($error);
		var_dump($_SERVER);
	}

}

function a($arg) {
	b('beta');
}

function b($arg) {
	d('delta');
}

function d($arg) {
	var_dump(debug_backtrace());
}

a('alpha');



trigger_error('Custom notice', E_USER_NOTICE);
trigger_error('Custom warning', E_USER_WARNING);
trigger_error('Custom error', E_USER_ERROR);

echo 'will not execute';








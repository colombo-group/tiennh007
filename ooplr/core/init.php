<?php
	session_start();

	$GLOBALS['config'] = array(
		'mysql' => array(
			'host' => 'localhost', 
			'username' => 'root',
			'password' => '',
			'db' => 'oop'
			), 
		'remember' => array(
			'cookie_name' => 'hash',
			'cookie_expiry' => 604800
			),
		'session' => array(
			'session_name' => 'user',
			'token_name' => 'token'
			)
		);
	//require_once 'classes/Config.php';
	//require_once 'classes/Cookie.php';
	//require_once 'classes/DB.php';
	// tu dong require cac class trong thu muc classes
	spl_autoload_register(function($class){
		require_once 'classes/'.$class.'.php';
	});

	require_once 'functions/sanitize.php';

	if(Cookie::exists(Config::get('remember/cookie_name'))&&!Session::exists(Config::get('session/session_name'))) {
		$hash = Cookie::get(Config::get('remember/cookie_name'));
		$hashCheck = DB::getInstance()->get('userssessions', array('hash', '=', $hash));
		if ($hashCheck->count()>0) {
			$user = new User($hashCheck->first()->userID);
			$user->login();
		}
	}
?>
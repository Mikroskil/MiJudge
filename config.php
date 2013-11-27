<?php
	session_start();
	define( 'ROOT_DIR', dirname(__FILE__) );
	include_once(ROOT_DIR . '/function/function.php');
	define( "UPLOAD_DIR", "\\\\192.168.159.128\\submissions\\");
	if ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='192.168.11.16')
	{
		define( 'JS_DIR', '/MiJudge/js' );
		define( 'CSS_DIR', '/MiJudge/css' );
		define( 'IMG_DIR', '/MiJudge/images' );
		define( 'FLD', '/MiJudge/' );
	}
	else
	{
		define( 'JS_DIR', '/js' );
		define( 'CSS_DIR', '/css' );
		define( 'IMG_DIR', '/images' );
		define( 'FLD', '/');
	}
	/*
	define( 'DB_Server', 'localhost' );
	define( 'DB_Login', 'mijudge' );
	define( 'DB_Password', 'MikroskilOnlineJudge' );
	define( 'DB_Name', 'mikroskil_oj' );
	//*/
	/*
	define( 'DB_Server', '192.168.159.128' );
	define( 'DB_Login', 'root' );
	define( 'DB_Password', 'ong' );
	define( 'DB_Name', 'domjudge' );
	//*/
	//*
	define( 'DB_Server', '192.168.159.128' );
	define( 'DB_Login', 'domjudge' );
	define( 'DB_Password', 'qMksax88TlqJiMuF' );
	define( 'DB_Name', 'domjudge' );
	//*/
?>
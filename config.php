<?php
	define( 'ROOT_DIR', dirname(__FILE__) );
	if ($_SERVER['HTTP_HOST']=='localhost')
	{
		define( 'JS_DIR', $_SERVER['REQUEST_URI'] . '/js' );
		define( 'CSS_DIR', $_SERVER['REQUEST_URI'] . '/css' );
		define( 'IMG_DIR', $_SERVER['REQUEST_URI'] . '/images' );
	}
	else
	{
		define( 'JS_DIR', '/js' );
		define( 'CSS_DIR', '/css' );
		define( 'IMG_DIR', '/images' );
	}
	define( 'DB_Server', 'localhost' );
	define( 'DB_Login', 'mijudge' );
	define( 'DB_Password', 'MikroskilOnlineJudge' );
	define( 'DB_Name', 'mikroskil_oj' );
?>
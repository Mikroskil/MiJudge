<?php

function newConnection()
{
	try {
		$connect = new PDO('mysql:host='.DB_Server.';dbname='.DB_Name, DB_Login, DB_Password);
		$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $connect;
	} catch (PDOException $e) {
		die($e->getMessage());
	}
}

?>
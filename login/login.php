<?php
	try {
		$connect = new PDO('mysql:host='.DB_Server.';dbname='.DB_Name, DB_Login, DB_Password);
		$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		die($e->getMessage());
	}
	if (isset($_POST['username']) && isset($_POST['password'])) {
		$res = $connect->prepare('select * from team where login=:user and authtoken=:pass');
		$res->execute(array('user' => $_POST['username'], 'pass' => md5($_POST['username'].'#'.$_POST['password'])));
		foreach ($res as $row) {
			echo $row['login'] . " " . $row['authtoken'];
			$_SESSION['username'] = $row['login'];
			header('location:' . FLD);
		}
	}
?>
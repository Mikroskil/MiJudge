<?php
	$connect = newConnection();
	$res = $connect->prepare('select * from team where login=:user and authtoken=:pass');
	$res->execute(array('user' => $_POST['username'], 'pass' => md5($_POST['username'].'#'.$_POST['password'])));
	if ($res->rowCount() > 0) {
		foreach ($res as $row) {
			echo $row['login'] . " " . $row['authtoken'];
			$_SESSION['username'] = $row['login'];
			header('location:' . FLD);
		}
	} else {
		$_POST['login'] = false;
	}
?>
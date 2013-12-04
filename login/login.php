<?php
	if (isLogin())
		header('location:' . FLD);
	if (isset($_POST['username']) && isset($_POST['password'])) {
		$connect = newConnection();
		$res = newQuery($connect, 'select * from team where login=:user and authtoken=:pass', 
						array('user' => $_POST['username'], 'pass' => md5($_POST['username'].'#'.$_POST['password'])));
		if ($res->rowCount() > 0) {
			$row = $res->fetch();
			$_SESSION['username'] = $row['login'];
			if (isset($_SESSION['lastpage']))
				header('location:' . $_SESSION['lastpage']);
			else
				header('location:' . FLD);
		} else {
			$_POST['login'] = false;
		}
	}
?>
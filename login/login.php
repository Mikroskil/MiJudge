<?php
	if (isLogin())
		header('location:' . FLD);
	if (isset($_POST['username']) && isset($_POST['password'])) {
		$connect = newConnection();
		$res = newQuery($connect, 'select * from team where login=:user and authtoken=:pass', 
						array('user' => $_POST['username'], 'pass' => md5($_POST['username'].'#'.$_POST['password'])));
		$row = $res->fetch();
		if ($row != null) {
			$_SESSION['username'] = $row['login'];
			if ($row['categoryid'] == 5)
				$_SESSION['isAdmin'] = true;
			else
				$_SESSION['isAdmin'] = false;
			if (isset($_SESSION['lastpage']))
				header('location:' . $_SESSION['lastpage']);
			else
				header('location:' . FLD);
		} else {
			$_POST['login'] = false;
		}
	}
?>
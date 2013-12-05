<?php
	$connect = newConnection();
	$res = $connect->prepare("INSERT INTO `domjudge`.`team` (`login`, `name`, `categoryid`, `affilid`, `authtoken`, `enabled`, `members`, `room`, `comments`, `judging_last_started`, `teampage_first_visited`, `hostname`) VALUES (:user, :name, '2', NULL, :pass, '1', NULL, NULL, NULL, NULL, NULL, NULL)");
	$res->execute(array('name' => $_POST['firstname'].' '.$_POST['lastname'], 'user' => $_POST['username'], 'pass' => md5($_POST['username'].'#'.$_POST['password'])));
	if ($res->rowCount() > 0) {
		echo 'your account has been registered successfully';
	} else {
		echo 'register failed';
		$_POST['register'] = false;
	}
?>
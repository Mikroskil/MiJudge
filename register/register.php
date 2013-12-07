<?php
	if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirmpassword'])) {
		$connect = newConnection();
		$cek = newQuery($connect, 'select count(*) from team where login=:user', 
							array('user' => $_POST['username']));
		if($cek->fetchColumn() > 0){
			$_SESSION['result'] = "Username already in use. Please choose another username.";
			$_SESSION['success'] = false;
		} else {
			try
			{
				$connect->beginTransaction();
				$res = newQuery($connect, "INSERT INTO `domjudge`.`team` (`login`, `name`, `categoryid`, `affilid`, `authtoken`, `enabled`, `members`, `room`, `comments`, `judging_last_started`, `teampage_first_visited`, `hostname`) VALUES (:user, :name, '2', NULL, :pass, '1', NULL, NULL, NULL, NULL, NULL, NULL)",
								array('name' => $_POST['firstname'].' '.$_POST['lastname'], 'user' => $_POST['username'], 'pass' => md5($_POST['username'].'#'.$_POST['password'])));
				$_SESSION['result'] = "Your account has been registered successfully";
				$_SESSION['success'] = true;
				$connect->commit();
			} catch (PDOExecption $e) {
				$connect->rollback();
				$_SESSION['result'] = "Error Registering Your Account";
				$_SESSION['success'] = false;
			}
		}
	}
?>
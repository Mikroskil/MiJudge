<?php
	include_once('../config.php');
	include_once('register.php');
	openHTML("Register");
	addHeader();
	openPageRegion();
	include_once 'page.php';
	addSidebar();
	/*echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
	if (isset($_POST['firstname']) && strlen($_POST['firstname'])==0)
		echo "<font color='red'>Please input your first name.</font>";
	else if (isset($_POST['lastname']) && strlen($_POST['lastname'])==0)
		echo "<font color='red'>Please input your last name.</font>";
	else if (isset($_POST['username']) && strlen($_POST['username'])<4)
		echo "<font color='red'>Username must contain 4 characters or more.</font>";
	else if (isset($_POST['password']) && strlen($_POST['password'])<4)
		echo "<font color='red'>Password must contain 4 characters or more.</font>";
	else if (isset($_POST['confirmpassword']) && strcmp($_POST['confirmpassword'],$_POST['password'])!=0)
		echo "<font color='red'>Passwords does not match</font>";
	else if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirmpassword']))
		include_once('register.php');
		*/
	closePageRegion();
	addFooter();
	closeHTML();
?>
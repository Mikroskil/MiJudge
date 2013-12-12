<?php
	include_once('../config.php');
	unset($_SESSION['username']);
	unset($_SESSION['probid']);
	unset($_SESSION['isAdmin']);
	session_destroy();
	header('location:' . FLD);
?>

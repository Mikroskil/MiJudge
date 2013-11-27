<?php
	include_once('../config.php');
	unset($_SESSION['username']);
	unset($_SESSION['probid']);
	session_destroy();
	header('location:' . FLD);
?>

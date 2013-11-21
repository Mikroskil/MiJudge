<?php
	include_once('../config.php');
	unset($_SESSION['username']);
	session_destroy();
	header('location:' . FLD);
?>

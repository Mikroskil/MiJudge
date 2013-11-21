<?php
	include_once('../config.php');
	unset($_SESSION['username']);
	header('location:' . FLD);
?>

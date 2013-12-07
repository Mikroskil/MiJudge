<?php
	include_once('../config.php');
	include_once('login.php');
	openHTML("Login");
	addHeader();
	openPageRegion();
	include_once 'page.php';
	addSidebar();
	closePageRegion();
	addFooter();
	closeHTML();
?>
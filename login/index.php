<?php
	include_once('../config.php');
	include_once('login.php');
	openHTML("Mikroskil Online Judge | Login");
	addHeader();
	openPageRegion();
	include_once 'page.php';
	addSidebar();
	closePageRegion();
	addFooter();
	closeHTML();
?>
<?php
	include_once('../config.php');
	openHTML("Mikroskil Online Judge | Problem Set");
	addHeader();
	openPageRegion();
	include_once 'page.php';
	addSidebar();
	closePageRegion();
	addFooter();
	closeHTML();
?>
<?php
	include_once('../config.php');
	openHTML("Mikroskil Online Judge | Ranking");
	addHeader();
	openPageRegion();
	include_once 'page.php';
	addSidebar();
	closePageRegion();
	addFooter();
	closeHTML();
?>
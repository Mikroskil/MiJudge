<?php
	include_once('/config.php');
	openHTML("Mikroskil Online Judge");
	addHeader();
	openPageRegion();
	include_once 'page.php';
	addSidebar();
	closePageRegion();
	addFooter();
	closeHTML();
?>
<?php
	include_once('../config.php');
	$connect = newConnection();
	$res = newQuery($connect, "select contestname from contest where cid=:cid", array('cid' => $_GET['contest']));
	$cname = $res->fetchColumn();
	if ($cname == null)
		throwError("No Contest");
	openHTML("Contest " . $cname);
	addHeader();
	openPageRegion();
	include_once 'page.php';
	addSidebar();
	closePageRegion();
	addFooter();
	closeHTML();
?>
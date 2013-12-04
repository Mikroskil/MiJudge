<?php
	include_once('/config.php');
	$connect = newConnection();
	$res = $connect->prepare("select * from problem where probid=:id");
	$res->execute(array('id' => $_GET['problem']));
	$row = $res->fetch();

	switch ( $row['problemtext_type'] ) {
	case 'pdf':
		$mimetype = 'application/pdf';
		break;
	case 'html':
		$mimetype = 'text/html';
		break;
	case 'txt':
		$mimetype = 'text/plain';
		break;
	default:
		error("Problem '$$_GET[problem]' text has unknown type");
	}

	$filename = "prob-$_GET[problem].$row[problemtext_type]";

	header("Content-Type: $mimetype; name=\"$filename\"");
	header("Content-Disposition: inline; filename=\"$filename\"");
	header("Content-Length: " . strlen($row['problemtext']));

	echo $row['problemtext'];
?>
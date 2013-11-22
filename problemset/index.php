<?php
	include_once('../config.php');
?>
<!DOCTYPE html>
<html lang="en">
<html>
	<head>
	<title>Mikroskil Online Judge</title>
<?php addMetahttp(); ?>
	</head>
	<body class="metro">
<?php
	addHeader();
	openPageRegion();
	include_once 'page.php';
	addSidebar();
	closePageRegion();
	addFooter();
?>
	</body>
</html>
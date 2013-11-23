<?php
	include_once('../config.php');
	if (isset($_POST['username']) && isset($_POST['password']))
		include_once('login.php');
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
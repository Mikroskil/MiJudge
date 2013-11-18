<?php
	include_once('../config.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
	<title>Mikroskil Online Judge</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_DIR; ?>/global.css">
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_DIR; ?>/link.css">
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_DIR; ?>/menu.css">
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_DIR; ?>/footer.css">
	</head>
	<body>
		<div class="page">
			<?php include_once ROOT_DIR . '\header.php'; ?>
			<?php include_once ROOT_DIR . '\menu.php'; ?>
			<?php include_once ROOT_DIR . '\sidebar.php'; ?>
			<?php include_once 'page.php'; ?>
			<div class="clear"></div>
			<?php include_once ROOT_DIR . '\footer.php'; ?>
		</div>
	</body>
</html>
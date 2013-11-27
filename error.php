<?php
	include_once('/config.php');
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
	openPageRegion();
	echo "<div class=\"text-center\">" . $_SESSION['error'] . "</div>";
	closePageRegion();
?>
	</body>
</html>
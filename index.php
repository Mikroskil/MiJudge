<?php
	include_once('/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<html>
	<head>
	<title>Mikroskil Online Judge</title>
	<?php include_once ROOT_DIR . '\meta-http.php'; ?>
	</head>
	<body class='metro'>
		<div class='page'>
			<?php include_once ROOT_DIR . '\header.php'; ?>
			<?php include_once ROOT_DIR . '\menu.php'; ?>
			<div class='page-region'>
				<div class='grid'>
					<div class='row'>
						<?php include_once 'page.php'; ?>
						<?php include_once ROOT_DIR . '\sidebar.php'; ?>
					</div>
				</div>
			</div>
			<?php include_once ROOT_DIR . '\footer.php'; ?>
		</div>
	</body>
</html>
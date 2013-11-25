<?php
	include_once('/config.php');
	if (isset($_FILES["submission"])) {
		if ($_FILES["submission"]["error"] > 0)
		{
			echo "Error: " . $_FILES["submission"]["error"] . "<br>";
		}
		else
		{
			echo "Upload: " . $_FILES["submission"]["name"] . "<br>";
			echo "Type: " . $_FILES["submission"]["type"] . "<br>";
			echo "Size: " . $_FILES["submission"]["size"] . " B<br>";
			echo "Stored in: " . $_FILES["submission"]["tmp_name"];
		}
	} else {
		header("Location:" . FLD);
	}
?>
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
			echo "Stored in: " . $_FILES["submission"]["tmp_name"] . "<br>";
			echo $_POST['language'];
			$probid = $_SESSION['probid'];
			$fileName = $_FILES["submission"]["name"];
			$saveFileName = "";
			$connect = newConnection();
			$res = $connect->prepare("SELECT * FROM `contest` where starttime <= now() and endtime >= now()");
			$res->execute();
			if ($res->rowCount() == 0) {
				throwError("No Contest");
			}
			$row = $res->fetch();
			$cid = $row['cid'];
			$res = $connect->prepare("SELECT * FROM `team` where name = '" . $_SESSION['username'] ."'");
			$res->execute();
			if ($res->rowCount() == 0)
				throwError("No Valid Team");
			$row = $res->fetch();
			$team = $row['login'];
			$res = $connect->prepare("SELECT * FROM `language` where name = '" . $_POST['language'] . "'");
			$res->execute();
			if ($res->rowCount() == 0)
				throwError("No Valid Team");
			$row = $res->fetch();
			$langid = $row['langid'];
			$origsubmitid = NULL;
			$res = $connect->prepare("RETURNID INSERT INTO submission
				  (cid, teamid, probid, langid, submittime, origsubmitid)
				  VALUES ($cid, $team, $probid, $langid, now(), $origsubmitid)");
			$res->execute();
			$saveFileName = $cid . "." .$id . "." . $team . "." . $probid . ".0." . $fileName;
			move_uploaded_file($_FILES["submission"]["tmp_name"], UPLOAD_DIR . $saveFileName);
		}
	} else {
		header("Location:" . FLD);
	}
?>
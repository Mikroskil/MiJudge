<?php
	include_once('/config.php');
	if (isset($_FILES["submission"])) {
		if ($_FILES["submission"]["error"] > 0)
		{
			throwError("Error: " . $_FILES["submission"]["error"]);
		}
		else
		{
			/*
			echo "Upload: " . $_FILES["submission"]["name"] . "<br>";
			echo "Type: " . $_FILES["submission"]["type"] . "<br>";
			echo "Size: " . $_FILES["submission"]["size"] . " B<br>";
			echo "Stored in: " . $_FILES["submission"]["tmp_name"] . "<br>";
			//echo $_POST['language'];
			*/
			$probid = $_POST['probid'];
			$fileName = $_FILES["submission"]["name"];
			$tempName = $_FILES["submission"]["tmp_name"];
			$connect = newConnection();
			
			$res = newQuery($connect, "SELECT * FROM `contest` where starttime <= now() and endtime >= now()");
			if ($res->rowCount() == 0)
				throwError("No Contest");
			$row = $res->fetch();
			$cid = $row['cid'];
			
			$res = newQuery($connect, "SELECT * FROM `team` where name=:username", array('username' => $_SESSION['username']));
			if ($res->rowCount() == 0)
				throwError("No Valid Team");
			$row = $res->fetch();
			$team = $row['login'];
			
			$res = newQuery($connect, "SELECT * FROM `language` where name = '" . $_POST['language'] . "'");
			if ($res->rowCount() == 0)
				throwError("No Valid Team");
			$row = $res->fetch();
			$langid = $row['langid'];
			
			try {
				$connect->beginTransaction();
				$res = newQuery($connect, "INSERT INTO submission
						(cid, teamid, probid, langid, submittime, origsubmitid)
						VALUES (:cid, :team, :probid, :langid, now(), null)", array('cid' => $cid, 'team' => $team, 'probid' => $probid, 'langid' => $langid));
				$id = $connect->lastInsertId();
				$res = newQuery($connect, "INSERT INTO submission_file
						(submitid, filename, rank, sourcecode) VALUES (:id, :filename, :rank, :source)",
						array('id' => $id, 'filename' => $fileName, 'rank' => 0, 'source' => getFileContents($tempName)));
				$connect->commit();
			} catch (PDOExecption $e) {
				$connect->rollback();
				throwError($e->getMessage());
			}
			$saveFileName = "c" . $cid . ".s" .$id . "." . $team . "." . $probid . "." . $langid . ".0." . $fileName;
			move_uploaded_file($_FILES["submission"]["tmp_name"], UPLOAD_DIR . $saveFileName);
			$_SESSION['result'] = "Submission Accepted Succesfully";
			header("Location:" . $_SESSION['lastpage']);
		}
	} else {
		header("Location:" . FLD);
	}
?>
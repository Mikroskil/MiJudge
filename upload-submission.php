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
			$probid = $_POST['problem'];
			$cid = $_POST['contest'];
			$fileName = $_FILES["submission"]["name"];
			$tempName = $_FILES["submission"]["tmp_name"];
			$connect = newConnection();
			
			if ($cid != 0) {
				$res = newQuery($connect, "SELECT count(*) FROM `contest` where starttime <= now() and cid=:cid", array('cid' => $cid));
				if ($res->fetchColumn() == 0)
					throwError("This Contest Has Not Started");

				$res = newQuery($connect, "SELECT count(*) FROM `contest` where endtime >= now() and cid=:cid", array('cid' => $cid));
				if ($res->fetchColumn() == 0)
					throwError("This Contest Has Finished");
			}
			
			$res = newQuery($connect, "SELECT * FROM `team` where name=:username", array('username' => $_SESSION['username']));
			$row = $res->fetch();
			if ($row == null)
				throwError("Invalid Team");
			$team = $row['login'];
			
			$res = newQuery($connect, "SELECT * FROM `language` where name = '" . $_POST['language'] . "'");
			$row = $res->fetch();
			if ($row == null)
				throwError("Language Not Supported");
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
<?php
//die('asdf');
	include_once('../config.php');
	if (isAdmin) {
		if (isset($_GET['contest'])) {
			$connect = newConnection();
			$cid = $_GET['contest'];
			try {
				$connect->beginTransaction();
				$res = newQuery($connect, "delete from clarification where cid=:cid", array('cid' => $cid));
				$res = newQuery($connect, "delete from judging where cid=:cid", array('cid' => $cid));
				$res = newQuery($connect, "delete from submission_file where submitid in (select submitid from submission where cid=:cid)", array('cid' => $cid));
				$res = newQuery($connect, "delete from submission where cid=:cid", array('cid' => $cid));
				$res = newQuery($connect, "delete from scoreboard_jury where cid=:cid", array('cid' => $cid));
				$res = newQuery($connect, "delete from scoreboard_public where cid=:cid", array('cid' => $cid));
				$res = newQuery($connect, "delete from testcase where probid in (select probid from problem where cid=:cid)", array('cid' => $cid));
				$res = newQuery($connect, "delete from problem where cid=:cid", array('cid' => $cid));
				$res = newQuery($connect, "delete from contest where cid=:cid", array('cid' => $cid));
				$connect->commit();
			} catch (PDOExecption $e) {
				$connect->rollback();
				throwError($e->getMessage());
			}
		}
	}
	header("Location:" . FLD . "contests");
?>
<?php
	include_once('../config.php');
	if (isAdmin) {
		$connect = newConnection();
		if (isset($_POST['contestid'])) {
			$atime = ($_POST['activatetime'] != "") ? $_POST['activatetime'] : null;
			$stime = ($_POST['starttime'] != "") ? $_POST['starttime'] : null;
			$ftime = ($_POST['freezetime'] != "") ? $_POST['freezetime'] : null;
			$etime = ($_POST['endtime'] != "") ? $_POST['endtime'] : null;
			$utime = ($_POST['unfreezetime'] != "") ? $_POST['unfreezetime'] : null;
			$res = newQuery($connect, "update contest set contestname=:cname, activatetime=:atime, starttime=:stime, freezetime=:ftime, endtime=:etime, unfreezetime=:utime 
										where cid=:cid", array( 'cid' => $_POST['contestid'],
																'cname' => $_POST['contestname'],
																'atime' => $atime,
																'stime' => $stime,
																'ftime' => $ftime,
																'etime' => $etime,
																'utime' => $utime));
			header("Location:" . FLD . "contests");
		}
		if (isset($_GET['contest'])) {
			$cid = $_GET['contest'];
			$res = newQuery($connect, "select * from contest where cid=:cid", array('cid' => $cid));
			$row = $res->fetch();
			if ($row == null)
				header("Location:" . FLD);
		echo "
	<div class=\"span8\">
		<form method=\"post\">
			<fieldset>
				<legend>Edit Contest</legend>
				<input type=\"hidden\" value=\"$cid\" placeholder=\"\" name=\"contestid\"/>
				<div>
				<input type=\"text\" value=\"$row[contestname]\" placeholder=\"Contest Name\" title=\"Contest Name\" name=\"contestname\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"$row[activatetime]\" placeholder=\"Activate Time\" title=\"Activate Time\" name=\"activatetime\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"$row[starttime]\" placeholder=\"Start Time\" title=\"Start Time\" name=\"starttime\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"$row[freezetime]\" placeholder=\"Freeze Time\" title=\"Freeze Time\" name=\"freezetime\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"$row[endtime]\" placeholder=\"End Time\" title=\"End Time\" name=\"endtime\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"$row[unfreezetime]\" placeholder=\"Unfreeze Time\" title=\"Unfreeze Time\" name=\"unfreezetime\" data-transform=\"input-control\"/>
				</div>
				<input type=\"submit\" value=\"Update\">
			</fieldset>
		</form>
	</div>";
		} else
			header("Location:" . FLD);
	} else
		header("Location:" . FLD);
?>
<?php
	if (isAdmin) {
		if (isset($_POST['contestid'])) {
			$atime = ($_POST['activatetime'] != "") ? $_POST['activatetime'] : null;
			$stime = ($_POST['starttime'] != "") ? $_POST['starttime'] : null;
			$ftime = ($_POST['freezetime'] != "") ? $_POST['freezetime'] : null;
			$etime = ($_POST['endtime'] != "") ? $_POST['endtime'] : null;
			$utime = ($_POST['unfreezetime'] != "") ? $_POST['unfreezetime'] : null;
			//insert into
			//die("asdf");
			$connect = newConnection();
			$res = newQuery($connect, "insert into contest
									values (null, :cname, :atime, :stime, :ftime, :etime, :utime, :atimes, null, :etimes, null, 1)",
										array( 'cname' => $_POST['contestname'],
												'atime' => $atime,
												'stime' => $stime,
												'ftime' => $ftime,
												'etime' => $etime,
												'utime' => $utime,
												'atimes' => $atime,
												'etimes' => $etime));
			header("Location:" . FLD . "contests");
		}
		echo "
	<div class=\"span8\">
		<form method=\"post\">
			<fieldset>
				<legend>Add Contest</legend>
				<input type=\"hidden\" value=\"\" placeholder=\"\" name=\"contestid\"/>
				<div>
				<input type=\"text\" value=\"\" placeholder=\"Contest Name\" title=\"Contest Name\" name=\"contestname\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"\" placeholder=\"Activate Time\" title=\"Activate Time\" name=\"activatetime\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"\" placeholder=\"Start Time\" title=\"Start Time\" name=\"starttime\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"\" placeholder=\"Freeze Time\" title=\"Freeze Time\" name=\"freezetime\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"\" placeholder=\"End Time\" title=\"End Time\" name=\"endtime\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"\" placeholder=\"Unfreeze Time\" title=\"Unfreeze Time\" name=\"unfreezetime\" data-transform=\"input-control\"/>
				</div>
				<input type=\"submit\" value=\"Save\">
			</fieldset>
		</form>
	</div>";
	} else
		header("Location:" . FLD);
?>
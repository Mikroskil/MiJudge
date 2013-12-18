<?php
	include_once('../config.php');
	if (isAdmin) {
		$connect = newConnection();
		if (isset($_POST['problemid'])) {
			header("Location:" . FLD . "problemset");
		}
		if (isset($_GET['problem'])) {
			$pid = $_GET['problem'];
			$res = newQuery($connect, "select * from problem where probid=:pid", array('pid' => $pid));
			$row = $res->fetch();
			if ($row == null)
				header("Location:" . FLD);
		echo "
	<div class=\"span8\">
		<form method=\"post\">
			<fieldset>
				<legend>Edit Problem</legend>
				<input type=\"hidden\" value=\"$pid\" placeholder=\"\" name=\"problemid\"/>
				<div>
				<input type=\"text\" value=\"$row[name]\" placeholder=\"Problem Name\" title=\"Contest Name\" name=\"contestname\" data-transform=\"input-control\"/>
				<input type=\"text\" value=\"$row[timelimit]\" placeholder=\"Activate Time\" title=\"Activate Time\" name=\"activatetime\" data-transform=\"input-control\"/>
				<textarea rows=\"20\" data-transform=\"input-control\">$row[problemtext]</textarea>
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
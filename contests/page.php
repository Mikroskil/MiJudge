<?php
	if (isset($_GET['contest'])) {
		include_once('contest.php');
	} else {
		$connect = newConnection();
		echo "
	<div class=\"span8\">";
		$res = newQuery($connect, "select * from contest where endtime > now() order by endtime desc");
		echo "
		<h3>Current Contests</h3>
		<table class=\"table hovered\">
			<thead>
				<tr>
					<th class=\"text-left\">Name</th>
					<th class=\"text-left\">Start</th>
					<th class=\"text-left\">End</th>
				</tr>
			</thead>";
			if ($res->rowCount() > 0) {
				echo "
			<tbody>";
				foreach ($res as $row) {
					echo "
				<tr>
					<td><a href=\"" . FLD . "contest/$row[cid]\">$row[contestname]</td>
					<td class=\"text-center\">$row[starttime]</td>
					<td class=\"text-center\">$row[endtime]</td>
				</tr>";
				}
				echo "
			</tbody>";
			}
			echo "
		</table>";
		openRow();
		$res = newQuery($connect, "select * from contest where endtime < now() order by endtime desc");
		echo "
		<h3>Past Contests</h3>
		<table class=\"table hovered\">
			<thead>
				<tr>
					<th class=\"text-left\">Name</th>
					<th class=\"text-left\">Start</th>
					<th class=\"text-left\">End</th>
				</tr>
			</thead>";
			if ($res->rowCount() > 0) {
				echo "
			<tbody>";
				foreach ($res as $row) {
					echo "
				<tr>
					<td><a href=\"" . FLD . "contest/$row[cid]\">$row[contestname]</td>
					<td class=\"text-center\">$row[starttime]</td>
					<td class=\"text-center\">$row[endtime]</td>
				</tr>";
				}
				echo "
			</tbody>";
			}
			echo "
		</table>";
		closeRow();
		echo "
	</div>
";
	}
?>
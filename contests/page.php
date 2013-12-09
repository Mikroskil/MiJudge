<?php
	if (isset($_GET['contest'])) {
		include_once('contest.php');
	} else {
		$connect = newConnection();
		echo "
	<div class=\"span8\">";
		$res = newQuery($connect, "select * from contest where endtime > now() order by endtime desc");
		$contests = $res->fetchAll();
		echo "
		<h3>Current Contests</h3>
		<table class=\"table hovered\">
			<thead>
				<tr>
					<th class=\"text-left\">Name</th>
					<th class=\"text-center\">Start</th>
					<th class=\"text-center\">End</th>
				</tr>
			</thead>";
			if (count($contests) > 0) {
				echo "
			<tbody>";
				foreach ($contests as $row) {
					echo "
				<tr>
					<td><a href=\"" . FLD . "contest/?contest=$row[cid]\">$row[contestname]</td>
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
		$contests = $res->fetchAll();
		echo "
		<h3>Past Contests</h3>
		<table class=\"table hovered\">
			<thead>
				<tr>
					<th class=\"text-left\">Name</th>
					<th class=\"text-center\">Start</th>
					<th class=\"text-center\">End</th>
				</tr>
			</thead>";
			if (count($contests) > 0) {
				echo "
			<tbody>";
				foreach ($contests as $row) {
					echo "
				<tr>
					<td><a href=\"" . FLD . "contest/?contest=$row[cid]\">$row[contestname]</td>
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
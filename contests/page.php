<?php
	if (isset($_GET['mode'])) {
		if ($_GET['mode'] == "edit") {
			include_once('edit-contest.php');
		} else {
			include_once('delete-contest.php');
		}
	} else if (isset($_GET['contest'])) {
		include_once('contest.php');
	} else {
		$connect = newConnection();
		echo "
	<div class=\"span8\">";
		if (isAdmin) {
			echo "
		<script>
		function AreYouSure()
		{
			return (confirm(\"Are You Sure?\") ? confirm(\"Are You Really Sure?\") : false);
		}
		</script>";
		}
		$res = newQuery($connect, "select * from contest where endtime > now() order by endtime desc");
		$curContests = $res->fetchAll();
		$res = newQuery($connect, "select * from contest where endtime < now() order by endtime desc");
		$pastContests = $res->fetchAll();
		placeContest("Current Contests", $curContests);
		placeContest("Past Contests", $pastContests);
		unset($curContests);
		unset($pastContests);
		echo "
	</div>";
	}

function placeContest($title, $table)
{
	echo "
		<h3>$title</h3>
		<table class=\"table bordered hovered\">
			<thead>
				<tr>
					<th class=\"text-left\">Name</th>
					<th class=\"text-center\">Start</th>
					<th class=\"text-center\">End</th>";
	if (isAdmin) {
		echo "
					<th class=\"text-center\"></th>";
	}
	echo "
				</tr>
			</thead>";
	if (count($table) > 0) {
		echo "
			<tbody>";
		foreach ($table as $row) {
			echo "
				<tr>
					<td><a href=\"" . FLD . "contest/?contest=$row[cid]\">$row[contestname]</td>
					<td class=\"text-center\">$row[starttime]</td>
					<td class=\"text-center\">$row[endtime]</td>";
			if (isAdmin) {
				echo "
					<td class=\"text-center\">
						<a href=\"?contest=$row[cid]&mode=edit\" title=\"Edit Contest\"><span class=\"icon-pencil\"></span></a>&nbsp;
						<a href=\"?contest=$row[cid]&mode=delete\" title=\"Delete Contest\" onclick=\"return AreYouSure();\"><span class=\"icon-cancel\"></span></a>
					</th>";
			}
			echo "
				</tr>";
		}
		echo "
			</tbody>";
	}
	echo "
		</table>";
}
?>
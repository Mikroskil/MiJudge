<?php
	if (isset($_GET['mode'])) {
		if ($_GET['mode'] == "edit") {
			include_once('edit-problem.php');
		} else {
			include_once('delete-problem.php');
		}
	} else if (isset($_GET['problem'])) {
		include_once('problem.php');
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
		echo "
		<h3>All Problems</h3>
		<table class=\"table bordered hovered\">
			<thead>
				<tr>
					<th class=\"text-center\">#</th>
					<th class=\"text-left\">Name</th>";
		if (isAdmin) {
			echo "
					<th class=\"text-center\"></th>";
		}
		echo "
				</tr>
			</thead>";
		$res = newQuery($connect, "select * from problem where cid not in (select cid from contest where endtime > now())");
		$problems = $res->fetchAll();
		if (count($problems) > 0) {
			echo "
			<tbody>";
			foreach ($problems as $row) {
				echo "
				<tr>
					<td class=\"text-center\"><a href=\"?problem=$row[probid]\">$row[probid]</a></td>
					<td>$row[name]</td>";
				if (isAdmin) {
					echo "
					<td class=\"text-center\">
						<a href=\"?problem=$row[probid]&mode=edit\" title=\"Edit Contest\"><span class=\"icon-pencil\"></span></a>&nbsp;
						<a href=\"?problem=$row[probid]&mode=delete\" title=\"Delete Contest\" onclick=\"return AreYouSure();\"><span class=\"icon-cancel\"></span></a>
					</th>";
				}
				echo "
				</tr>";
			}
			echo "
			</tbody>";
		}
		unset($problems);
		echo "
		</table>
	</div>";
	}
?>
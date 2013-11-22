<?php
	if (isset($_GET['contest'])) {
		include_once('contest.php');
	} else {
		$connect = newConnection();
		$res = $connect->prepare("select * from contest order by endtime desc");
		$res->execute();
		echo "
	<div class=\"span8\">
		<h3>All Contests</h3>
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
					<td><a href=\"?contest=$row[cid]\">$row[contestname]</a></td>
					<td class=\"text-center\">$row[starttime]</td>
					<td class=\"text-center\">$row[endtime]</td>
				</tr>";
				}
				echo "
			</tbody>";
			}
			echo "
		</table>
	</div>
";
	}
?>
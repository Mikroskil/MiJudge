<?php
	if (isset($_GET['problem'])) {
		include_once('problem.php');
	} else {
		$connect = newConnection();
		$res = newQuery($connect, "select * from problem where cid not in (select cid from contest where starttime > now())");
		$problems = $res->fetchAll();
		echo "
	<div class=\"span8\">
		<h3>All Problems</h3>
		<table class=\"table hovered\">
			<thead>
				<tr>
					<th class=\"text-center\">#</th>
					<th class=\"text-left\">Name</th>
				</tr>
			</thead>";
			if (count($problems) > 0) {
				echo "
			<tbody>";
				foreach ($problems as $row) {
					echo "
				<tr>
					<td class=\"text-center\"><a href=\"?problem=$row[probid]\">$row[probid]</a></td>
					<td>$row[name]</td>
				</tr>";
				}
				echo "
			</tbody>";
			}
			echo "
		</table>
	</div>";
	}
?>
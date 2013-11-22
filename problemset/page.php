<?php
	if (isset($_GET['problem'])) {
		include_once('problem.php');
	} else {
		$connect = newConnection();
		$res = $connect->prepare("select * from problem");
		$res->execute();
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
			if ($res->rowCount() > 0) {
				echo "
			<tbody>";
				foreach ($res as $row) {
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
	</div>
";
	}
?>
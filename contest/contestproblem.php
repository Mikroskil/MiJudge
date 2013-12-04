<?php
	$res = newQuery($connect, "select * from contest a inner join problem b on a.cid=b.cid where a.cid=:id", array('id' => $_GET['contest']));
	echo "
		<table class=\"table hovered\">
			<thead>
				<tr>
					<th class=\"text-center\">#</th>
					<th class=\"text-left\">Problem Name</th>
				</tr>
			</thead>";
	if ($res->rowCount() > 0) {
		echo "
			<tbody>";
		foreach ($res as $row) {
			echo "
				<tr>
					<td class=\"text-center\"><a href=\"?contest=$_GET[contest]&problem=$row[probid]\">$row[probid]</a></td>
					<td>$row[name]</td>
				</tr>";
		}
		echo "
			</tbody>";
	}
	echo "
		</table>";
?>
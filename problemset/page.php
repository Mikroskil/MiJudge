<?php
	$connect = mysql_connect(DB_Server, DB_Login, DB_Password);
	mysql_select_db(DB_Name);
	$query = "select * from problem";
	$problem = mysql_query($query);
	echo "
	<div class='span8'>
		<span>All Problems</span>
		<table class='table hovered'>
			<thead>
				<tr>
					<th class='text-center'>#</th>
					<th class='text-left'>Name</th>
				</tr>
			</thead>
			<tbody>";
	while ($row = mysql_fetch_array($problem)) {
			echo "
				<tr>
					<td class='text-center'><a href=?page=$row[probid]>$row[probid]</a></td>
					<td>$row[name]</td>
				</tr>";
	}
		echo "
			</tbody>
		</table>
	</div>
";
	mysql_close($connect);
?>
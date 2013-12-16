<?php
	$connect = newConnection();
	$total_submission = newQuery($connect, "SELECT * FROM submission WHERE teamid = '$_SESSION[username]'");
	echo "
	<div class=\"span8\">
		<table class=\"table\">
			<thead>
				<tr>
					<th class=\"text-left\"><h2>$_SESSION[username]</h2></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class=\"text-left\"><img src='../images/default_profile.png'></img></td>
				</tr>
				<tr>
					<td class=\"text-left\">Total Submissions: ".count($total_submission->fetchAll())."</td>
				</tr>
			</tbody>
		</table>
	</div>";
?>
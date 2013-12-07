<?php
	if (isLogin()) {
		$res = newQuery($connect, "select s.submitid, t.name, s.probid, s.langid, s.submittime, j.result FROM submission s
					LEFT JOIN team     t ON (t.login    = s.teamid)
					LEFT JOIN judging  j ON (s.submitid = j.submitid AND j.valid=1)
					where s.cid=:cid and s.teamid=:username", array('cid' => $_GET['contest'], 'username' => $_SESSION['username']));
	}
	$submission = $res->fetchAll();
	echo "
		<table class=\"table hovered\">
			<thead>
				<tr>
					<th class=\"text-center\">#</th>
					<th class=\"text-left\">Team</th>
					<th class=\"text-left\">Problem</th>
					<th class=\"text-left\">Lang</th>
					<th class=\"text-left\">Time</th>
					<th class=\"text-left\">Result</th>
				</tr>
			</thead>";
	if (count($submission) > 0) {
		echo "
			<tbody>";
		foreach ($submission as $row) {
			echo "
				<tr>
					<td class=\"text-center\">$row[submitid]</td>
					<td>$row[name]</td>
					<td>$row[probid]</td>
					<td>$row[langid]</td>
					<td>$row[submittime]</td>
					<td>$row[result]</td>
				</tr>";
		}
		echo "
			</tbody>";
	}
	echo "
		</table>";
?>
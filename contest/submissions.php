<?php
	if (isLogin()) {
		if (isAdmin) {
			$filter = "order by s.submitid";
			$farray = array('cid' => $_GET['contest']);
		} else {
			$filter = "and s.teamid=:username";
			$farray = array('cid' => $_GET['contest'], 'username' => $_SESSION['username']);
		}
		$res = newQuery($connect, "select s.submitid, t.name, s.probid, s.langid, s.submittime, 
					(case when j.result is null then 'Pending' else j.result end) as result FROM submission s
					LEFT JOIN team     t ON (t.login    = s.teamid)
					LEFT JOIN judging  j ON (s.submitid = j.submitid AND j.valid=1)
					where s.cid=:cid $filter", $farray);
	} else {
		return;
	}
	$submission = $res->fetchAll();
	echo "
		<table class=\"table bordered hovered\">
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
					<td><a href=\"?contest=$_GET[contest]&problem=$row[probid]\">$row[probid]</a></td>
					<td>$row[langid]</td>
					<td>$row[submittime]</td>
					<td>$row[result]</td>
				</tr>";
		}
		echo "
			</tbody>";
	}
	unset($submission);
	echo "
		</table>";
?>
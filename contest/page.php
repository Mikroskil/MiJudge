<?php
	if (isset($_GET['contest'])) {
		$connect = newConnection();
		$res = newQuery($connect, "select * from contest where cid=:id", array('id' => $_GET['contest']));
		$row = $res->fetch();
		echo "
	<div class=\"span8\">";
		echo "
		<h3><a href=\"?contest=$_GET[contest]\">Contest $row[contestname]</a></h3>";
		echo "
			<div class=\"tab-control\" data-role=\"tab-control\">
				<ul class=\"tabs\">
					<li class=\"active\"><a href=\"#problem\">Problem</a></li>
					<li><a href=\"#submissions\">Submissions</a></li>
				</ul>
				<div class=\"frames\">
					<div class=\"frame\" id=\"problem\">";
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
		openRow();
		if (isset($_GET['problem']))
			include_once(ROOT_DIR . "/problemset/problem.php");
		closeRow();
		echo "
					</div>
					<div class=\"frame\" id=\"submissions\">";
					$res = newQuery($connect, "select s.submitid, t.name, s.probid, s.langid, s.submittime, j.result FROM submission s
								LEFT JOIN team     t ON (t.login    = s.teamid)
								LEFT JOIN judging  j ON (s.submitid = j.submitid AND j.valid=1)
								where s.cid=:cid and s.teamid=:username", array('cid' => $_GET['contest'], 'username' => $_SESSION['username']));
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
						if ($res->rowCount() > 0) {
							echo "
						<tbody>";
							foreach ($res as $row) {
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
					echo "
					</div>
				</div>
			</div>";
		echo "
	</div>";
	} else {
		header("Location: " . FLD);
	}
?>
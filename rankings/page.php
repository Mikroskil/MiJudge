<?php
	$connect = newConnection();
	$res = newQuery($connect, "select a.teamid, count(*) as total, b.totalsubmit, b.accepted*100.0/b.totalsubmit as persentase from (
						SELECT distinct a.teamid, a.probid FROM submission a
						inner join judging b on a.submitid=b.submitid
						where b.result='correct'
					) a
					inner join (
						SELECT teamid, sum(case when b.result='correct' then 1 else 0 end) as accepted, count(*) as totalsubmit FROM submission a
						inner join judging b on a.submitid=b.submitid
						group by teamid
					) b on a.teamid=b.teamid
					group by a.teamid
					order by total desc, persentase desc");
	$rank = $res->fetchAll();
	echo "
	<div class='span8'>
		<h3>Rank List </h3>
		<table class=\"table bordered hovered\">
			<thead>
				<tr>
					<th class=\"text-center\">#</th>
					<th class=\"text-left\">Name</th>
					<th class=\"text-center\">Solved/Submissions</th>
					<th class=\"text-center\">Ratio</th>
				</tr>
			</thead>";
			if (count($rank) > 0) {
				echo "
			<tbody>";
				$i = 1;
				foreach ($rank as $row) {
					echo "
				<tr>
					<td class=\"text-center\">$i</td>
					<td>$row[teamid]</td>
					<th class=\"text-center\">$row[total] / $row[totalsubmit]</th>
					<th class=\"text-center\">";
					printf("%.2lf", $row['persentase']);
					echo " %</th>
				</tr>";
					$i++;
				}
				echo "
			</tbody>";
			}
			echo "
		</table>
	</div>";
?>
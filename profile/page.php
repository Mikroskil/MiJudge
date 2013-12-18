<?php
	$connect = newConnection();
	$que = newQuery($connect, "SELECT teamid, sum(case when b.result='correct' then 1 else 0 end) as accepted
							, sum(case when b.result='wrong-answer' then 1 else 0 end) as wrong_answer
							, sum(case when b.result='compile-error' then 1 else 0 end) as compile_error
							, sum(case when b.result='runtime-error' then 1 else 0 end) as runtime_error
							, sum(case when b.result='time-limit' then 1 else 0 end) as time_limit
							, count(*) as totalsubmit FROM submission a
							inner join judging b on a.submitid=b.submitid
							where teamid='$_SESSION[username]'");
	$res = $que->fetchAll();
	echo "
	<div class=\"span8\">
		<table class=\"table\">
			<thead>
				<tr>
					<th class=\"text-left\"><h1>$_SESSION[username]</h1></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class=\"text-left\"><img src='../images/default_profile.png'></img></td>
				</tr>
				<tr>
					<td class=\"text-left\"><h2>Problems Solved: ".$res[0]['accepted']."<br> <!--belum tahu cara ambil berapa solved-->
											Total Submissions: ".$res[0]['totalsubmit']."<br>
											Accepted: ".$res[0]['accepted']."<br>
											Wrong Answer: ".$res[0]['wrong_answer']."<br>
											Compile Error: ".$res[0]['compile_error']."<br>
											Runtime Error: ".$res[0]['runtime_error']."<br>
											Time Limit Exceeded: ".$res[0]['time_limit']."
					</h2></td>
				</tr>
			</tbody>
		</table>
	</div>";
?>
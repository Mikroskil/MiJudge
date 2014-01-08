<?php
	if (isLogin() || isset($_GET['user'])) {
		$user;
		if (isset($_GET['user']))
			$user = $_GET['user'];
		else
			$user = $_SESSION['username'];
		$connect = newConnection();
		$que = newQuery($connect, "SELECT teamid
								, sum(case when b.result='correct' then 1 else 0 end) as accepted
								, sum(case when b.result='wrong-answer' then 1 else 0 end) as wrong_answer
								, sum(case when b.result='compiler-error' then 1 else 0 end) as compile_error
								, sum(case when b.result='runtime-error' then 1 else 0 end) as runtime_error
								, sum(case when b.result='time-limit-error' then 1 else 0 end) as time_limit
								, count(*) as totalsubmit FROM submission a
								inner join judging b on a.submitid=b.submitid
								where teamid=:user", array('user' => $user));
		$res = $que->fetchAll();
		
		$que2 = newQuery($connect, "select count(*) from (SELECT distinct a.probid FROM submission a
							inner join judging b on a.submitid=b.submitid
							where b.result='correct' and a.teamid=:user) a", array('user' => $user));
		$solved = $que2->fetchColumn();
		
		$que3 = newQuery($connect, "SELECT name from team where login=:user" , array('user' => $user));
		$fullname = $que3->fetchColumn();
		
		/*
		//pake mysql biasa, pas pake PDO Error trus, ud stress wkwk
		$conn = mysql_connect(DB_Server,DB_Login,DB_Password) or die ("SERVER DOWN");
		$db   = mysql_select_db(DB_Name, $conn) or die ("DATABASE TIDAK ADA");
		$que2 = mysql_query("SELECT distinct a.probid FROM submission a
							inner join judging b on a.submitid=b.submitid
							where b.result='correct' and a.teamid='$_GET[user]'");
		$solved = mysql_num_rows($que2);
		$que3 = mysql_query("SELECT name from team where login='$_GET[user]'");
		$fullname = mysql_fetch_array($que3)[0];*/
		if($res[0]['totalsubmit']==0){
			$res[0]['accepted']=$res[0]['wrong_answer']=$res[0]['compile_error']=$res[0]['runtime_error']=$res[0]['time_limit']=0;
		}
		echo "
		<div class=\"span8\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"text-left\"><h1>$_GET[user]<br>($fullname)</h1></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-left\"><img src='../images/default_profile.png'></img></td>
					</tr>
					<tr>
						<td class=\"text-left\"><h2>Problems Solved: ".$solved."<br> <!--belum tahu cara ambil berapa solved-->
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
	} else {
		goHome();
	}
?>
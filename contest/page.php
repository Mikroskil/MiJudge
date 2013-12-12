<?php
	if (isset($_GET['contest'])) {
		if (!isset($_GET['menu']))
			$_GET['menu'] = "problem";
		$connect = newConnection();
		$res = newQuery($connect, "select * from contest where cid=:id and starttime<=now()", array('id' => $_GET['contest']));
		$row = $res->fetch();
		if ($row == null)
			header("Location:" . FLD);
		echo "
	<div class=\"span8\">
		<nav class=\"horizontal-menu\">
			<ul>
				<li><a href=\"?contest=$_GET[contest]\">Problem</a></li>
				<li><a href=\"?contest=$_GET[contest]&menu=submissions\">Submissions</a></li>
				<li><a href=\"?contest=$_GET[contest]&menu=scoreboard\">Scoreboard</a></li>
			</ul>
		</nav>";
		if ($_GET['menu'] == "submissions")
			include_once 'submissions.php';
		else if ($_GET['menu'] == "scoreboard")
			include_once 'scoreboard.php';
		else if (!isset($_GET['problem']))
			include_once 'contestproblem.php';
		else
			include_once(ROOT_DIR . "/problemset/problem.php");
		echo "
	</div>";
	} else {
		header("Location: " . FLD);
	}
?>
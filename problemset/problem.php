<?php
	$connect = newConnection();
	$res = newQuery($connect, "select * from problem where probid=:id and cid not in (select cid from contest where starttime > now())",
					(array('id' => $_GET['problem'])));
	$problem = $res->fetch();
	if ($problem != null) {
		$_SESSION['hasProblem'] = true;
		if ($problem['problemtext_type'] == "html") {
			echo "
	<div class=\"span8\">
$problem[problemtext]
	</div>";
		} else if ($problem['problemtext_type'] == "pdf") {
			header("Location: " . FLD . "showPDF.php?problem=$_GET[problem]");
		} else {
			echo "
	<div class=\"span8\">
Problem ID $_GET[problem] Could Not Displayed.
	</div>";
		}
	} else {
		echo "
	<div class=\"span8\">
Problem ID $_GET[problem] Not Found.
	</div>";
	}
?>
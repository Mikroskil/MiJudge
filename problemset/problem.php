<?php
	$connect = newConnection();
	$res = $connect->prepare("select * from problem where probid=:id");
	$res->execute(array('id' => $_GET['problem']));
	if ($res->rowCount() == 1) {
		$row = $res->fetch();
		if ($row['problemtext_type'] == "html") {
			echo "
	<div class=\"span8\">
$row[problemtext]
	</div>";
		} else if ($row['problemtext_type'] == "pdf") {
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
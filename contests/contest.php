<?php
	$connect = newConnection();
	$res = $connect->prepare("select * from contest where cid=:id");
	$res->execute(array('id' => $_GET['contest']));
	if ($res->rowCount() == 1) {
		$row = $res->fetch();
		echo "
	<div class=\"span8\">";
	echo "
		<h3><a href=\"?contest=$_GET[contest]\">Contest $row[contestname]</a></h3>";
		$res = $connect->prepare("select * from contest a inner join problem b on a.cid=b.cid where a.cid=:id");
		$res->execute(array('id' => $_GET['contest']));
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
		";
	} else {
		echo "
	<div class=\"span8\">
Contest Not Found.
	</div>
";
	}
?>

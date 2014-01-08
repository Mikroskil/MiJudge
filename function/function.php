<?php

function newConnection()
{
	try {
		$connect = new PDO('mysql:host='.DB_Server.';dbname='.DB_Name, DB_Login, DB_Password);
		$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $connect;
	} catch (PDOException $e) {
		throwError("Server Not Found");
	}
}

function newQuery($connection, $query, $array = NULL)
{
	$result = $connection->prepare($query);
	if ($array != NULL)
		$result->execute($array);
	else
		$result->execute();
	return $result;
}

function throwError($err)
{
	$_SESSION['error'] = $err;
	header("Location: " . FLD . "error.php");
}

function dbconfig_get($conf)
{
	$con = newConnection();
	$res = newQuery($connect, "select * from configuration where name=:conf", array('conf' => $conf));
	$row = $res->fetch();
	return $row['value'];
}

function addMetahttp()
{
	include_once ROOT_DIR . '/meta-http.php';
}

function addHeader()
{
	echo "<body class=\"metro\">
";
	include_once ROOT_DIR . '/header.php';
}

function addSidebar()
{
	include_once ROOT_DIR . '/sidebar.php';
}

function addFooter()
{
	include_once ROOT_DIR . '/footer.php';
	echo "
	</body>";
}

function openHTML($title = null)
{
	if ($_SERVER['REQUEST_URI'] != "/login/" && $_SERVER['REQUEST_URI'] != "/login/index.php" && 
		$_SERVER['REQUEST_URI'] != "/register/" && $_SERVER['REQUEST_URI'] != "/register/index.php")
		$_SESSION['lastpage'] = $_SERVER['REQUEST_URI'];
	if ($title == null)
		$title = "Mikroskil Online Judge";
	else
		$title = "Mikroskil Online Judge | " . $title;
	echo "<!DOCTYPE html>
<html lang=\"en\">
<html>
	<head>
	<title>" . $title . "</title>";
	addMetahttp();
	echo "
	</head>
";
}

function closeHTML()
{
	echo "
</html>";
}

function openPageRegion()
{
	//echo $_SERVER['REQUEST_URI'];
	echo "
	<div class=\"page\">
		<div class=\"page-region\">";
	openGrid();
}

function closePageRegion()
{
	closeGrid();
	echo "
		</div>
	</div>";
}

function openGrid()
{
	echo "
			<div class=\"grid\">";
	openRow();
}

function closeGrid()
{
	closeRow();
	echo "
			</div>";
}

function openRow()
{
	echo "
				<div class=\"row\">";
}

function closeRow()
{
	echo "
				</div>";
}

function isLogin()
{
	return isset($_SESSION['username']);
}

function goHome()
{
	header("Location: " . FLD);
}

function getFileContents($filename)
{
	if (!file_exists($filename) ) {
		return '';
	}
	if (!is_readable($filename) ) {
		error("Could not open $filename for reading: not readable");
	}
	return file_get_contents($filename);
}

function getPenaltyTime($isSolved, $totalSubmission)
{
	if (!$isSolved) return 0;
	return ($totalSubmission - 1) * 20;
}
?>
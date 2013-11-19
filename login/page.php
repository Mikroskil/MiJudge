<?php
	include_once('../config.php');
	echo "<div class='content'>form login
	<div>
	<form>
		<fieldset>
			<label>Username</label>
			<div class='input-control text'><input type='text'></div>
			<label>Password</label>
			<div><input type='text'></div>
			<div><input type='submit' value='Login'></div>
		</fieldset>
	</form>
	</div>
	</div>";
	echo "<div class='content'>Test connect</div>";
	$connect = mysql_connect(DB_Server, DB_Login, DB_Password);
	mysql_select_db(DB_Name);
	echo "<div class='content'>";
	if ($connect)
		echo "Connected";
	else
		echo "Fail";
	echo "</div>";
	mysql_close($connect);
	/*
	try {
		$dbconn = new PDO('mysql:host='.DB_Server.';dbname='.DB_Name, DB_Login, DB_Password);
		echo "<div class='content'>Connected PDO</div>";
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
	//*/
?>
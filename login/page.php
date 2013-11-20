<?php
	include_once('../config.php');
	echo "<div class='span8'>
	<form>
		<fieldset>
			<legend>Login</legend>
			<lable>Username</lable>
			<div class='input-control text' data-role='input-control'>
				<input type='text' placeholder='type username'/>
				<button class='btn-clear'></button>
			</div>
			<lable>Password</lable>
			<div class='input-control password' data-role='input-control'>
				<input type='password' value='' placeholder='type password'/>
				<button class='btn-reveal'></button>
			</div>
			<input type='submit' value='Login'>
		</fieldset>
	</form>";
	echo "<div class='content'>Test connect</div>";
	$connect = mysql_connect(DB_Server, DB_Login, DB_Password);
	mysql_select_db(DB_Name);
	echo "<div class='content'>";
	if ($connect)
		echo "Connected";
	else
		echo "Fail";
	echo "</div>";
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
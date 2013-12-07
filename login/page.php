<?php
	$user = "";
	if (isset($_POST['username']))
		$user = $_POST['username'];
	echo "
	<div class=\"span8\">";
	if (isset($_POST['login']))
		echo "<h3 class=\"text-center fg-red\">Invalid Authentification</h3>";
	echo "
		<form method=\"post\">
			<fieldset>
				<legend>Login</legend>
				<div class=\"input-control text\" data-role=\"input-control\">
					<input type=\"text\" value=\"" . $user . "\" placeholder=\"Username\" name=\"username\"/>
					<button class=\"btn-clear\"></button>
				</div>
				<div class=\"input-control password\" data-role=\"input-control\">
					<input type=\"password\" value=\"\" placeholder=\"Password\" name=\"password\"/>
					<button class=\"btn-reveal\"></button>
				</div>
				<input type=\"submit\" value=\"Login\">
			</fieldset>
		</form>
	</div>";
?>
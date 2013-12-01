<?php
	echo "	<header class=\"bg-dark\">
		<div class=\"navigation-bar dark\">
			<div class=\"navigation-bar-content container\">
				<a href=\"" . FLD . "\" class=\"element\">Mikroskil Online Judge</a>
				<span class=\"element-divider\"></span>
				<a href=\"" . FLD . "\" class=\"element\">Home</a>
				<a href=\"" . FLD . "problemset\" class=\"element\">Problem Set</a>
				<a href=\"" . FLD . "contests\" class=\"element\">Contests</a>
				<a href=\"" . FLD . "rankings\" class=\"element\">Ranking</a>";
	if (isset($_SESSION['username']))
		echo "
				<a href=\"" . FLD . "logout\" class=\"element place-right\">Log Out</a>
				<a href=\"" . FLD . "profile\" class=\"element place-right\">" . htmlspecialchars($_SESSION["username"]) . "</a>";
	else
		echo "
				<a href=\"" . FLD . "register\" class=\"element place-right\">Register</a>
				<a href=\"" . FLD . "login\" class=\"element place-right\">Login</a>";
	echo "
			</div>
		</div>
	</header>";
?>
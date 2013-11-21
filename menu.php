			<div class='navigation-bar dark'>
				<div class="navigation-bar-content container">
					<a href="<?php echo FLD; ?>" class="element">Mikroskil Online Judge</a>
					<span class="element-divider"></span>
					<a href="<?php echo FLD; ?>" class="element">Home</a>
					<a href="<?php echo FLD; ?>problemset" class="element">Problem Set</a>
					<a href="<?php echo FLD; ?>contests" class="element">Contests</a>
					<a href="<?php echo FLD; ?>rankings" class="element">Ranking</a>
<?php
	if (isset($_SESSION['username']))
		echo "					<a href='" . FLD . "logout' class='element place-right'>Log Out</a>
					<a href='" . FLD . "profile' class='element place-right'>$_SESSION[username]</a>";
	else
		echo "					<a href='" . FLD . "register' class='element place-right'>Register</a>
					<a href='" . FLD . "login' class='element place-right'>Login</a>";
?>
				</div>
			</div>

<?php
	echo "
	<div class=\"span4\">";
	if (isAdmin) {
			echo "
			<nav class=\"sidebar\">
				<ul>
					<li class=\"title\">Admin Menu</li>
					<li><a href=\"\">Manage Contests</a></li>
					<li>
						<a class=\"dropdown-toggle\" href=\"#\">Manage Problems</a>
						<ul class=\"dropdown-menu\">
							<li><a href=\"\">Subitem 1</a></li>
							<li><a href=\"\">Subitem 2</a></li>
							<li><a href=\"\">Subitem 3</a></li>
						</ul>
					</li>
				</ul>
			</nav>";
	}
	echo "
	<div>Here should be news / profile</div>";
	if (isset($_SESSION['username']) && isset($_GET['problem']) && isset($_SESSION['hasProblem'])) {
		unset($_SESSION['hasProblem']);
		$_SESSION['probid'] = $_GET['problem'];
		echo "
		<div class=\"row\">";
		if (isset($_SESSION['result'])) {
			echo "
			<h2 class=\"fg-green\">$_SESSION[result]</h2>";
			unset($_SESSION['result']);
		}
		if (!isset($_GET['contest']))
			$_GET['contest'] = 0;
		echo "
			<form action=\"" . FLD . "upload-submission.php\" method=\"post\" enctype=\"multipart/form-data\">
			<fieldset>
				<legend>Submit Solution</legend>
				<div class=\"input-control file\">
					<input type=\"file\" name=\"submission\"/>
					<button class=\"btn-file\"></button>
				</div>
				<div class=\"input-control hidden\">
					<input type=\"hidden\" name=\"problem\" value=\"$_GET[problem]\"/>
				</div>
				<div class=\"input-control hidden\">
					<input type=\"hidden\" name=\"contest\" value=\"$_GET[contest]\"/>
				</div>
				<div class=\"input-control select\">
					<select name=\"language\">
						<option>C++</option>
						<option>Java</option>
					</select>
				</div>
				<input type=\"submit\" value=\"Submit\">
			</fieldset>
			</form>
		</div>";
	}
	echo "
	</div>";
/*
				<div>
					<div class=\"input-control radio default-style\">
						<label>
							<input type=\"radio\" name=\"language\" checked />
							<span class=\"check\"></span>
							C++
						</label>
					</div>
				</div>
				<div>
					<div class=\"input-control radio default-style\">
						<label>
							<input type=\"radio\" name=\"language\" />
							<span class=\"check\"></span>
							Java
						</label>
					</div>
				</div>*/
?>
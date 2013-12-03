<?php
	echo "
	<div class=\"span4\">";
	echo "
	Here should be news / profile";
	if (isset($_SESSION['username']) && isset($_GET['problem'])) {
		$_SESSION['probid'] = $_GET['problem'];
		echo "
		<div class=\"row\">";
		if (isset($_SESSION['result'])) {
			echo "
			<h2 class=\"fg-green\">$_SESSION[result]</h2>";
			unset($_SESSION['result']);
		}
		echo "
			<form action=\"" . FLD . "upload-submission.php\" method=\"post\" enctype=\"multipart/form-data\">
			<fieldset>
				<legend>Submit Solution</legend>
				<div class=\"input-control file\">
					<input type=\"file\" name=\"submission\"/>
					<button class=\"btn-file\"></button>
				</div>
				<div class=\"input-control hidden\">
					<input type=\"hidden\" name=\"probid\" value=\"$_GET[problem]\"/>
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
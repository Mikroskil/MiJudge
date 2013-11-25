<?php
	echo "
	<div class=\"span4\">";
	echo "
	Here should be news / profile";
	if (isset($_GET['problem'])) {
		echo "
		<div class=\"row\">
			<form action=\"" . FLD . "upload-submission.php\" method=\"post\" enctype=\"multipart/form-data\">
			<fieldset>
				<legend>Login</legend>
				<div class=\"input-control file\">
					<input type=\"file\" name=\"submission\"/>
					<button class=\"btn-file\"></button>
				</div>
				<input type=\"submit\" value=\"Submit\">
			</fieldset>
			</form>
		</div>";
	}
	echo "
	</div>
";
?>
<?php
	echo "
<div class='span8'>";
	if (isset($_SESSION['result'])) {
		echo "
		<h2 class=\"" . ($_SESSION['success'] ? "fg-green" : "fg-red") . "\">$_SESSION[result]</h2>";
		unset($_SESSION['result']);
		unset($_SESSION['success']);
	}
	echo "
	<form method='post'>
		<fieldset>
			<legend>Register</legend>
			<div class='input-control text' data-role='input-control'>
				<input type='text' placeholder='First Name' name='firstname'/>
				<button class='btn-clear'></button>
			</div>
			<div class='input-control text' data-role='input-control'>
				<input type='text' placeholder='Last Name' name='lastname'/>
				<button class='btn-clear'></button>
			</div>
			<div class='input-control text' data-role='input-control'>
				<input type='text' placeholder='Username' name='username'/>
				<button class='btn-clear'></button>
			</div>
			<div class='input-control password' data-role='input-control'>
				<input type='password' value='' placeholder='Password' name='password'/>
				<button class='btn-reveal'></button>
			</div>
			<div class='input-control password' data-role='input-control'>
				<input type='password' value='' placeholder='Confirm Password' name='confirmpassword'/>
				<button class='btn-reveal'></button>
			</div>
			<input type='submit' value='Register'>
		</fieldset>
	</form>
</div>
";
?>
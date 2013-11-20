<?php
	include_once('../config.php');
	echo "
	<div class='span8'>
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
		</form>
	</div>";
?>
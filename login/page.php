<?php
	echo "
	<div class='span8'>
		<form method='post'>
			<fieldset>
				<legend>Login</legend>
				<div class='input-control text' data-role='input-control'>
					<input type='text' placeholder='Username' name='username'/>
					<button class='btn-clear'></button>
				</div>
				<div class='input-control password' data-role='input-control'>
					<input type='password' value='' placeholder='Password' name='password'/>
					<button class='btn-reveal'></button>
				</div>
				<input type='submit' value='Login'>
			</fieldset>
		</form>
	</div>";
?>
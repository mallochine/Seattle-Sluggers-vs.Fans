<?php

$key = $_GET['key'];
$email = $_GET['email'];

?>

<html>

	Your account will be activated as soon as you submit a new password. <br />
	<form id='newpassword'>
		Enter a new password: <input type='password' id='password1' /><br />
		Confirm password: <input type='password' id='password2' />
		<input type='hidden' id='key' value='<?php echo $key; ?>' />
		<input type='hidden' id='email' value='<?php echo $email; ?>' />
		<input type='submit' />
	</form>
	
	<br />
	
	<div id='message'></div>
		
	<script src='../lib/jquery.js' type='text/javascript'></script>
	<script src='activation.js' type='text/javascript'></script>
	
</html>
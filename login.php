<html>
  <head>
    <title>Login Page</title>
  </head>
  <body>
  	<h1>Login Page</h1>
  	
    <?php 
      $error = $_GET['mess'];
			if ($error == 'loggedout')
				echo "<p class='error'>You are not logged in. You must first log in.</p>";
      if ($error == 'loginerror')
        echo "<p class='error'>Your login information is incorrect. Please try again.</p>";
      if ($error == 'loggedoutsuccessfully')
      	echo "<p>You are logged out successfully.</p>";
    ?>
    
		<form action='verify_login.php' method='post'>
			Username: <input type='text' name='username' id='username' /><br />
			Password: <input type='password' name='password' id='password' /><br />
			Account Type: 
				<input type='radio' name='account_type' value='user' checked/>Player
				<input type='radio' name='account_type' value='director' />Director
			<br />
			<input type='submit' value='Login' />
		</form>
		
		<br />
		<br />
		<b>Don't have an account? <a href='registration/player_registration.php'>Create one right now.</a></b>
		
		<script src='./lib/jquery.js'></script>
    <script>
    	$(document).ready(function(){
        $("#username").select();
    	});
    </script>
  </body>
</html>
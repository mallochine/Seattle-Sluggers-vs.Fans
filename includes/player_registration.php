<?php 
	/** Notes on how to improve this page
	 * add a CAPTCHA so that bots can't create players
	 */
	include('../headers/validate_director.php');
?>

<h3>Player Registration</h3>

<form id='registration_form'>
	First Name: <input type='text' id='firstname' /><br />
	Last Name: <input type='text' id='lastname' /><br />
	Rating: <input type='text' id='rating' /><br />
		<!-- the input field for the rating should not be a text field -->
	Email*: <input type='text' id='email' /><br />
	<input type='submit' value='Submit Registration Form' id='submit_form' />
	<!-- Add the CAPTCHA here -->
</form>

<br />
* when the player is successfully registered, a confirmation email will be sent.

<!-- assumes that the jquery library has already been loaded -->
<script>
	$(document).ready(function(){
		$("#firstname").select();
		$("#registration_form").submit(function(event){
			if(!IsFormValid()){
		  	$("#message").html('The form is invalid');
		  	return;
		  }
			$("#message").html("Processing request...");
		  $.post("./actions/register_player.php",{
		  		firstname: $("#firstname").val(),
		      lastname: $("#lastname").val(),
		      rating: $("#rating").val(),
		      email: $("#email").val(),
		      account_type: 'director'
		  	}, function(response){
		  		processResponse(response);
		  	}
		  );
		  event.preventDefault();
		});
	});

	function processResponse(response){
		if (response == 1)
			$("#message").html('Registering the player has been successful.');
		else
			$("#message").html(response);
	}

	/**
	 * looks at the registration form and returns true if valid
	 */
	function IsFormValid(){
		return true;
	}
</script>
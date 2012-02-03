<h3>Player Registration</h3>

* indicates a required field
<form id='registration_form'>
	First Name*: <input type='text' id='firstname' /><br />
	Last Name*: <input type='text' id='lastname' /><br />
	Rating: <input type='text' id='rating' /><br />
		<!-- the input field for the rating should not be a text field -->
	Email*: <input type='text' id='email' /><br />
	Confirm Email: <input type='text' id='email2' /><br />
	<input type='submit' value='Submit Registration Form' id='submit_form' />
	<!-- Add the CAPTCHA here -->
</form>

<br />
<div id='message'></div>

Important Message: the confirmation email which we send to you may end up in your junk folder. For support, please don't hesitate to email the webmaster at alexchesskid@msn.com.

<script src='../lib/jquery.js' type='type/javascript'></script>
<script>
	$(document).ready(function(){
		$("#firstname").select();
		$("#registration_form").submit(function(event){
			if(!IsFormValid()){
		  	$("#message").html('The form is invalid');
		  	return;
		  }
			$("#message").html("Processing request...");
		  $.post("../actions/register_player.php",{
		  		firstname: $("#firstname").val(),
		      lastname: $("#lastname").val(),
		      rating: $("#rating").val(),
		      email: $("#email").val(),
		      account_type: 'player'
		  	}, function(response){
		  		processResponse(response);
		  	}
		  );
		  event.preventDefault();
		});
	});

	function processResponse(response){
		//$("#message").html(response);
		if (response == 1)
			$("#message").html('The account has been successfully registered');
		else
			$("#message").html(response);
	}

	/**
	 * looks at the registration form and returns true if valid
	 */
	function IsFormValid(){
		if ($("#email").val() != $("#email2").val()){
			$("#message").html("The two emails do not match");
			return false;
		}
		return true;
	}
</script>
/**
 * this script assumes that the jquery library has already been embedded
 */

$(document).ready(function(){
	$("#password1").select();
	$("#newpassword").submit(function(event){
		if(validateForm() != 1){
			$("#message").html("Error: the passwords do not match");
			$("#password1").select();
			return;
		}
		$("#message").html("Processing...");
	    $.post("chgPlayerPassword.php",{
    			password: $("#password1").val(),
    			email: $("#email").val(),
    			key: $("#key").val()
     		}, function(response){
     			if (response == 1){
     				var message = "Your password was successfully changed, and your account is now active. ";
     				var message += "You should log into your account to sign up for a match.";
     				$("#message").html(message);
     			} else
     				$("#message").html(response);
     		}
     	);
		event.preventDefault();
	});
})

function validateForm(){
	var password1 = $("#password1").val();
	var password2 = $("#password2").val();
	
	if (password1 == password2)
		return true;
	else
		return false;
}
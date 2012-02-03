<?php
	include('../headers/validate_director.php');
?>

<h3>Creating a Match</h3>

<form id='initializeMatch'>
	Match Name: <input type='text' id='match_name' /><br />
	Username: <input type='text' id='username' /><br />
	Password: <input type='password' id='password' /><br />
	<input type='submit' id='button' value='Initialize Match Setup' /><br />
</form>

<script>
  $(document).ready(function(){
		$("#match_name").select();
    $("#initializeMatch").submit(function(event){
   	 	if(!IsFormValid()){
   	  	alert('The form is invalid');
   	  	return;
   	 	}
     	$.post("./actions/iniChessMatch.php",{
    		match_name: $("#match_name").val(),
    		username: $("#username").val(),
    		password: $("#password").val()
     	  	}, function(response){
       	processResponse(response);
     	});
     	event.preventDefault();
    });
  });
  
  function processResponse(response){
	  var status = response.substring(0,7);
	  var matchid = response.substring(7,response.length);
		if (status == 'success'){
			$("#formwindow").html("Loading...please wait");
			var redirect_url = "./includes/SettingNumBoards.php?matchid=" + matchid;
	  	$("#formwindow").load(redirect_url);
		} else {
	  	$("#formwindow").html(response);
		}
  }
  
  function IsFormValid(){
		//check to see that all the numbers in the form is right
		return true;
  }
</script>
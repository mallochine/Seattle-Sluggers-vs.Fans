<?php 

include('../headers/validate_player.php');

?>

<h2>Sign up for a Match</h2>

<form id='registration_form'>
	Match ID: <input type='text' id='matchid' value='' />
	<input type='submit' value='Submit' />
	<input type='hidden' id='playerid' value='<?php echo $_SESSION['playerid'];?>' />
</form>

<script src='../lib/jquery.js'></script>
<script>
	$(document).ready(function(){
		$("#matchid").select();
		$("#registration_form").submit(function(event){
			event.preventDefault();
			$("#message").html("Loading...");
			if (!isFormValid()){
				$("#message").html("Your form is invalid");
				return;
			}
			
			$.post("./player_actions/process_simulregistration.php", {
				playerid: $("#playerid").val(),
				matchid: parseFloat($("#matchid").val())
			}, function(response){
				$("#message").html(response);
			});
		});
	});

	// sooner or later I'll get around to developing this...
	// lol I never got around to developing it. oh well.
	function isFormValid(){
		return true;
	}
</script>
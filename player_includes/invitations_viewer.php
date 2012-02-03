<?php
	include_once('../lib/player_fns.php');
	include_once('../lib/database_fns.php');
	include_once('../headers/validate_player.php');
	
	//echo "<script>alert('".$_SESSION['playerid']."');</script>"; // a test for the player's id
?>

<h2>Your Invitations</h2>

<form id='invitations'>
	<?php dispInvitations($_SESSION['playerid']); ?>
	<!-- should have a control panel of actions...just like phpMyAdmin -->
	<br />
	<i>With selected:</i> 
		<select id='action'>
			<option value='accept'>Accept</option>
			<option value='decline'>Decline</option>
		</select>
	<input type='hidden' id='playerid' value='<?php echo $_SESSION['playerid'];?>' />
	<input type='submit' value='Go' />
</form>

<script src='../lib/jquery.js' type='text/javascript'></script>
<script>
	$(document).ready(function(){
		$("#matchid0").select();
		$("#invitations").submit(function(event){
			event.preventDefault();
			if(!isFormValid()){
				$("#message").html("Error: none of the boxes are checked");
				return;
			}
			var params = getParams();
			$.post("./player_actions/rsvp_invitation.php", params, function(response){
				if (response == 1){
					$("#message").html("Successful!");
				} else {
					$("#message").html(response);
				}
			});
		});
	});

	/**
	 	Parameters needed:
	 	-all matchids that were selected
	 	-type of action: accept or decline
	 	-the number of matchids (given by numChecked)
	 	-playerid
	 */
	function getParams(){
		var params = '';
		var numInvitations = $("#numInvitations").val();
		var numChecked = 0;

		for (var i=0; i<numInvitations; i++){
			if ($("#matchid"+i).attr('checked')){
				params += "matchid" + numChecked + "=" + $("#matchid"+i).val() + "&";
				numChecked++;
			}
		}

		params += "numChecked="+numChecked+"&";
		params += "action="+$("#action").val()+"&";
		params += "playerid="+$("#playerid").val();
		return params;
	}

	function isFormValid(){
		// check to see if any of the checkboxes are checked
		var numInvitations = $("#numInvitations").val();
		for (i=0; i<numInvitations; i++){
			if ($("#matchid"+i).attr('checked')){
				return true;
			}
		}
		return false;
	}
</script>
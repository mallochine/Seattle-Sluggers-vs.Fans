<?php
  include('../lib/match_wizard_fns.php');
	include_once('../headers/validate_director.php');

  $matchid = $_REQUEST['matchid'];
  $numBoards = $_REQUEST['numBoards'];
  if (empty($numBoards)){
  	$numBoards = getNumBoards($matchid);
  }
  $directorid = $_SESSION['directorid'];
  if (!checkMatchStatus($matchid, 2))
  	exit("Error: the match is not at this stage.");
	if (!IsDirector_MatchTrue($matchid, $directorid))
		exit("Error: you are not the director of this match");
?>

<h3>Entering Information for each Board</h3>

<form id='boardconfig'>
	<?php 
		dispBoardInfoForm($numBoards); 
		echo "<input type='hidden' id='matchid' value='$matchid' />"; 
	?>
	<input type='submit' value='Submit Board Information' />
</form>

<!-- 
	<iframe id="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
-->
<script src='../lib/jquery.js' type='text/javascript'></script>
<script>
	$(document).ready(function(){
		$("#playerid1").select();
		$("#boardconfig").submit(function(event){
			if(!IsFormValid()){
			  $("#message").html('The form is invalid');
			  return; 
			}
			var params = getParams();
			$("#message").html("Processing request...");
			$.post("./actions/configChessMatch.php", params, function(response){
					if (response == 1){
						$("#message").html("Successful!");
						$("#formwindow").load("./includes/view_rsvps.php?matchid="+$("#matchid").val());
					} else {
						$("#message").html(response);
					}
				}
			);
			event.preventDefault();
		});
	});

	function getParams(){
		var numBoards = $("#numBoards").val();
		var matchid = $("#matchid").val();
		var params = '';

		for (var i=1; i<=numBoards; i++){
			params += "playerid"+i+"=" + $("#playerid"+i).val() + "&";
			if ($("#color"+i).attr('checked') == true)
				params += "color"+i+"=" + $("#color"+i).val() + "&";
		}
		params += "numBoards=" + numBoards + "&";
		params += "matchid=" + matchid;

		return params;
	}
	
	function IsFormValid(){
		// check whether all the required input fields are filled out
		// make sure that the ASCII character & isn't there
		return true;
	}
</script>
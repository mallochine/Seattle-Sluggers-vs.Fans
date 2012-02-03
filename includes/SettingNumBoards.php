<?php
	include('../headers/validate_director.php');
  
  $matchid = $_GET['matchid'];
  $directorid = $_SESSION['directorid'];
  if (!checkMatchStatus($matchid, 1))
  	exit("Error: the match is not at this stage.");
	if (!IsDirector_MatchTrue($matchid, $directorid))
		exit("Error: you are not the director of this match");
  $matchname = getMatchName($matchid); 
?>

<h3>Setting the Number of Boards</h3>

<form id='configMatch'>
	Match Name: <?php echo $matchname; ?><br />
	Number of Boards: <input type='text' id='NumBoards' maxlength='2' /><br />
	<input type='submit' id='button' value='Continue' /><br >
	<input type='hidden' id='matchid' value='<?php echo $matchid; ?>'/>
</form>

<script>
	$(document).ready(function(){
		$("#NumBoards").select();
		$("#configMatch").submit(function(event){
			if(!IsFormValid()){
				alert("The number of boards must be an integer");
				return;
			}
	    $.post("./actions/setNumBoards.php",{
	    		matchid: $("#matchid").val(),
	    		numBoards: $("#NumBoards").val()
	     	}, function(response){
	    		processResponse(response);
	    	}
	    );
			event.preventDefault();
		});
	});

	function processResponse(response){
		if (response == 1){
			var url = "./includes/configBoards.php?matchid=" + $("#matchid").val();
			$("#formwindow").html("Loading...please wait");
		  $("#formwindow").load(url);
		} else {
			$("#formwindow").html(response);
		}
	}

	function IsFormValid(){
		var test = parseInt($("#NumBoards").val());
		if (test != $("#NumBoards").val())
			return false;
		else 
			return true;
	}
</script>
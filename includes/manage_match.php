<?php

include_once('../headers/validate_director.php');
include_once('../lib/match_wizard_fns.php');

$matchid = $_REQUEST['matchid'];

?>

<h2>Match Control Panel</h2>

<?php 
	dispMatchControlPanel($matchid);
	echo "<input type='hidden' id='matchid' value='$matchid' />"; 
?>

<script src='./lib/jquery.js' type='text/javascript'></script>
<script>
	$(document).ready(function(){
		$("#submit_action").click(function(event){ 
			event.preventDefault();
			if(!isFormValid())
				return;
			var params = getParams();
			$("#message").html("Processing request...");
			$.post("./actions/contChessMatch.php",params,function(response){
				if (response == 1)
					$("#message").html("The action requested has been successfully completed");
				else
					$("#message").html(response);
			});
		});

		$("#director_message_form").submit(function(event){
			event.preventDefault();
			if (!isDirectorMessValid()){
				$("#message").html("Error: the message that you have for the director is invalid");
				return;
			}
			$("#message").html("Processing request...");
			$.post("./actions/updateDirectorMessage.php",{
				director_message: $("#director_message").val(),
				matchid: $("#matchid").val(),
				boardid: $("#message_board").val()
			}, function(response){
				$("#message").html(response);
			});
		});
	});

	function getParams(){
		var params = '';
		var numChecked = 0;

		for (var i=0; i<$("#numBoards").val(); i++){
			if ($("#boardid"+i).attr("checked")){
				params += "boardid"+numChecked+"="+$("#boardid"+i).val()+"&";
				numChecked++;
			}
		}
		params += "numChecked="+numChecked+"&";
		params += "action="+$("#action").val()+"&";
		params += "matchid="+$("#matchid").val()+"&";
		params += "numBoards="+$("#numBoards").val();
		//alert(params);
		return params;
	}

	function isDirectorMessValid(){
		// in the future, will have better sanitization functions......
		return true;
	}
	
	function isFormValid(){
		// check to see if any of the checkboxes are checked
		var numBoards = $("#numBoards").val();
		for (i=0; i<numBoards; i++){
			if ($("#boardid"+i).attr('checked')){
				return true;
			}
		}
		$("#message").html("Error: none of the checkboxes are checked");
		return false;
	}
</script>
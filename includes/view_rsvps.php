<?php 

include_once('../headers/validate_director.php');
include_once('../lib/match_wizard_fns.php');

if (isset($_GET['matchid']))
	$matchid = $_GET['matchid'];
else
	exit("A match was not requested");
	
?>

<h3>Match Launchpad</h3>

<?php dispPlayersRSVPS($matchid);?>

<input type='hidden' id='matchid' value='<?php echo $matchid;?>' />
<input type='button' id='launch' value='Launch Match' />

<script src='./lib/jquery.js'></script>
<script>
	$(document).ready(function(){
		$("#launch").click(function(event){
			event.preventDefault();
			$("#message").html("Processing request...");
			if (!isFormValid()){
				$("#message").html("There is something wrong with the HTML form...");
				return;
			}
			var params = 'matchid='+$("#matchid").val();
			$.post("./actions/launch_match.php", params, function(response){
					if (response == 1){
						link = "playmatch.php?match="+$("#matchid").val();
						var message = "Match is succesfully launched! You can view it here:<br />";
						message += "<a href='"+link+"'>"+link+"</a>";
						$("#message").html(message);
					} else
						$("#message").html(response);
				}
			);
		});
	});

	/**
	 * later, will build on this function
	 */
	function isFormValid(){
		return true;
	}
</script>
<?php
/**
 * include an option to delete the matches being created.
 */

	include_once('../lib/database_fns.php');
  include_once('../lib/match_wizard_fns.php');
  include_once('../headers/validate_director.php');
  
  $directorid = $_SESSION['directorid'];
  echo "<h3>Matches Being Created</h3>";
  dispMatchesBeingCreated($directorid);
?>

<script>
	$(document).ready(function(){
		$("#MatchWizard a[name=SettingNumBoards]").click(function(event){
			dispLoadingMessage();
			$("#formwindow").load('./includes/SettingNumBoards.php?matchid='+this.id);
			event.preventDefault();
		});

		$("#MatchWizard a[name=ConfigBoards]").click(function(event){
			dispLoadingMessage();
			$("#formwindow").load('./includes/configBoards.php?matchid='+this.id);
			event.preventDefault();
		});
		
		$("#MatchWizard a[name=view_rsvps]").click(function(event){
			dispLoadingMessage();
			$("#formwindow").load('./includes/view_rsvps.php?matchid='+this.id);
			event.preventDefault();
		});
	});
</script>
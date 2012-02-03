<?php 

include_once('../headers/validate_director.php');
include_once('../lib/database_fns.php');
include_once('../lib/match_wizard_fns.php');

?>

<h2>Matches In Progress</h2>

<div id='matchesinprogress'>
	<?php dispMatchesInProgress($_SESSION['directorid']);?>
</div>

<script src='./lib/jquery.js'></script>
<script>
	$(document).ready(function(){
		$("#matchesinprogress a[name=matchid]").click(function(event){
			event.preventDefault();
			dispLoadingMessage();
			alert(this.value);
		});
	});
</script>
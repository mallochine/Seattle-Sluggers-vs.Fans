<?php 
	include_once('./lib/database_fns.php');
  include_once('./lib/user_auth_fns.php');
  header("Cache-control: no-cache");
  
  session_start();
  if(!CheckLoginValid('director'))
  	echo "<script>window.location.href='logout.php'</script>";
  	
  // get the director id
  $directorid = $_SESSION['directorid'];
  if (!isset($directorid)){
  	$username = $_SESSION['username'];
  	if (isset($username)){
  		$directorid = getDirectorID($username);
  	} else {
  		// log out, because neither the username nor the director id are set!
  		header("location: logout.php");
  	}
  }
  
?>

<html>
  <head>
    <title>Match Control Panel</title>
  </head>
  
  <body>
  	<h1>Control Panel</h1>
  	<input type='button' id='logout' value='Log out' />
  	<input type='button' id='home' value='Home' /><br />
  	<input type='button' id='registerPlayer' value='Register a Player' />
  	<input type='button' id='browsePlayers' value='Browse existing catalog of players' /><br />
  	<input type='button' id='loadMatchWizard' value='Create a Match!' />
  	<input type='button' id='loadMatchesBeingCreated' value='Load Matches Being Created' />
  	
  	<br />
  	<input type='button' id='loadCurrentMatches' value='Load Matches in Progress' /><br />
  	
  	<div id='message'></div>
  	
  	<br />
  	<div id='formwindow'>
  		Dream up something great. Then do it.
  	</div>
  </body>
  
  <!-- <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js'></script> -->
  <script src='./lib/jquery.js'></script>
  <script>
	$(document).ready(function(){

			<?php 
				if($_GET['url'] == 'managematch'){
					$matchid = $_GET['matchid'];
					echo "$(\"#formwindow\").load('./includes/manage_match.php?matchid=$matchid');";
				} else
					echo "$(\"#formwindow\").load('./includes/director_home.php');";
			?>
		$("#browsePlayers").click(function(event){
			event.preventDefault();
			dispLoadingMessage();
			dispErrorMessage();
		});
		
		$("#registerPlayer").click(function(event){
			event.preventDefault();
			dispLoadingMessage();
			$("#formwindow").load('./includes/player_registration.php');
		});
		
  	$("#loadMatchWizard").click(function(event){
  		event.preventDefault();
  		dispLoadingMessage();
   		$("#formwindow").load('./includes/iniChessMatch.php');
    });
  	
  	$("#loadMatchesBeingCreated").click(function(event){
  		event.preventDefault();
  		dispLoadingMessage();
  		$("#formwindow").load('./includes/loadMatchesBeingCreated.php');
  	});

  	$("#loadCurrentMatches").click(function(event){
			event.preventDefault();
  		dispLoadingMessage();
  		$("#formwindow").load('./includes/loadCurrentMatches.php');
  	});

  	$("#logout").click(function(event){
  	  window.location.href='logout.php';
  	});

  	$("#home").click(function(event){
  		event.preventDefault();
    	dispLoadingMessage();
  	  $("#formwindow").load('./includes/director_home.php');
  	});
	});

	function dispLoadingMessage(){
		$("#message").html("");
		$("#formwindow").html("Loading...please wait");
	}

	function dispErrorMessage(){
		$("#message").html("");
		$("#formwindow").html("Sorry, this feature is not available yet");
	}
		
  </script>
</html>
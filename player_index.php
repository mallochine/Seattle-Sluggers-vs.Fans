<?php 
	include_once('./lib/database_fns.php');
  include_once('./lib/user_auth_fns.php');
  header("Cache-control: no-cache");

	session_start();
	if(!CheckLoginValid('user'))
		"<script>window.location.href='logout.php';</script>";
	
	/* I later didn't find this piece of code very useful
  // get the player id
  $playerid = $_SESSION['playerid'];
  if (!isset($playerid)){
  	$username = $_SESSION['username'];
  	if (isset($username)){
  		$playerid = getPlayerID($username);
  	} else {
  		// log out, because neither the username nor the director id are set!
  		header("location: logout.php");
  	}
  }
  */
?>

<html>
	<h1>Control Panel</h1>

	<!-- This is the control panel -->
	<input type='button' id='logout' value='Logout' />
	<input type='button' id='home' value='Home' /><br />
	<input type='button' id='upload_photo' value='Upload a Profile Picture' /><br />
	<input type='button' id='edit_profile' value='Edit your account information' /><br />
	<input type='button' id='signup_match' value='Sign up to play in a match' /><br />
	<input type='button' id='view_invitations' value='View your invitations' /><br />
	<input type='button' id='view_matches' value='View the matches you are playing in right now' /><br />

	<br />
	<div id='message'></div><br />
	<div id='formwindow'></div>

	<script src='./lib/jquery.js'></script>
	<script>
		$(document).ready(function(){
			dispLoadingMessage();
			$("#formwindow").load("./player_includes/player_home.php");

			$("#signup_match").click(function(event){
				event.preventDefault();
				dispLoadingMessage();
				$("#formwindow").load("./player_includes/simul_registration.php");
			});

			$("#home").click(function(event){
				event.preventDefault();
				dispLoadingMessage();
				$("#formwindow").load("./player_includes/player_home.php");
			});
			
			$("#upload_photo").click(function(event){
				event.preventDefault();
				dispLoadingMessage();
				dispErrorMessage();
			});
		
			$("#edit_profile").click(function(event){
				event.preventDefault();
				dispLoadingMessage();
				dispErrorMessage();
			});

			$("#view_invitations").click(function(event){
				event.preventDefault();
				dispLoadingMessage();
				$("#formwindow").load("./player_includes/invitations_viewer.php");
			});

			$("#view_matches").click(function(event){
				event.preventDefault();
				dispLoadingMessage();
				$("#formwindow").load("./player_includes/view_matches.php");
			});

			$("#logout").click(function(event){
				window.location.href='logout.php';
			});
		});

		function dispLoadingMessage(){
			$("#message").html("");
			$("#formwindow").html("Loading...please wait");
		}

		function dispErrorMessage(){
			$("#message").html("");
			$("#formwindow").html("Sorry, this feature is not yet available.");
		}
	</script>
</html>
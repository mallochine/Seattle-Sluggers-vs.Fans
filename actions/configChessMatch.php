<?php 

include_once('../lib/database_fns.php');

/**
 * accessed by configBoards.php
 *
 * the goal of this script is to put the players into the database
 * and to send a confirmation email
 */

$matchid = $_POST['matchid'];
$numBoards = $_POST['numBoards'];
$boardinfo = array();

// this is the email message
$url = "http://www.alexguo.info/match_portal/login.php";
$message .= "Hello, a director has invited you to play in his match. ";
$message .= "To view his invitation, log into your account at $url.";

// one by one, insert the information into the database
for ($i=1; $i<=$numBoards; $i++){	
	$boardinfo['playerid'] = $_POST['playerid'.$i];
	$boardinfo['desc'] = $_POST['desc'.$i];
	if (isset($_POST['color'.$i]))
		$boardinfo['color'] = 1;
	else
		$boardinfo['color'] = 0;
	
	// register the player into match_players
	$result = insertMatchPlayer($matchid, $boardinfo['playerid']);
	if ($result != 1)
		echo $result;

	// register the player into match_boards$i
	$result = insertBoard($matchid, $i, $boardinfo);
	if ($result != 1)
		echo $result; // display an error message
		
	// email a confirmation email to the players
	$email = getPlayerInfo($boardinfo['playerid'], 'email');
	if(!mail($email, "A director has invited you to play in a match", $message))
		exit("Could not send an email to $email");
}

if(chgMatchInfo($matchid, 'status', '400')) // indicating that the match is ready to launch
	exit(true);
else
	exit("Error: could not change the status of the match.");

?>
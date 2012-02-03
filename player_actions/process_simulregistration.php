<?php 

/**
 * register the user to play in every board
 * 
 * note to self: need to create restrictions on which users can sign up to play in the match
 */

$matchid = $_POST['matchid'];
$playerid = $_POST['playerid'];

include_once('../lib/database_fns.php'); 

// check to see whether the user is actually playing in the match
if(isPlayerInMatch($matchid, $playerid))
	exit("Error: you are already playing in the match");
if(isUserInMatch($matchid, $playerid))
	exit("Error: you are already registered in this match.");

// check to see whether the matchid requested exists
if(!isMatchRegistered($matchid))
	exit("Error: the match that you requested is not in our database");

// insert the user into the match_players table
insertMatchPlayer($matchid, $playerid);
chgMatch_PlayersStatus($matchid, $playerid, '700');

// now sign the user up for every board in the match
if(insertUserIntoMatch($matchid, $playerid)){
	echo "You are successfully signed up to play in the match. ";
	$link = "http://".$_SERVER['SERVER_NAME']."/match_portal/playmatch.php?match=$matchid";
	echo "You can play in the match <a href='$link'>right now</a>.";
} else 
	exit("Error: could not insert the user into the match.");

?>
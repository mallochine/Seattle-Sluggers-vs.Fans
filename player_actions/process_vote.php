<?php 

/**
 * same script is used for both the public and the player playing the game
 */

$matchid 		 = $_POST['matchid'];
$boardid 		 = $_POST['boardid'];
$playerid 	 = $_POST['playerid'];
$firstsquare = $_POST['firstsquare'];
$lastsquare  = $_POST['lastsquare'];

include_once('../lib/database_fns.php');
if (!include_once('../matches/match'.$matchid.'/legalmoves'.$boardid.'.php'))
	exit("Error: could not stream the possible legal moves");
if (!include_once('../matches/match'.$matchid.'/board_settings'.$boardid.'.php'))
	exit("Error: could not stream the board settings");
	
$isPlayerInMatch = isPlayerInMatch($matchid, $playerid);
if (!$isPlayerInMatch){
	// check whether the player is actually supposed to vote on this board
	/** for the slugger match, we are disabling this feature
	if (!isUserInMatch($matchid, $playerid)) 
		exit("Error: you cannot vote in this match. Go to your account to sign up.");
	**/

	/** We want the match open to the public, so this is not possible
	// check whether the player has voted
	if (getPlayerVoteStatus($matchid, $boardid, $playerid) == 606) // the player has already voted
		exit("Error: you have already voted on this board.");
		**/
	// check whether the ip address is entered into the database
	
	// check whether it is the player's turn to vote
	if ($board_settings['color'] == getBoardInfo($matchid, $boardid, 'color'))
		exit("Error: it is not your turn to play a move");
} else {
	// check whether the player is actually supposed to vote on this board
	if (getBoardInfo($matchid, $boardid, 'playerid') != $playerid)
		exit("Error: you are not supposed to vote on this board.");

	// check whether the player has voted
	if (getMatch_PlayersStatus($matchid, $playerid) == 606) // the player has already voted
		exit("Error: you have already voted on this board.");
	
	// check whether it is the player's turn to vote
	if ($board_settings['color'] != getBoardInfo($matchid, $boardid, 'color'))
		exit("Error: it is not your turn to play a move");
}
	
// loop through legalmoves array, checking for whether the move is in the array
for ($i=0; $i<$numlegalmoves; $i++){
	if($legalmoves[$i][0] == $firstsquare && $legalmoves[$i][1] == $lastsquare){
		// enter the vote into the database
		if (isMoveEntered($matchid, $boardid, $firstsquare, $lastsquare)){
			$result = increaseVote($matchid, $boardid, $firstsquare, $lastsquare);
			if (!$result)
				echo $result;
		} else {
			$result = registerMove($matchid, $boardid, $firstsquare, $lastsquare);
			if (!$result) 
				echo $result;
		}
		// change the player's vote status to 'voted' (606)
		if ($isPlayerInMatch){
			chgMatch_PlayersStatus($matchid, $playerid, 606);
		} else {
			// this line is not needed because the players voting will not be registered
			//chgPlayerVoteStatus($matchid, $boardid, $playerid, 606);
			// insert the ip address into the database
		}
		exit("Your vote has been successfully processed.");
		break;
	}
}

echo "The move that you submitted is not legal."; 

?>
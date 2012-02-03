<?php

include_once("./lib/user_auth_fns.php");
include_once("./lib/player_fns.php");
include_once("./lib/database_fns.php");
include_once("./lib/general.inc.php");

session_start();

$isLoggedIn = false;
if (CheckLoginValid('user')){
	$isLoggedIn = true;
}

$matchid = $_GET['match'];
$boardid = $_GET['board'];
$playerid = $_SESSION['playerid'];

?>

<div id='include_html' align='center'>
	<?php 
		if (empty($matchid)){
			exit("ERROR: please request a match <br />");
		} else if (empty($boardid)){
			echo "<h1>".getMatchInfo($matchid, 'matchname')."</h1>";
    	dispMatchBoards($matchid);
    	exit;
		} else {
			include_once('./matches/match'.$matchid.'/board'.$boardid.'.php');
			include_once('./matches/match'.$matchid.'/board_settings'.$boardid.'.php');
			include_once('./matches/match'.$matchid.'/board_array'.$boardid.'.php');
			
			// display the most recent move played
			if ($lastmove[0] != 0){
				$piece_letter = PieceCodeToLetter($lastmove[0]);
				$firstsquare = NumToAlgebraicSquare($lastmove[1]);
				$lastsquare = NumToAlgebraicSquare($lastmove[2]);
				echo "<span align='center'>Last move played: $piece_letter$firstsquare-$lastsquare</span><br />";
			}
			
			// display the top two most popular moves voted
			$firstmove = getVoteRankedNumber($matchid, $boardid, 1);
			if ($firstmove != false){
				$firstmove_firstsquare = NumToAlgebraicSquare($firstmove[0]);
				$firstmove_lastsquare = NumToAlgebraicSquare($firstmove[1]);
				$firstmove_firstcoord = NumToCoordinate($firstmove[0]);
				$piece_code = $chessboard[$firstmove_firstcoord[0]][$firstmove_firstcoord[1]];
				$piece_letter = PieceCodeToLetter($piece_code);
				$firstmove_notation = "$piece_letter$firstmove_firstsquare-$firstmove_lastsquare";
				echo "<span align='center'>Most popular move voted: $firstmove_notation</span><br />";
			}
			$secondmove = getVoteRankedNumber($matchid, $boardid, 2);
			if ($secondmove != false){
				$secondmove_firstsquare = NumToAlgebraicSquare($secondmove[0]);
				$secondmove_lastsquare = NumToAlgebraicSquare($secondmove[1]);
				$secondmove_firstcoord = NumToCoordinate($secondmove[0]);
				$piece_code = $chessboard[$secondmove_firstcoord[0]][$secondmove_firstcoord[1]];
				$piece_letter = PieceCodeToLetter($piece_code);
				$secondmove_notation = "$piece_letter$secondmove_firstsquare-$secondmove_lastsquare";
				echo "<span align='center'>Second most popular move voted: $secondmove_notation</span><br />";
			}
			
			// echo some important hidden parameters
			echo "<input type='hidden' id='matchid' value='$matchid' />";
			echo "<input type='hidden' id='boardid' value='$boardid' />";
			echo "<input type='hidden' id='playerid' value='$playerid' />";
		}
	?>
</div>

<span align='left'>
	<?php 
		// display the director's message
		$director_message = getBoardInfo($matchid, $boardid, 'director_message');
		if (empty($director_message)){
			echo "The director has not posted a message this time.";
		} else {
			echo "Director's Message: $director_message.";
		}
	?>
</span><br /><br />

<div id='message'></div>

<?php 
	if ($isLoggedIn){
		// conduct error checking
		include_once('./matches/match'.$matchid.'/board_settings'.$boardid.'.php');
		$isPlayerInMatch = isPlayerInMatch($matchid, $playerid);
		if (!$isPlayerInMatch){
			// check whether the player is actually supposed to vote on this board
			/** However, let's have the match be open to everybody
			if (!isUserInMatch($matchid, $playerid)){
				echo "You cannot vote in this match. ";
				echo "To sign up, go to your <a href='player_index.php'>account</a> ";
				echo "and request to play in a match with id $matchid.";
				exit;
			}
			**/

			/** I don't want this feature of checking whether the player has already voted
			// check whether the player has voted
			if (getPlayerVoteStatus($matchid, $boardid, $playerid) == 606) // the player has already voted
				exit("You have already voted on this board.");
			**/
		
			/* the only error checking we'll have is based on the IP Address */
			
	
			// check whether it is the player's turn to vote
			if ($board_settings['color'] == getBoardInfo($matchid, $boardid, 'color'))
				exit("It is not your turn to play a move.");
		} else {
			// check whether the player is actually supposed to vote on this board
			if (getBoardInfo($matchid, $boardid, 'playerid') != $playerid)
				exit("You are not supposed to vote on this board.");

			// check whether the player has voted
			if (getMatch_PlayersStatus($matchid, $playerid) == 606) // the player has already voted
				exit("You have already voted on this board.");

			// check whether it is the player's turn to vote
			if ($board_settings['color'] != getBoardInfo($matchid, $boardid, 'color'))
				exit("It is not your turn to play a move.");
		}
		
		include('./player_includes/voting_form.php'); // this is only displayed if it passes all the above tests
	} else {
		echo "To vote on this match, please log in and <a href=''>register</a> for this match. ";
		echo "This is done to prevent cheating. ";
		echo "Thanks for your cooperation.";
	}
?>
<?php 

/**
 * Assumptions
 * -the directory to the match is already created
 * -the move that will be played is already checked for legality
 */

include_once('../lib/database_fns.php');
include_once('../lib/DispChessboard.inc.php'); 
include_once('../lib/LegalMoveGenerator.class.php'); 
include_once('../lib/general.inc.php');
include_once('../lib/database_fns.php');

//get matchid and the requested chess position from the post
$numChecked = $_POST['numChecked'];
$matchid = $_POST['matchid'];
$numBoards = $_POST['numBoards'];
$boardid = array();
for ($i=0; $i<$numBoards; $i++){
	$boardid[$i] = $_POST['boardid'.$i];
}

for ($i=0; $i<$numChecked; $i++){
	$boardid = $boardid[$i]; 
	
	// check for whether there are any votes in the table
	if(!hasVotesOnBoard($matchid, $boardid)){
		echo "Error: Board $boardid does not have any votes in it.";
		continue;
	}

	// get the current board_array, board_settings, legal moves
	include_once('../matches/match'.$matchid.'/board_array'.$boardid.'.php');
	include_once('../matches/match'.$matchid.'/board_settings'.$boardid.'.php');
	include_once('../matches/match'.$matchid.'/legalmoves'.$boardid.'.php');
	
	// get the next move to play
	$NextMovePlayed = getNewMove($matchid, $boardid);
	$firstsquare = NumToCoordinate($NextMovePlayed[0]);
	$lastsquare = NumToCoordinate($NextMovePlayed[1]);
	
	// alter the board_array
	$firstsquare_piececode = $chessboard[$firstsquare[0]][$firstsquare[1]];
	$chessboard[$firstsquare[0]][$firstsquare[1]] = 0;
	$chessboard[$lastsquare[0]][$lastsquare[1]] = $firstsquare_piececode;
	$piece = abs($firstsquare_piececode);
	
	// alter the board_settings
	// handle the rooks
	if ($firstsquare_piececode == 4){
		if ($NextMovePlayed[0] == 0) // the starting square, the qr
			$board_settings['wqr'] = 1;
		if ($NextMovePlayed[0] == 7) // white's right-hand corner, the kr
			$board_settings['wkr'] = 1;
	}
	if ($firstsquare_piececode == -4){
		if ($NextMovePlayed[0] == 63) // black's left-hand corner, the kr
			$board_settings['bkr'] = 1;
		if ($NextMovePlayed[0] == 56) // black's right-hand corner, the qr
			$board_settings['bqr'] = 1;
	}
	
	// handle the kings
	if ($firstsquare_piececode == 6){ // the piece is a king
		$board_settings['wkingindex'] = CoordinateToNum($lastsquare[0], $lastsquare[1]);
		$board_settings['wk'] = 1;
	}
	if ($firstsquare_piececode == -6){
		$board_settings['bkingindex'] = CoordinateToNum($lastsquare[0], $lastsquare[1]);
		$board_settings['bk'] = 1;
	}
	
	// handle castling
	if ($piece == 6 && $NextMovePlayed[0] == 4 && $NextMovePlayed[1] == 6){ // white k-side castling
		$chessboard[7][0] = 0;
		$chessboard[5][0] = 4;
		$board_settings['wkr'] = 1;
	}
	if ($piece == 6 && $NextMovePlayed[0] == 4 && $NextMovePlayed[1] == 2){ // white q-side castling
		$chessboard[0][0] = 0;
		$chessboard[3][0] = 4;
		$board_settings['wqr'] = 1;
	}
	if ($piece == 6 && $NextMovePlayed[0] == 60 && $NextMovePlayed[1] == 62){ // black k-side castling
		$chessboard[7][7] = 0;
		$chessboard[5][7] = -4;
		$board_settings['bkr'] = 1;
	}
	if ($piece == 6 && $NextMovePlayed[0] == 60 && $NextMovePlayed[1] == 58){ // black q-side castling
		$chessboard[0][7] = 0;
		$chessboard[3][7] = -4;
		$board_settings['bqr'] = 1;
	}
	
	if ($board_settings['color'] == 1)
		$board_settings['color'] = 0;
	else
		$board_settings['color'] = 1;
	// also need to handle pawn promotions....

	//set up the legal moves generator
	$LastMovePlayed = array();
	$LastMovePlayed[0] = $firstsquare_piececode;
	$LastMovePlayed[1] = $NextMovePlayed[0];
	$LastMovePlayed[2] = $NextMovePlayed[1];
	
	if($board_settings['wk'] == 1)
		$IsWKMoved = true;
	else
		$IsWKMoved = false;
		
	if($board_settings['wkr'] == 1)
		$IsWKRMoved = true;
	else
		$IsWKRMoved = false;
		
	if($board_settings['wqr'] == 1)
		$IsWQRMoved = true;
	else
		$IsWQRMoved = false;
		
	if($board_settings['bk'] == 1)
		$IsBKMoved = true;
	else
		$IsBKMoved = false;
		
	if($board_settings['bkr'] == 1)
		$IsBKRMoved = true;
	else
		$IsBKRMoved = false;
		
	if($board_settings['bqr'] == 1)
		$IsBQRMoved = true;
	else
		$IsBQRMoved = false;
		
	if($board_settings['color'] == 1){
		$color = 'white';
		$IsWhiteTurn= true;
		$kingindex = NumToCoordinate($board_settings['wkingindex']);
	} else {
		$color = 'black';
		$IsWhiteTurn = false;
		$kingindex = NumToCoordinate($board_settings['bkingindex']);
	}
	
	/**
	if ($IsBKMoved)
		exit("The Black King has moved");
	else if (!$IsBKMoved)
		exit("The Black King has not moved");
	else
		exit("Nothing");
		//**/
	
	//$LegalMovesGenerator->SetWhiteSettings($board_settings['wk'], $board_settings['wkr'], $board_settings['wqr']);
	//$LegalMovesGenerator->SetBlackSettings($board_settings['bk'], $board_settings['bkr'], $board_settings['bqr']);
	$LegalMovesGenerator = new LegalMoveGenerator();
	$LegalMovesGenerator->SetWhiteSettings($IsWKMoved, $IsWKRMoved, $IsWQRMoved);
	$LegalMovesGenerator->SetBlackSettings($IsBKMoved, $IsBKRMoved, $IsBQRMoved);
	$LegalMovesGenerator->SetPosSettings($IsWhiteTurn, $LastMovePlayed);
	$LegalMovesGenerator->kingindex = $kingindex;
	$LegalMovesGenerator->GenerateLegalMoves($chessboard);

	// create the new files
	$fileBoardarray = dispBoardArray($chessboard);
	$fileSettings = dispBoardsettings($board_settings, $LastMovePlayed);
	$fileLegalmoves = dispLegalmoves($LegalMovesGenerator->legalmoves);
	$fileChessboard = OutputChessboard('./images/', $color, DoubleArrToSingleArr($chessboard));
	//exit("Number of legal moves: ".$LegalMovesGenerator->legalmoves[0][2]);
	
	// additional html code for the chessboard
	$matchname = getMatchInfo($matchid, 'matchname');
	$playerid = getBoardInfo($matchid, $boardid, 'playerid');
	$playername = getPlayerInfo($playerid, 'firstname')." ".getPlayerInfo($playerid, 'lastname');

	$html_color = "<input type='hidden' id='color' value='".$board_settings['color']."' />";
	$page_title = "<h1>$matchname</h1>";
	if ($board_settings['color'] == getBoardInfo($matchid, $boardid, 'color'))
		$board_title = "<h2>Board $boardid: $playername to play</h2>";
	else 
		$board_title =  "<h2>Board $boardid: $playername awaiting turn</h2>";

	//write the new files
	$fp = fopen('../matches/match'.$matchid.'/board'.$boardid.'.php','w');
	fwrite($fp, $page_title);
	fwrite($fp, $board_title);
	fwrite($fp, $html_color);
	fwrite($fp, $fileChessboard);
	fclose($fp);

	$fp = fopen('../matches/match'.$matchid.'/legalmoves'.$boardid.'.php', 'w');
	fwrite($fp, $fileLegalmoves);
	fclose($fp);
	
	$fp = fopen('../matches/match'.$matchid.'/board_settings'.$boardid.'.php', 'w');
	fwrite($fp, $fileSettings);
	fclose($fp);
	
	$fp = fopen('../matches/match'.$matchid.'/board_array'.$boardid.'.php', 'w');
	fwrite($fp, $fileBoardarray);
	fclose($fp);
	
	// reset the poll settings
	//delVotesOnBoard($matchid, $boardid); // not needed because the players voting are not registered
	// reset the table storing the ip addresses
	chgMatch_PlayersStatus($matchid, $playerid, '600');
	chgBoardVoteStatus($matchid, $boardid, '600');
}

echo true;

?>
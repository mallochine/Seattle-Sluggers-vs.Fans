<?php 

include_once('../lib/database_fns.php');
include_once('../lib/DispChessboard.inc.php');
include_once('../lib/LegalMoveGenerator.class.php');
include_once('../lib/general.inc.php');

$matchid = $_POST['matchid'];

// check if all the players have accepted the match invitation
// first get all the playerids that are registered under this match
$playerid = array();
$playerid = getPlayersInMatch($matchid);

// loop through the array of playerids and check if all of them have accepted
for ($i=0; $i<count($playerid); $i++){
	if(!isInvitationAccepted($matchid, $playerid[$i]))
		exit("Error: not all of the invitations were accepted");
}

// create the HTML string for the board
$chessboard = OutputChessboard('./images/'); // same for every board
$arrNewboard = getNewChessboardArr();
$dblarrNewboard = SingleArrToDoubleArr($arrNewboard);

// create the PHP string for the legal moves
$LegalmoveGenerator = new LegalMoveGenerator();
$LegalmoveGenerator->IsWhiteToMove = true;
$LegalmoveGenerator->GenerateLegalMoves($dblarrNewboard);
$fileLegalmoves = dispLegalmoves($LegalmoveGenerator->legalmoves);

// create the PHP string for the board settings
$fileSettings = dispBoardsettings();

// create the PHP string for the current board position
$fileBoardArray = dispBoardArray($dblarrNewboard);

for ($i=1; $i<=count($playerid); $i++){
	// creating the HTML file for each board
	$matchname = getMatchInfo($matchid, 'matchname');
	$boardid = getBoardID($matchid, $playerid[$i-1]); 
	$playername = getPlayerInfo($playerid[$i-1], 'firstname')." ".getPlayerInfo($playerid[$i-1], 'lastname');
	
	$html_color = "<input type='hidden' id='color' value='1' />";
	/** it is always going to be white to move
	$color = getBoardInfo($matchid, $boardid, 'color');
	if ($color == 1){ // white
		$html_color = "<input type='hidden' id='color' value='1' />";
	}
	
	if ($color == 0){ // black
		$html_color = "<input type='hidden' id='color' value='0' />";
	}
	**/
	
	$page_title = "<h1>$matchname</h1>";
	$board_title = "<h2>Board $boardid: $playername</h2>";
	
	if(!$fp = fopen("../matches/match$matchid/board$boardid.php","w"))
		exit('Error: could not create the file pointer for the html file');
	if(!fwrite($fp, $page_title))
		echo "Error: could not write the page title";
	if(!fwrite($fp, $board_title))
		echo "Error: could not write the board title";
	if(!fwrite($fp, $html_color))
		echo "Error: could not write the input value for the color";
	if(!fwrite($fp, $chessboard))
		echo "Error: could not write the chessboard";
	fclose($fp);
	
	// create the PHP file for the legal moves
	if(!$fp = fopen("../matches/match$matchid/legalmoves$boardid.php","w"))
		exit('Error: could not create the file pointer for the legal moves file');
	if(!fwrite($fp, $fileLegalmoves))
		echo "Error: could not write the legal moves";
	fclose($fp);
		
	// create the PHP file for the board_settings
	if (!$fp = fopen("../matches/match$matchid/board_settings$boardid.php","w"))
		exit('Error: could not create the file pointer for the board settings');
	if (!fwrite($fp, $fileSettings))
		echo "Error: could not write the board settings";
	fclose($fp);
	
	// create the PHP files for the current board position
	if (!$fp = fopen("../matches/match$matchid/board_array$boardid.php","w"))
		exit('Error: could not create the file pointer for the current board position');
	if (!fwrite($fp, $fileBoardArray))
		echo "Error: could not write the chessboard array into the php file";
	fclose($fp);
		
	// change the vote status of each player
	chgMatch_PlayersStatus($matchid, $playerid[$i-1], 600); // a vote has not been submitted
}

// change the status of the match
chgMatchInfo($matchid, 'status', 500); // 500 = the match is now in progress
echo true;

?>
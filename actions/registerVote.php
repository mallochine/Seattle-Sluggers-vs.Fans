<?php 

/**
 * Notes
 * -registerVote.php is where the second stage of legality checking takes place
 */

//NOTE: these file links are likely to be deprecated
include ('.../kaspro/config.php');
include ('./lib/LegalMoveGenerator.class.php');
include ('./lib/general.inc.php');

$userid = $_REQUEST['user'];
$matchid = $_REQUEST['match'];
$boardid = $_REQUEST['board'];
$firstsquare = $_REQUEST['firstsquare'];
$lastsquare = $_REQUEST['lastsquare'];
$king_index = $_REQUEST['kindex']; // An integer indicating where the king is located.

// get the current chess board
for ($i=0; $i<64; $i++){
	$url_param = "sq".$i;
	$chessboard[$i] = $_REQUEST[$url_param];
}

// convert the chessboard to a double-dimensional array for use in the LegalMoveGenerator
$dblChessboard = SingleArrToDoubleArr($chessboard);

// check whether the king is still safe if the move is played
$LegalMovesGenerator = new LegalMoveGenerator();
$LegalMovesGenerator->SetAsPosition($dblChessboard);
$kingsq = NumToCoordinate($king_index);
if (!$LegalMovesGenerator->IsKingAllowedOnSquare($kingsq[0], $kingsq[1])){
	//uh...stop the script somehow.
	echo "mayday! huge error!";
}

// see whether the user is supposed to vote on that board
$match_users_table = "match_users$matchid";
$qryUser = "select * from `$match_users_table` 
	where `userid`=$userid 
	and `boardid`=$boardid";
$srcUser = mysql_query($qryUser, $DBconn);

if (mysql_affected_rows($srcUser) == 0){
	echo "The user is not registered to vote on this board";
}

// register the vote if the tests for legitimacy has been passed
$vote_table = "match_votes$matchid";
$qryVote = "select * from `".$vote_table."` where `firstsquare`=`".$firstsquare.
	"and `lastsquare`=`".$lastsquare."` limit 1";
$srcVote = mysql_query($qryVote, $DBconn);
$numVotes;

// get the current number of votes
if (mysql_affected_rows($srcVote) == 0){
	// if the move is not in the table, insert a new entry
	$qryRegstrMove = "insert into `$vote_table` values(
		$boardid, $firstmove, $lastmove, 0);";
	mysql_query($qryRegstrMove, $DBconn);
	$numVotes = 0;
} else {
    while ($candidate_move = mysql_fetch_array($srcVote)){
        $numVotes = $candidate_move['NumVotes'];
    }
}

$numVotes++; 
$qryRegstrVote = "update `$vote_table` 
	set `NumVotes`=$numVotes
	where `boardid`=$boardid;";
if (mysql_query($qryRegstrVote, $DBconn)){
	echo "true";
} else {
	echo "false";
}


?>
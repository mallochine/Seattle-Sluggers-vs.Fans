<?php	

/**

if(mkdir ("matches/match5/", 0777, true))
	echo "the directory was successfully created";
else
	echo "the directory was not successfully created";
**/

include_once('./lib/DispChessboard.inc.php');
include_once('./lib/LegalMoveGenerator.class.php');
include_once('./lib/general.inc.php');
include_once('./lib/database_fns.php');

//$newmove = getNewMove(3, 1);
//echo "<script>alert('firstmove:".$newmove[0].". lastmove:".$newmove[1]."');</script>";

// create the HTML/PHP files for each board
$arrNewboard = getNewChessboardArr();
$dblarrNewboard = SingleArrToDoubleArr($arrNewboard);

// legal move generator
$LegalmoveGenerator = new LegalMoveGenerator();
$LegalmoveGenerator->IsWhiteToMove = false;
$LegalmoveGenerator->kingindex = array(4, 7);
$LegalmoveGenerator->GenerateLegalMoves($dblarrNewboard);
//$LegalmoveGenerator->SetAsPosition($dblarrNewboard);
//$LegalmoveGenerator->GenerateLegalPawnMoves(1, 0, 3);
//$LegalmoveGenerator->GenerateLegalKnightMoves(2, 6, 0);
//$fileLegalmoves = dispLegalmoves($LegalmoveGenerator->legalmoves);
//echo $fileLegalmoves;

/** beginning of the debug attempt **/
include('./matches/match3/board_array1.php');
include('./matches/match3/board_settings1.php');
	$LastMovePlayed = array(0,0,0);

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
	
	$LegalMovesGenerator = new LegalMoveGenerator();
	$LegalMovesGenerator->SetWhiteSettings($IsWKMoved, $IsWKRMoved, $IsWQRMoved);
	$LegalMovesGenerator->SetBlackSettings($IsBKMoved, $IsBKRMoved, $IsBQRMoved);
	$LegalMovesGenerator->SetPosSettings($IsWhiteTurn, $LastMovePlayed);
	$LegalMovesGenerator->kingindex = $kingindex;
	$LegalMovesGenerator->GenerateLegalMoves($chessboard);
	/*
	$from = array($kingindex[0], $kingindex[1]);
	$to = array(1, 7);
	$LegalMovesGenerator->AddChessSquare(-6, $from, $to);
	
	/**
	if($LegalMovesGenerator->isKingSafeIfPieceMoved($from, $to))
		echo "YES THE KING WOULD BE SAFE";
	else
		echo "NO THE KING WOULD NOT BE SAFE";
		//**/
	
	/*
	$fileLegalmoves = dispLegalmoves($LegalMovesGenerator->legalmoves);
	$fp = fopen('./matches/match3/legalmoves1.php', 'w');
	fwrite($fp, $fileLegalmoves);
	fclose($fp);
	*/
	
	echo "<script>alert('".$_SERVER['SERVER_NAME']."');</script>";
	
?>
<div id='chessboard' align='center'>
	<?php 
		echo OutputChessboard('./images/', 'white', DoubleArrToSingleArr($dblarrNewboard));
		//echo OutputChessboard('./images/', 'black', $singlearrNewboard);
	?>
</div>

<br />
<form id='test_form'>
	<input type='text' id='squareid' />
	<input type='submit' id='test_button' value='CLICK ME!' />
</form>

<html>
	<script src='./lib/jquery.js' type='text/javascript'></script>
	<script>
		$("#test_form").submit(function(event){
			event.preventDefault();
			var squareid = $("#squareid").val();
			$("#"+squareid).html("HI");
		});
	</script>
</html>
<?php 

/**
 * General Assumptions
 * 
 * connection to database is already established.
 */

/**
 * Here is how the bitboard for the chessboard will be stored
 * in a multi-dimensional array called $chessposition
 * 
 * negative sign means that the piece is a black piece
 * positive sign means that the piece is a white piece
 * 
 * 0 = nothing
 * 1 = pawn
 * 2 = knight
 * 3 = bishop
 * 4 = rook
 * 5 = queen
 * 6 = king
 */

/**
 * echo a chessboard
 * 
 * assumptions
 * -we will be outputting a html table
 * -the images will have a .png file extension
 * -the table cells have a name called s1 (or, s[id])
 * 
 * @param 
 * -$chessposition single-dimensional array
 * -$img_path the directory that the images are located in
 * 
 * @return html_output...what else is there to say
 * 
 * Assumptions
 * -$img_path ends in a '/'
 */
function OutputChessboard($img_path, $color='white',$chessposition=array()){
	$html_output = '';

	if (empty($chessposition)){
		//set the $chessposition as the starting position.
		$chessposition = getNewChessboardArr();
	}
	
	$html_output .= "<table class='chessboard' id='chessboard' border='0' cellpadding='0' cellspacing='0'>";
	if ($color == 'white'){
		$j = 7;
		$start = 0;
		$diff = 8;
		$inc = 1;
	} else {
		$j = 0;
		$start = 7;
		$diff = -1;
		$inc = -1;
	}
	
	while ($j>=0 && $j<=7){
		$html_output .= "<tr>";
		for ($i=$start; ($i-$diff)!=0; $i=$i+$inc){	
			$html_output .= "<td>";
			$square_code = $j*8 + $i;
      $piece_code   = $chessposition[$square_code]; 
      if ($piece_code == 0){
      	$piece_name = 'square';
        $piece_color = '';
      } else {
      	$piece_name   = getPieceName($piece_code);
        $piece_color  = getPieceColor($piece_code);
      }
      if (IsWhiteSquare($square_code))
       	$square_color = 'w';
      else
      	$square_color = 'b';
      	
      $html_output .= "<td id='$square_code'>";
      $html_output .= "<img src='".$img_path.$piece_color.$piece_name.$square_color.".png' />"; // e.g. whiteking.png
      // '../lib/images/'
      $html_output .= "</td>";
		}
		$html_output .= "</tr>";
		
		if ($color == 'white'){
			$j--;
		} else {
			$j++;
		}
	}
		
	$html_output .= "</table>";
	
	return $html_output;
}

function getNewChessboardArr(){
	$chessposition = array();

	//set the first row
	$chessposition[0] = 4;
	$chessposition[1] = 2;
	$chessposition[2] = 3;
	$chessposition[3] = 5;
	$chessposition[4] = 6;
	$chessposition[5] = 3;
	$chessposition[6] = 2;
	$chessposition[7] = 4;
		
	//set the second row
	for ($i=0; $i<8; $i++){
		$chessposition[$i+8] = 1;
	}
		
	//set rows 3-6
	for ($i=0; $i<32; $i++){
		$chessposition[$i+16] = 0;
	}
		
	//set row 7
	for ($i=0; $i<8; $i++){
		$chessposition[$i+48] = -1;
	}
		
	//set row 8
	$chessposition[56] = -4;
	$chessposition[57] = -2;
	$chessposition[58] = -3;
	$chessposition[59] = -5;
	$chessposition[60] = -6;
	$chessposition[61] = -3;
	$chessposition[62] = -2;
	$chessposition[63] = -4;
	
	return $chessposition;
}

/**
 * Output the White settings
 * 
 * @param are all boolean
 */
function OutputWhiteSettings($IsKingMoved, $IsKRMoved, $IsQRMoved){
	$html_output = ''; 
	
	if ($IsKingMoved){
		$html_output .= "<input type='hidden' id='wK' value=1 />";
	} else {
		$html_output .= "<input type='hidden' id='wK' value=0 />";
	}
	
	if ($IsKRMoved){
		$html_output .= "<input type='hidden' id='wKR' value=1 />";
	} else {
		$html_output .= "<input type='hidden' id='wKR' value=0 />";
	}
	
	if ($IsQRMoved){
		$html_output .= "<input type='hidden' id='wQR' value=1 />";
	} else {
		$html_output .= "<input type='hidden' id='wQR' value=0 />";
	}
	
	return $html_output;
}

/**
 * Output Black settings
 * 
 * @param are all boolean
 */
function OutputBlackSettings($IsKingMoved, $IsKRMoved, $IsQRMoved){
	$html_output = ''; 
	
	if ($IsKingMoved){
		$html_output .= "<input type='hidden' id='wK' value=1 />";
	} else {
		$html_output .= "<input type='hidden' id='wK' value=0 />";
	}
	
	if ($IsKRMoved){
		$html_output .= "<input type='hidden' id='wKR' value=1 />";
	} else {
		$html_output .= "<input type='hidden' id='wKR' value=0 />";
	}
	
	if ($IsQRMoved){
		$html_output .= "<input type='hidden' id='wQR' value=1 />";
	} else {
		$html_output .= "<input type='hidden' id='wQR' value=0 />";
	}
	
	return $html_output;
}

function OutputPosSettings($IsWhiteTurn, $LastMovePlayed){
	$html_output = '';
	
	if ($IsWhiteTurn){
		$html_output .= "<input type='hidden' id='turn' value='white' />";
	} else if (!$IsWhiteTurn){
		$html_output .= "<input type='hidden' id='turn' value='black' />";
	}
	
	$html_output .= "<input type='hidden' id='lastpiece' value='".$LastMovePlayed[0]."' />";
	$html_output .= "<input type='hidden' id='lastfromsq' value=".$LastMovePlayed[1]." />";
	$html_output .= "<input type='hidden' id='lasttosq' value=".$LastMovePlayed[2]." />";
	
	return $html_output;
}

/**
 * something
 */
function getPieceColor($piece_code){
	if ($piece_code > 0){
		return 'white';
	}
	
	if ($piece_code < 0){
		return 'black';
	}
	
	if ($piece_code == 0){
		return 'ERROR, no color exists for 0';
	}
}

/**
 * Given a code, look up the name of a piece
 * 
 * @param int $chesspiece, positive if white, negative is black
 * @return the name of the piece.
 */
function getPieceName($chesspiece){
	$pieceid = abs($chesspiece); //make a white/black piece always white
	$piece_codes = array('', 'pawn', 'knight', 'bishop', 'rook', 'queen', 'king');
	
	return $piece_codes[$pieceid];
}

/**
 * Given the id of the square, determine if the square
 * is a black or white square
 * 
 * @param int squareid, between 0 and 63. Used in the array $chessposition
 * @return true if the square is a white square
 */
function isWhiteSquare($squareid){
	$ColNum = $squareid % 8;
	$RowNum = ($squareid - $ColNum)/8;
	$testnum = ($ColNum + ($RowNum % 2)) % 2;
	
	if ($testnum == 0){
		return false;
	} else if ($testnum == 1){
		return true;
	}
}

/**
 * creates a string of all the possible legal moves in a given position
 * - the legalmoves array will be a two dimensional array.
 * - the function essentially writes the dbl array of legal moves into the file
 * 
 * @param arr dblarrChessboard two dimensional array that encapsulates all the legal moves in the position
 * @return str 
 */
function dispLegalmoves($dblarrLegalmoves){
	$numLegalmoves = $dblarrLegalmoves[0][2]; // given
	$strLegalmoves = "<?php\n";
	$strLegalmoves .= "\$legalmoves = array();\n";
	$strLegalmoves .= "\$numlegalmoves = $numLegalmoves;\n";
	for ($i=0; $i<$numLegalmoves; $i++){
		$strLegalmoves .= "\$legalmoves[$i][0] = ".$dblarrLegalmoves[$i][0].";\n";
		$strLegalmoves .= "\$legalmoves[$i][1] = ".$dblarrLegalmoves[$i][1].";\n";
	}
	$strLegalmoves .= "?>";
	return $strLegalmoves;
}

/**
 * tries to output chessboard into a php file
 * 
 * $chessboard is a two dimensional array
 */
function dispBoardArray($chessboard){
	$strChessboard = "<?php\n";
	$strChessboard .= "\$chessboard = array();\n";
	for ($i=0; $i<8; $i++){
		for ($j=0; $j<8; $j++){
			$strChessboard .= "\$chessboard[$i][$j]=".$chessboard[$i][$j].";\n"; 
		}
	}
	$strChessboard .= "?>";
	return $strChessboard;
}

/**
 * creates a string of the current board settings, which will be outputted into a php file
 * 
 * board settings which will be inputed
 * - whether the white kr, qr, and king have moved
 * - whether the black kr, qr, and king have moved
 * - the last move played
 * - whose side it is to move
 * 
 * Note to self: the boardsettings could probably be more efficiently stored in an xml file
 */
function dispBoardsettings($settings = array(
														'color' 		=> 1, //1 indicates 'white'
														'wkr'				=> 0, //whether the white kr moved
														'wqr'				=> 0, //whether the white qr moved
														'wk'				=> 0, //whether the white king moved
														'bkr'				=> 0, //same for black
														'bqr'				=> 0,
														'bk'				=> 0,
														'wkingindex'=> 4, //square that the white king is on
														'bkingindex'=> 60),
													 $lastmove = array(0, 0, 0)
													){
	$strSettings = "<?php\n";
	$strSettings .= "\$board_settings = array();";
	$strSettings .= "\$board_settings['color'] = ".$settings['color'].";\n";
	$strSettings .= "\$board_settings['wkr'] = ".$settings['wkr'].";\n";
	$strSettings .= "\$board_settings['wqr'] = ".$settings['wqr'].";\n";
	$strSettings .= "\$board_settings['wk'] = ".$settings['wk'].";\n";
	$strSettings .= "\$board_settings['bkr'] = ".$settings['bkr'].";\n";
	$strSettings .= "\$board_settings['bqr'] = ".$settings['bqr'].";\n";
	$strSettings .= "\$board_settings['bk'] = ".$settings['bk'].";\n";
	$strSettings .= "\$board_settings['wkingindex'] = ".$settings['wkingindex'].";\n";
	$strSettings .= "\$board_settings['bkingindex'] = ".$settings['bkingindex'].";\n";
	$strSettings .= "\$lastmove = array();";
	$strSettings .= "\$lastmove[0] = ".$lastmove[0].";";
	$strSettings .= "\$lastmove[1] = ".$lastmove[1].";";
	$strSettings .= "\$lastmove[2] = ".$lastmove[2].";";
	$strSettings .= "?>";
	return $strSettings;
}
?>
<?php 

/**
 * converts a single array to a multi-dimensional array
 * 
 * parameters: a single-dimensional array
 * returns a double-dimensional array
 * 
 * notes
 * -needed for generating legal moves
 */
function SingleArrToDoubleArr($arrSingleDim = array()){
	$arrDoubleDim = array();
	
	for ($i=0; $i<64; $i++){
		$arrSquare = NumToCoordinate($i); 
		$arrDoubleDim[$arrSquare[0]][$arrSquare[1]] = $arrSingleDim[$i];
	}
	
	return $arrDoubleDim;
}

/**
 * convert a double dimensional array to a single array
 * 
 * @param arr double dimensional array $arrDoubleDim
 * @return arr single dimensional array $arrSingleDim
 */
function DoubleArrToSingleArr($arrDblDim){
	$arrSingleDim = array();
	for ($i=0; $i<64; $i++){
		$arrSingleDim[$i] = $arrDblDim[$i%8][floor($i/8)]; //the most beautiful line of code I have ever written
	}
	return $arrSingleDim;
}

/**
 * @param number
 * @return coordinate
 * 
 * could probably be better named as NumToDblArr
 */
function NumToCoordinate($NumSquare){ 
    $col = $NumSquare % 8;
    $row = (($NumSquare - $col)/8);
    	
    return array($col, $row);
}

function NumToAlgebraicSquare($NumSquare){
	$col = $NumSquare % 8;
	$row = ($NumSquare-$col)/8;
	
	$alphabet = 'abcdefgh';
	return $alphabet[$col].($row+1);
}

/**
 * Convert a coordinate to a number
 */
function CoordinateToNum($col, $row){
	return $col + 8*$row;
}

function PieceCodeToLetter($piece_code){
	$key = "  NBRQK";
	return $key[abs($piece_code)];
}

?>
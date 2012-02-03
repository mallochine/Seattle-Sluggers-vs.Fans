<?php

// Note to self: this is TERRIBLY WRITTEN AND DESIGNED CODE

/**
 * Here is how the bitboard for the chessboard will be stored
 * in a multi-dimensional array called $chessposition
 * 
 * negative signs means that the piece is a black piece
 * 0 = nothing
 * 1 = pawn
 * 2 = knight
 * 3 = bishop
 * 4 = rook
 * 5 = queen
 * 6 = king
 */
/**
 * Here is how a move will be stored in an array
 * 
 * $legalmoves[numlegalmoves][0/1]
 * $legalmoves[0][2] = total number of legal moves.
 * 
 * $legalmoves[0][0] = 1; //starting x-square
 * $legalmoves[0][1] = 7; //finishing y-square
 * $legalmoves[nextmove][0], etc.
 * 
 */
class LegalMoveGenerator 
{
	public $IsWhiteToMove = true;
	public $LastMovePlayed = array();
	/**
	 * $LastMovePlayed[0] = piece_code
	 * $LastMovePlayed[1] = starting square_num
	 * $LastMovePlayed[2] = finishing square_num
	 */
	
	//White Settings
	public $IsWhiteKingMoved = false;
	public $IsWhiteKRMoved = false;
	public $IsWhiteQRMoved = false;
	
	//Black to move
	public $IsBlackKingMoved = false;
	public $IsBlackKRMoved = false;
	public $IsBlackQRMoved = false;
	
	public $legalmoves = array(); //stores all the legal moves of a position
	public $chessposition = array(); //stores the chessboard being examined
	public $kingindex = array(4, 0);
	
	/**
	 * Set up the position's settings
	 * 
	 * @param
	 * -$IsWhiteToMove, boolean
	 * -$WhiteSettings[0] = IsWhiteKingMoved
	 * -$WhiteSettings[1] = IsWhiteKRMoved
	 * -$WhiteSettings[2] = IsWhiteQRMoved
	 * -same for BlackSettings[]
	 */
	public function __construct($IsWhiteToMove = true,
								$LastMovePlayed = array(0, 0, 0),
								$WhiteSettings = array(false, false, false),
								$BlackSettings = array(false, false, false)){
		
		$this->SetWhiteSettings($WhiteSettings[0], $WhiteSettings[1], $WhiteSettings[2]);
		$this->SetBlackSettings($BlackSettings[0], $BlackSettings[1], $BlackSettings[2]);
		$this->ResetLegalMovesArr();
	}
	
	public function __destruct(){
		//idk what's going to happen.
	}
	
	public function SetPosSettings($IsWhiteToMove, $LastMovePlayed){
		if(is_bool($IsWhiteToMove)){
			$this->IsWhiteToMove = $IsWhiteToMove;
		}
		
		for ($i=0; $i<3; $i++){
			$this->LastMovePlayed[$i] = $LastMovePlayed[$i];
		}
	}
	
	/**
	 * Set the settings for White
	 * 
	 * @param bool $IsWhiteKingMoved, $IsWhiteKRMoved, $IsWhiteQRMoved
	 * 		have any of the above moved?
	 * @return bool true/false based on the success of the operation.
	 */
	public function SetWhiteSettings($IsWhiteKingMoved, $IsWhiteKRMoved, $IsWhiteQRMoved){
		$this->IsWhiteKingMoved = $IsWhiteKingMoved;
		$this->IsWhiteKRMoved = $IsWhiteKRMoved;
		$this->IsWhiteQRMoved = $IsWhiteKQMoved;
		return true;
	}
	
	/**
	 * Set the settings for White
	 * 
	 * @param bool $IsBlackKingMoved, $IsBlackKRMoved, $IsBlackQRMoved
	 * 		have any of the above moved?
	 * @return bool true/false based on the success of the operation.
	 */
	public function SetBlackSettings($IsBlackKingMoved, $IsBlackKRMoved, $IsBlackQRMoved){
		$this->IsBlackKingMoved = $IsBlackKingMoved;
		$this->IsBlackKRMoved = $IsBlackKRMoved;
		$this->IsBlackQRMoved = $IsBlackQRMoved;
		return true;
	}
	
	/**
	 * Set the chessboard
	 * 
	 * @param multi-dimensional array $chessboard
	 */
	public function SetAsPosition($chessposition){
		if($this->chessposition = $chessposition){
			return true;
		} else {
			return false;
		}
	}
	
	public function ResetLegalMovesArr(){
		unset($this->legalmoves);
		
		$this->legalmoves = array();
		$this->legalmoves[0][2] = 0;
	}

	/**
     * Given a chess position, generate legal moves
     * 
     * assumptions
     * -$chessposition is a legal position
     * -the settings for white has already been set
     * -the settings for black has already been set
     * -the settings for the position has already been set
     * 
     * @param: chessposition[file][rank]
     * @return: $this->legalmoves[piece][file][rank]
     */
	public function GenerateLegalMoves($chessposition){
		$this->SetAsPosition($chessposition);
		$this->ResetLegalMovesArr();
	
		//loop through the entire board
		//possible candidates are (x,y)
		for ($x=0; $x<8; $x++){
            for ($y=0; $y<8; $y++){
                $chesspiece = $this->chessposition[$x][$y];
                
                //if nothing is there
                if ($chesspiece == 0){
                    continue;
                }
                
                if (($this->IsWhiteToMove && ($chesspiece < 0)) ||
                		(!$this->IsWhiteToMove && ($chesspiece > 0))){
                		continue;
            		}
                
                //if chesspiece is a pawn
                if (($chesspiece == 1) || ($chesspiece == -1)){ // chesspiece = pawn
                    $this->GenerateLegalPawnMoves($chesspiece, $x, $y);
                    
                    continue; // move on to the next iteration
                }
                
                //if $chesspiece is a knight
                if (($chesspiece == 2) || ($chesspiece == -2)){
                    $this->GenerateLegalKnightMoves($chesspiece, $x, $y);
                    
                    continue; // move on to the next iteration
                }//end of examining legal moves for knight
                
                //if $chesspiece is a bishop
                if (($chesspiece == 3) || ($chesspiece == -3)){ //$chesspiece is a bishop
                    $this->GenerateLegalBishopMoves($chesspiece, $x, $y);
                    
                    continue; //move on to the next iteration
                }//end of generating legal moves for bishop
                
                //if $chesspiece is a rook
                if (($chesspiece == 4) || ($chesspiece == -4)){
                    $this->GenerateLegalRookMoves($chesspiece, $x, $y); 
                                       
                    continue; //move on to the next iteration
                }//end of generating legal moves for the rook
                
                //if $chesspiece is a queen
                if (($chesspiece == 5) || ($chesspiece == -5)){
                
                    $this->GenerateLegalBishopMoves($chesspiece, $x, $y);
                    $this->GenerateLegalRookMoves($chesspiece, $x, $y);
                    
                    continue; //move on to the next iteration
                }//end of generating legal moves for the queen
                
                //if chesspiece is a king
                if (($chesspiece == 6) || ($chesspiece == -6)){
                    $this->GenerateLegalKingMoves($chesspiece, $x, $y);
                    
                    continue; // move on to the next iteration
                }
            }
        }
	}// end of function GenerateLegalMoves
	
	/**
	 * For a given coordinate, generate all the legal rook moves
	 * 
	 * @assume $chessposition is already set
	 * @param $chessposition, name of the chesspiece, coordinates of the rook
	 * @return bool true/false based on success of operation
	 * @modifies $legalmoves
	 */
	public function GenerateLegalRookMoves($chesspiece, $x, $y){
	    //examine forward moves
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $b++;
            
            $from = array($x, $y);
            $to = array ($a, $b);
            
            if (!$this->IsLegalChessSquare($a, $b))
            	break;
            
            if($this->IsFriendlyPiece($chesspiece, $a, $b))
            	break;
            	
            $this->AddChessSquare($chesspiece, $from, $to);
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){
            	break; //stop examining this file.
            }
        }

        //examine backward moves
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $b--;
            
            $from = array($x, $y);
            $to = array ($a, $b);
            
            if (!$this->IsLegalChessSquare($a, $b))
            	break;
            
            if($this->IsFriendlyPiece($chesspiece, $a, $b))
            	break;
            	
            $this->AddChessSquare($chesspiece, $from, $to);
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ 
                break; //stop examining this file.
            }
        }

        //examine right-ward moves
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a++;
            
            $from = array($x, $y);
            $to = array ($a, $b);
            
            if (!$this->IsLegalChessSquare($a, $b))
            	break;
            
            if($this->IsFriendlyPiece($chesspiece, $a, $b))
            	break;
            	
            $this->AddChessSquare($chesspiece, $from, $to);
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){
                break; //stop examining this rank.
            }
        }

        //examine left-ward moves
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a--;
            
            $from = array($x, $y);
            $to = array ($a, $b);
            
            if (!$this->IsLegalChessSquare($a, $b))
            	break;
            
            if($this->IsFriendlyPiece($chesspiece, $a, $b))
            	break;
            	
            $this->AddChessSquare($chesspiece, $from, $to);
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ 
                break; //stop examining this rank.
            }
        }
        
        return true;
	}//end of GenerateLegalRookMoves()

	/**
	 * For a given coordinate, generate all the legal bishop moves
	 * 
	 * @param chesspiece, coordinates of the bishop
	 * @return bool true/false based on the success of the operation
	 * @modifies legalmoves
	 */
	public function GenerateLegalBishopMoves($chesspiece, $x, $y){
		//examine top right diagonal
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a++;
            $b++;
            
            $from = array($x, $y);
            $to = array ($a, $b);
            
            if (!$this->IsLegalChessSquare($a, $b))
            	break;
            
            if($this->IsFriendlyPiece($chesspiece, $a, $b))
            	break;
            	
            $this->AddChessSquare($chesspiece, $from, $to);
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){
                break; //stop examining this diagonal.
            }
        }

        //examine bottom left diagonal
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a--;
            $b--;
            
            $from = array($x, $y);
            $to = array ($a, $b);
            
            if (!$this->IsLegalChessSquare($a, $b))
            	break;
            
            if($this->IsFriendlyPiece($chesspiece, $a, $b))
            	break;
            	
            $this->AddChessSquare($chesspiece, $from, $to);
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ 
                break; //stop examining this diagonal.
            }
        }

        //examine bottom right diagonal
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a++;
            $b--;
            
            $from = array($x, $y);
            $to = array ($a, $b);
            
            if (!$this->IsLegalChessSquare($a, $b))
            	break;
            
            if($this->IsFriendlyPiece($chesspiece, $a, $b))
            	break;
            	
            $this->AddChessSquare($chesspiece, $from, $to);
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ 
                break; //stop examining this diagonal.
            }
        }

        //examine top left diagonal
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a--;
            $b++;
            
            $from = array($x, $y);
            $to = array ($a, $b);
            
            if (!$this->IsLegalChessSquare($a, $b))
            	break;
            
            if($this->IsFriendlyPiece($chesspiece, $a, $b))
            	break;
            	
            $this->AddChessSquare($chesspiece, $from, $to);
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){
                break; //stop examining this diagonal.
            }
        }
        
        return true;
	}
	
	/**
	 * For a given coordinate, generate all the legal pawn moves
	 * 
	 * @param chesspiece, coordinates of the pawn
	 * @return bool true/false based on the success of the operation
	 * @modifies legalmoves
	 */
	public function GenerateLegalPawnMoves($chesspiece, $x, $y){
		$from = array($x, $y);
		
	    if ($chesspiece > 0){ // $chesspiece is a white pawn.
	    	$to = array($x, $y+1);
	    	
	    	if ($this->chessposition[$x][$y+1] == 0){
	    		$this->AddChessSquare($chesspiece, $from, $to);
	       	if ($y == 1){
           	$to = array($x, $y+2);
           	if ($this->chessposition[$x][$y+2] == 0)
            	$this->AddChessSquare($chesspiece, $from, $to);
	       	}
        }
        
        if ($this->IsEnemyPiece(1, $x+1, $y+1)){
        	$this->AddChessSquare(1, $from, array($x+1, $y+1));
        }
        if ($this->IsEnemyPiece(1, $x-1, $y+1)){
        	$this->AddChessSquare(1, $from, array($x-1, $y+1));
        }
      }

      if ($chesspiece < 0){ // $chesspiece is a black pawn
      	$to = array($x, $y-1);
	    	
	      if($this->chessposition[$x][$y-1] == 0){
	      	$this->AddChessSquare($chesspiece, $from, $to);
	      	if ($y == 6){
          	$to = array($x, $y-2);
          	if ($this->chessposition[$x][$y-2] == 0)
            	$this->AddChessSquare($chesspiece, $from, $to);
	        }
        }
        
        if ($this->IsEnemyPiece(-1, $x+1, $y-1))
        	$this->AddChessSquare(-1, $from, array($x+1, $y-1));
        if ($this->IsEnemyPiece(-1, $x-1, $y-1))
        	$this->AddChessSquare(-1, $from, array($x-1, $y-1)); 
      }
        
        //now do en-passant
        /***
        if ($LastMovePlayed[0] == ){ //if it is a pawn
        	$fromsquare = $this->NumToCoordinate($LastMovePlayed[0]);
        	$tosquare = $this->NumToCoordinate($LastMovePlayed[1]);
        	
        	$a = $tosquare[0] + 1;
        	if ($this->IsEnemyPiece($this->chessposition[$tosquare[0]][$tosquare[1]]))
        }
        ***/
        
        return true;
	}
	
	/**
	 * For a given coordinate, generate all the legal knight moves
	 * 
	 * @param chesspiece, coordinates of the pawn
	 * @return bool true/false based on the success of the operation
	 * @modifies legalmoves
	 */
	public function GenerateLegalKnightMoves($chesspiece, $x, $y){
		$from = array($x, $y);
		
		$to1 = array($x+1, $y+2);
		$to2 = array($x-1, $y+2);
		$to3 = array($x+1, $y-2);
		$to4 = array($x-1, $y-2);
		$to5 = array($x+2, $y+1);
		$to6 = array($x-2, $y+1);
		$to7 = array($x+2, $y-1);
		$to8 = array($x-2, $y-1);
		
		$this->AddChessSquare($chesspiece, $from, $to1);
        $this->AddChessSquare($chesspiece, $from, $to2);
        $this->AddChessSquare($chesspiece, $from, $to3);
        $this->AddChessSquare($chesspiece, $from, $to4);
        $this->AddChessSquare($chesspiece, $from, $to5);
        $this->AddChessSquare($chesspiece, $from, $to6);
        $this->AddChessSquare($chesspiece, $from, $to7);
        $this->AddChessSquare($chesspiece, $from, $to8);
        
        return true;
	}
	
	/**
	 * For a given coordinate, generate all the legal king moves
	 * 
	 * @param chesspiece, coordinates of the king
	 * @return bool true/false based on the success of the operation
	 * @modifies legalmoves
	 */
	public function GenerateLegalKingMoves($chesspiece, $x, $y){
	
		$from = array($x, $y);
	
		/**
		 * Determine the legality of the usual king moves
		 */
		if ($this->IsKingAllowedOnSquare($x+1, $y)){
			$to = array($x+1, $y);
			$this->AddChessSquare($chesspiece, $from, $to);
		}
		
		if ($this->IsKingAllowedOnSquare($x+1, $y+1)){
			$to = array($x+1, $y+1);
			$this->AddChessSquare($chesspiece, $from, $to);
		}
		
		if ($this->IsKingAllowedOnSquare($x+1, $y-1)){
			$to = array($x+1, $y-1);
			$this->AddChessSquare($chesspiece, $from, $to);
		}

		if ($this->IsKingAllowedOnSquare($x-1, $y)){
			$to = array($x-1, $y);
			$this->AddChessSquare($chesspiece, $from, $to);
		}
		
		if ($this->IsKingAllowedOnSquare($x-1, $y+1)){
			$to = array($x-1, $y+1);
			$this->AddChessSquare($chesspiece, $from, $to);
		}
		
		if ($this->IsKingAllowedOnSquare($x-1, $y-1)){
			$to = array($x-1, $y-1);
			$this->AddChessSquare($chesspiece, $from, $to);
		}
		
		if ($this->IsKingAllowedOnSquare($x, $y-1)){
			$to = array($x, $y-1);
			$this->AddChessSquare($chesspiece, $from, $to);
		}
		
		if ($this->IsKingAllowedOnSquare($x, $y+1)){
			$to = array($x, $y+1);
			$this->AddChessSquare($chesspiece, $from, $to);
		}
		
		/**
		 * Determine whether the king can castle kingside
		 */
		if ($chesspiece > 0){
		    if (!$this->IsWhiteKingMoved && !$this->IsWhiteKRMoved){
                if ($this->IsKingAllowedOnSquare(5, 0) && 
                    $this->IsKingAllowedOnSquare(6, 0) &&
                    $this->chessposition[5][0] == 0 &&
                    $this->chessposition[6][0] == 0){
                    // note: this function never got called.
                    // therefore, the problem must be in the IsKingAllowedOnSquare function
                    // not sure what the problem is exactly thoughf
                    $to = array(6, 0);
                    $this->AddChessSquare($chesspiece, $from, $to);
                }
            }
            
            if (!$this->IsWhiteKingMoved && !$this->IsWhiteQRMoved){
            	if ($this->IsKingAllowedOnSquare(2, 0) && 
            			$this->IsKingAllowedOnSquare(3, 0) &&
            			$this->chessposition[3][0] == 0 && 
            			$this->chessposition[2][0] == 0 &&
            			$this->chessposition[1][0] == 0){
            		
            		$to = array(2, 0);
            		$this->AddChessSquare($chesspiece, $from, $to);
            	}
            }
		}
		
		if ($chesspiece < 0){
			if (!$this->IsBlackKingMoved && !$this->IsBlackKRMoved){
                if ($this->IsKingAllowedOnSquare(5, 7) && 
                    $this->IsKingAllowedOnSquare(6, 7) && 
                    $this->chessposition[5][7] == 0 &&
                    $this->chessposition[6][7] == 0){
                    
                    $to = array(6, 7);
                    $this->AddChessSquare($chesspiece, $from, $to);
                }
            }
            
			if (!$this->IsBlackKingMoved && !$this->IsBlackQRMoved){
            	if ($this->IsKingAllowedOnSquare(2, 7) && 
            			$this->IsKingAllowedOnSquare(3, 7) &&
            			$this->chessposition[1][7] == 0 &&
            			$this->chessposition[2][7] == 0 &&
            			$this->chessposition[3][7] == 0){
            		
            		$to = array(2, 7);
            		$this->AddChessSquare($chesspiece, $from, $to);
            	}
            }
		}
        
        return true;
	} // end of generating legal king moves
	
	/**
	 * determine if the king would be safe if the move was made
	 */
	public function isKingSafeIfPieceMoved($firstsquare,$lastsquare){
		$firstsquare_piececode = $this->chessposition[$firstsquare[0]][$firstsquare[1]];
		$lastsquare_piececode = $this->chessposition[$lastsquare[0]][$lastsquare[1]];
		$this->chessposition[$firstsquare[0]][$firstsquare[1]] = 0;
		$this->chessposition[$lastsquare[0]][$lastsquare[1]] = $firstsquare_piececode;
		if (abs($firstsquare_piececode) == 6)
			$result = $this->IsKingAllowedOnSquare($lastsquare[0], $lastsquare[1]);
		else
			$result = $this->IsKingAllowedOnSquare($this->kingindex[0], $this->kingindex[1]);
		$this->chessposition[$firstsquare[0]][$firstsquare[1]] = $firstsquare_piececode;
		$this->chessposition[$lastsquare[0]][$lastsquare[1]] = $lastsquare_piececode;
		return $result;
	}
	
	/**
	 * Determine whether a king can safely go to a given coordinate
	 * 
	 * @param coordinate of the square in question!
	 * @return bool true/false based on whether the king is allowed to 
	 * 		go on that coordinate square. 
	 */
	public function IsKingAllowedOnSquare($x, $y){
	
		if ($this->IsWhiteToMove)
			$chesspiece = 6;
		else
			$chesspiece = -6;
		
		if (!$this->IsLegalChessSquare($x, $y)){
			return false;
		}
		
		/** Actually, we don't need this piece of code, because it's not a criterion for whether 
		 * the king is safe on (x, y)
		//$chessposition[$x][$y]
		if ($this->IsFriendlyPiece($chesspiece, $x, $y)){ 
			return false;
		}
		**/
		
		/**
		 * Examine the diagonals
		 */
	    $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a--;
            $b++;
            
            if (!$this->IsLegalChessSquare($a, $b)){
            	break; //there are no more squares along this diagonal
            }
            
            if ($this->chessposition[$a][$b] == 0){ //there's nobody on this square
            	continue; //move on to next iteration
            }
            
            if ($this->IsFriendlyPiece($chesspiece, $a, $b)){ //there's a friendly piece
            	break; //stop examining this diagonal
            }
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ //there's an enemy piece
            	$enemypiece = $this->chessposition[$a][$b];
            	$piece = abs($enemypiece);
            	if ($piece == 3 || $piece == 5) {
            		return false;
            	} else
            		break;
            }
        }//end of examining top left diagonal
        
        //check bottom left diagonal
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a--;
            $b--;
            
            if (!$this->IsLegalChessSquare($a, $b)){
            	break; //there are no more squares along this diagonal
            }
            
            if ($this->chessposition[$a][$b] == 0){ //there's nobody on this square
            	continue; //move on to next iteration
            }
            
            if ($this->IsFriendlyPiece($chesspiece, $a, $b)){ //there's a friendly piece
            	break; //stop examining this diagonal
            }
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ //there's an enemy piece
            	$enemypiece = $this->chessposition[$a][$b];
            	$piece = abs($enemypiece);
            	if ($piece == 3 || $piece == 5) {
            		return false;
            	} else
            		break;
            }
        }//end of examining bottom left diagonal
        
        //check top right diagonal
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a++;
            $b++;
            
            if (!$this->IsLegalChessSquare($a, $b)){
            	break; //there are no more squares along this diagonal
            }
            
            if ($this->chessposition[$a][$b] == 0){ //there's nobody on this square
            	continue; //move on to next iteration
            }
            
            if ($this->IsFriendlyPiece($chesspiece, $a, $b)){ //there's a friendly piece
            	break; //stop examining this diagonal
            }
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ //there's an enemy piece
            	$enemypiece = $this->chessposition[$a][$b];
            	$piece = abs($enemypiece);
            	if ($piece == 3 || $piece == 5) {
            		return false;
            	} else
            		break;
            }
        }//end of examining top right diagonal
        
        //check bottom right diagonal
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a++;
            $b--;
            
            if (!$this->IsLegalChessSquare($a, $b)){
            	break; //there are no more squares along this diagonal
            }
            
            if ($this->chessposition[$a][$b] == 0){ //there's nobody on this square
            	continue; //move on to next iteration
            }
            
            if ($this->IsFriendlyPiece($chesspiece, $a, $b)){ //there's a friendly piece
            	break; //stop examining this diagonal
            }
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ //there's an enemy piece
            	$enemypiece = $this->chessposition[$a][$b];
            	$piece = abs($enemypiece);
            	if ($piece == 3 || $piece == 5) {
            		return false;
            	} else 
            		break;
            }
        }//end of examining bottom right diagonal
        
        /**
         * Examine the Files and Ranks
         */
        //check forward file
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $b++;
            
            if (!$this->IsLegalChessSquare($a, $b)){
            	break; //there are no more squares along this file
            }
            
            if ($this->chessposition[$a][$b] == 0){ //there's nobody on this square
            	continue; //move on to next iteration
            }
            
            if ($this->IsFriendlyPiece($chesspiece, $a, $b)){ //there's a friendly piece
            	break; //stop examining this file
            }
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ //there's an enemy piece
            	$enemypiece = $this->chessposition[$a][$b];
            	$piece = abs($enemypiece);
            	if ($piece == 4 || $piece == 5) 
            		return false;
            	else
            		break;
            }
        }//end of checking forward file
        
        //check backward file
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $b--;
            
            if (!$this->IsLegalChessSquare($a, $b)){
            	break; //there are no more squares along this file
            }
            
            if ($this->chessposition[$a][$b] == 0){ //there's nobody on this square
            	continue; //move on to next iteration
            }
            
            if ($this->IsFriendlyPiece($chesspiece, $a, $b)){ //there's a friendly piece
            	break; //stop examining this file
            }
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ //there's an enemy piece
            	$enemypiece = $this->chessposition[$a][$b];
            	$piece = abs($enemypiece);
            	if ($piece == 4 || $piece == 5) {
            		return false;
            	} else
            		break;
            }
        }//end of checking forward file
        
        //check right-ward file
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a++;
            
            if (!$this->IsLegalChessSquare($a, $b)){
            	break; //there are no more squares along this file
            }
            
            if ($this->chessposition[$a][$b] == 0){ //there's nobody on this square
            	continue; //move on to next iteration
            }
            
            if ($this->IsFriendlyPiece($chesspiece, $a, $b)){ //there's a friendly piece
            	break; //stop examining this file
            }
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ //there's an enemy piece
            	$enemypiece = $this->chessposition[$a][$b];
            	$piece = abs($enemypiece);
            	if ($piece == 4 || $piece == 5) {
            		return false;
            	} else
            		break;
            }
        }//end of checking right-ward file
        
        //check left-ward file
        $a = $x;
        $b = $y;
        for ($i=0; $i<8; $i++){
            $a--;
            
            if (!$this->IsLegalChessSquare($a, $b)){
            	break; //there are no more squares along this file
            }
            
            if ($this->chessposition[$a][$b] == 0){ //there's nobody on this square
            	continue; //move on to next iteration
            }
            
            if ($this->IsFriendlyPiece($chesspiece, $a, $b)){ //there's a friendly piece
            	break; //stop examining this file
            }
            
            if ($this->IsEnemyPiece($chesspiece, $a, $b)){ //there's an enemy piece
            	$enemypiece = $this->chessposition[$a][$b];
            	$piece = abs($enemypiece);
            	if ($piece == 4 || $piece == 5) {
            		return false;
            	} else
            		break;
            }
        }//end of checking left-ward file
        
        /**
         * Check for Knights
         */
        if ($this->HasEnemyPiece($chesspiece, 2, $x+1, $y+2)){
        	return false;
        } else if ($this->HasEnemyPiece($chesspiece, 2, $x+1, $y-2)){
        	return false;
        } else if ($this->HasEnemyPiece($chesspiece, 2, $x-1, $y+2)){
        	return false;
        } else if ($this->HasEnemyPiece($chesspiece, 2, $x-1, $y-2)){
        	return false;
        } else if ($this->HasEnemyPiece($chesspiece, 2, $x+2, $y-1)){
        	return false;
        } else if ($this->HasEnemyPiece($chesspiece, 2, $x+2, $y+1)){
        	return false;
        } else if ($this->HasEnemyPiece($chesspiece, 2, $x-2, $y-1)){
        	return false;
        } else if ($this->HasEnemyPiece($chesspiece, 2, $x-2, $y+1)){
        	return false;
        }
        
	
		/**
		 * Check for Pawns
		 */
		if ($chesspiece > 0){ //a white piece
			if ($this->HasEnemyPiece($chesspiece, 1, $x+1, $y+1)){
				return false;
			}
			
			if ($this->HasEnemyPiece($chesspiece, 1, $x-1, $y+1)){
				return false;
			}
		}
		
		if ($chesspiece < 0){ //a black piece
			if ($this->HasEnemyPiece($chesspiece, 1, $x+1, $y-1)){
				return false;
			}
			
			if ($this->HasEnemyPiece($chesspiece, 1, $x-1, $y-1)){
				return false;
			}
		}
		
		/**
		 * Check for the King
		 */
		if ($this->HasEnemyPiece($chesspiece, 6, $x+1, $y)){
			return false;
		}
		
		if ($this->HasEnemyPiece($chesspiece, 6, $x+1, $y-1)){
			return false;
		}
		
		if ($this->HasEnemyPiece($chesspiece, 6, $x+1, $y+1)){
			return false;
		}
		
		if ($this->HasEnemyPiece($chesspiece, 6, $x-1, $y)){
			return false;
		}
		
		if ($this->HasEnemyPiece($chesspiece, 6, $x-1, $y+1)){
			return false;
		}
		
		if ($this->HasEnemyPiece($chesspiece, 6, $x-1, $y-1)){
			return false;
		}
		
		if ($this->HasEnemyPiece($chesspiece, 6, $x, $y+1)){
			return false;
		}
		
		if ($this->HasEnemyPiece($chesspiece, 6, $x, $y+1)){
			return false;
		}
		
		return true;
	}//end of IsKingAllowedOnSquare();
	
	/**
	 * Given coordinates, add chess square to legal moves if 
	 * the chess square is a legal chess square
	 * 
	 * @param chesspiece, arr from (coordinates), arr to (coordinates)
	 * @return true/false based on success of operation
	 */
	public function AddChessSquare($chesspiece, $from=array(), $to=array()){
		$x = $from[0];
		$y = $from[1];
		$a = $to[0];
		$b = $to[1];
		
		if (!$this->isKingSafeIfPieceMoved($from, $to))
			return false;
		
		$fromsquare = $this->CoordinateToNum($x, $y);
		$tosquare = $this->CoordinateToNum($a, $b);
		
		if (!$this->IsLegalChessSquare($a, $b)){
			return false;
		}
		
		//see whether $chesspiece and (a,b) are the same color
		if ($this->IsFriendlyPiece($chesspiece, $a, $b)){
			return false;
		}
		$NumPieceMoves = $this->legalmoves[0][2];
		
		//input from-coordinates and to-coordinates
		$this->legalmoves[$NumPieceMoves][0] = $fromsquare;
		$this->legalmoves[$NumPieceMoves][1] = $tosquare;
		
		//adjust the NumPieceMoves
		$NumPieceMoves++; 
		$this->legalmoves[0][2] = $NumPieceMoves;
				
		return true;
	}
	
	/**
	 * Determine whether a given coordinate is an enemy
	 * knight
	 * 
	 * @param int $chesspiece, int $enemypiece, coordinates
	 * @return true/false whether the coordinate has a Enemy Knight
	 */
	public function HasEnemyPiece($chesspiece, $enemypiece, $x, $y){
		if (!$this->IsLegalChessSquare($x, $y)){
			return false;
		}
	
		if ($this->chessposition[$x][$y] == 0){
			return false;
		}
		
		if ($this->IsFriendlyPiece($chesspiece, $x, $y)){ // *** ADDED $CHESSPIECE HERE ***
			return false;
		}
		
		if ($this->IsEnemyPiece($chesspiece, $x, $y)){ // *** ADDED $CHESSPIECE HERE ***
			$testnum = abs($this->chessposition[$x][$y]);
			if ($testnum == $enemypiece){ //if it is the enemy piece
				return true;
			} else {
				return false;
			}
		}
		
		return false;
	}
	
	/**
	 * Determine whether a given coordinate is an enemy piece
	 * 
	 * @param int chesspiece 
	 * @param int a,b are possible coordinates.
	 * 
	 * @return true/false whether coordinate has an enemy piece
	 */
	public function IsEnemyPiece($chesspiece, $a, $b){
		$testnum = $chesspiece * $this->chessposition[$a][$b];
		if ($testnum < 0){ //if they are opposite pieces
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Determine whether a given coordinate is a friendly piece
	 * 
	 * @param 
	 * -int chesspiece 
	 * -int a,b are possible coordinates.
	 * 
	 * @return true/false whether coordinate has an enemy piece
	 */
	public function IsFriendlyPiece($chesspiece, $a, $b){
		$testnum = $chesspiece * $this->chessposition[$a][$b];
		if ($testnum > 0){ //if they are friendly pieces
			return true;
		} else {
			return false;
		}
	}
	
    /**
     * Check if a given number could be part of a legal chess square
     * 
     * @param $number
     * @return true/false based on whether the square is legal. 
     */
    public function IsLegalChessSquare($a, $b){
        if (($a>7) || ($a<0)){
            return false;
        }
        
        if (($b>7) || ($b<0)){
            return false;
        }
        
        return true;
    }
    
    /**
     * convert a coordinate into a number (that will be put into
     * a single dimensional array)
     * 
     * @param coordinate
     * @return number
     */
    public function CoordinateToNum($x, $y){
    	return (($y * 8) + $x);
    }
    
    /**
     * @param number
     * @return array coordinate
     */
    public function NumToCoordinate($NumSquare){
    	$col = $NumSquare % 8;
    	$row = ($NumSquare - $col)/8;
    	
    	return array($col, $row);
    }
}//end of LegalMoveGenerator Class


?>
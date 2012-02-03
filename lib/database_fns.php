<?php

/**
 * Notes
 * - getMatchName, getMatchStatus, getNumBoards are equivalent to the function 
 * 		getMatchInfo, but they were already there before I created getMatchInfo,
 * 		and heck, it's not completely necessary to adjust, so screw it.
 * - lalalala wow I did not think this thing was going to be this long
 */

/**
 * database: kasprosian
 * server: 173.201.136.52
 * user: kasprosian
 * passwd: KrispyKreme1
 */
function db_connect($conn_info = array(
											'database' 	=> "matchdb",
											'server' 		=> "localhost",
											'user' 			=> "root",
											'passwd' 		=> "")
										){
  $result = mysql_connect($conn_info['server'], $conn_info['user'], $conn_info['passwd']);
  mysql_select_db($conn_info['database'], $result);
  return $result; 
}

/**
 * return true if the email and the key exist in the table
 */
function confirmPlayer($email, $key){
	$conn = db_connect();
	if (!$conn)
		die("error: could not connect to the database ".mysql_error());
		
	$email = mysql_real_escape_string($email);
	$key 	 = mysql_real_escape_string($key);
		
	$query = "select * from `players` where
						`email`		='$email' and
						`password`='$key'
						limit 1;";
	$result = mysql_query($query, $conn); 
	
	if (!$result)
		return mysql_error();
	if(mysql_num_rows($result) > 0)
		return true;
	else 
		return false;
}

/**
 * delete the votes on the given board and match
 */
function delVotesOnBoard($matchid, $boardid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$boardid = mysql_real_escape_string($boardid);
	$query = "delete from `match_votes$matchid`
						where `boardid`=$boardid;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	else
		return true;
}

/**
 * check whether the board number already exists
 */
function isBoardRegistered($matchid, $boardnum){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
	
	$table = "match_boards$matchid";
	$boardnum = mysql_real_escape_string($boardnum);
	$query = "select * from `$table`
						where `boardid`=$boardnum
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) > 0)
		return true;
	else
		return false;
}

/**
 * check whether the player has already been registered
 */
function IsPlayerRegistered($email){
	$conn = db_connect();
	if (!$conn)
		die("error: could not connect to the database ".mysql_error());
		
	$email = mysql_real_escape_string($email);
	
	$query = "select * from `players`
						where `email` = '$email' 
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) > 0)
		return true;
	else
		return false;
}

/**
 * 400 is the status code for active
 */
function isPlayerActive($playerid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$playerid = mysql_real_escape_string($playerid);
	
	$query = "select * from `players`
						where `playerid` = $playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	while ($player = mysql_fetch_array($result)){
		if($player['status'] == 400)
			return true;
		else
			return false;
		break;
	}
}

/**
 * looks at whether the player is registered in a match
 * 'player' refers to the person playing against the public
 */
function isPlayerInMatch($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$query = "select * from `match_players`
						where `matchid` = $matchid
						and `playerid` = $playerid
						and (`status` = 600 or `status` = 606)
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) > 0)
		return true;
	else 
		return false;
}

/**
 * check to see if the user is in the match at all...
 */
function isUserInMatch($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid 	= mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid); 
	$query = "select * from `match_users$matchid`
						where `playerid` = $playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	
	if (mysql_num_rows($result) > 0)
		return true;
	else
		return false;
}
/**
 * Check to see if the user is signed up for the board in the match
 */
function isUserOnBoard($matchid, $boardid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid 	= mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$boardid = mysql_real_escape_string($boardid);
	$query = "select * from `match_users$matchid`
						where `playerid` = $playerid
						and `boardid` = $boardid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	
	if (mysql_num_rows($result) > 0)
		return true;
	else
		return false;
}

function isInvitationAccepted($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid 	= mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$query = "select * from `match_players`
						where `matchid`=$matchid
						and `playerid` =$playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	
	if (!$result)
		return mysql_error();
	
	if (mysql_num_rows($result) == 0)
		return "The player is not invited to play in this match";
		
	while ($invitation = mysql_fetch_array($result)){
		$status = $invitation['status'];
		if ($status == 404 || $status == 200) // respectively, declined or not yet rsvped
			return false;
		else
			return true;
	}
}

/**
 * check whether the move entry already exists in the database
 */
function isMoveEntered($matchid, $boardid, $firstsquare, $lastsquare){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$firstsquare = mysql_real_escape_string($firstsquare);
	$lastsquare = mysql_real_escape_string($lastsquare);
	$boardid = mysql_real_escape_string($boardid);
	$query = "select * from `match_votes$matchid`
						where `boardid`=$boardid
						and `firstsq`=$firstsquare
						and `lastsq`=$lastsquare
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) > 0)
		return true;
	else
		return false;
}

/**
 * checks to see if there's an entry in `matches` with matchid $matchid
 */
function isMatchRegistered($matchid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$query = "select * from `matches`
						where `matchid` = $matchid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) > 0)
		return true;
	else
		return false;
}

function hasVotesOnBoard($matchid, $boardid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$boardid = mysql_real_escape_string($boardid);
	$query = "select * from `match_votes$matchid`
						where `boardid`=$boardid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) > 0)
		return true;
	else
		return false;
}

/**
 * enter the move into the database
 */
function registerMove($matchid, $boardid, $firstsquare, $lastsquare){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$firstsquare = mysql_real_escape_string($firstsquare);
	$lastsquare = mysql_real_escape_string($lastsquare);
	$boardid = mysql_real_escape_string($boardid);
	$query = "insert into `match_votes$matchid` (`primaryid`,`boardid`, `firstsq`,`lastsq`,`numvotes`)
						values(null, $boardid, $firstsquare, $lastsquare, 1);";
	$result = mysql_query($query, $conn);
	
	if (!result)
		return mysql_error();
	else 
		return true;
}

/**
 * for a given move, increase the vote count by one
 * assumes that the move is already registered in the database
 */
function increaseVote($matchid, $boardid, $firstsquare, $lastsquare){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$firstsquare = mysql_real_escape_string($firstsquare);
	$lastsquare = mysql_real_escape_string($lastsquare);
	$boardid = mysql_real_escape_string($boardid);
	$numVotes = getNumVotes($matchid, $boardid, $firstsquare, $lastsquare);
	$numVotes++;
	$query = "update `match_votes$matchid`
						set `numvotes` = $numVotes
						where `boardid`=$boardid
						and `firstsq`=$firstsquare
						and `lastsq`=$lastsquare
						limit 1;";
	$result = mysql_query($query, $conn);
	
	if (!$result)
		return mysql_error();
	else
		return true;
}

function getNumVotes($matchid, $boardid, $firstsquare, $lastsquare){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$firstsquare = mysql_real_escape_string($firstsquare);
	$lastsquare = mysql_real_escape_string($lastsquare);
	$boardid = mysql_real_escape_string($boardid);
	$query = "select `numvotes` from `match_votes$matchid`
						where `boardid`=$boardid
						and `firstsq`=$firstsquare
						and `lastsq`=$lastsquare
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	
	while ($vote = mysql_fetch_array($result)){
		return $vote['numvotes'];
		break;
	}
}

function activatePlayer($email, $key){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$email = mysql_real_escape_string($email);
	$key	 = mysql_real_escape_string($key);
	
	$query = "update `players`
						set `status` = 400
						where `email` = '$email'
						and `password` = '$key'
						limit 1;";
	if (mysql_query($query, $conn))
		return true;
	else
		return mysql_error();
}

function deactivatePlayer($email, $key){
	$conn = db_connect();
	if (!$conn)
		die("error: could not connect to the database ".mysql_error());
		
	$email = mysql_real_escape_string($email);
	$key	 = mysql_real_escape_string($key);
	
	$query = "update `players`
						set `status` = 404
						where `email` = '$email'
						and `password` = '$key'
						limit 1;";
	if (mysql_query($query, $conn))
		return true;
	else
		return mysql_error();
}

/**
 * insert the information into the match
 * 
 * @param arr boardinfo['playerid']['desc']
 * 
 * TO-DO
 * -have a function that checks for whether the info in board info is empty
 */
function insertBoard($matchid, $boardnum, $boardinfo){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());

	$table = "match_boards$matchid";
	$playerid = mysql_real_escape_string($boardinfo['playerid']);
	$desc = mysql_real_escape_string($boardinfo['desc']);
	$color = mysql_real_escape_string($boardinfo['color']);

	if(isBoardRegistered($matchid, $boardnum))
		return "Error: the board is already registered in our databases";
		
	/* Should we have this?
	if(!isPlayerActive($playerid))
		return "Error: the player id $playerid is currently registered as inactive in our database";
		*/
		
	$query = "insert into `$table`
						(`boardid`, `playerid`, `color`, `description`,`status`) VALUES(
							$boardnum, $playerid, $color, '$desc', NULL);";
	$result = mysql_query($query, $conn);
	if($result)
		return true;
	else
		return mysql_error();
}

/**
 * insert the player's information into the database
 * 
 * -need to include a way to sanitize the data
 */
function insertPlayer($playerinfo = array(
												'firstname'	=> '',
												'lastname'	=> '',
												'rating'		=> '',
												'email'			=> '',
												'passwd'		=> '')
											){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$firstname = mysql_real_escape_string($playerinfo['firstname']);
	$lastname = mysql_real_escape_string($playerinfo['lastname']);
	$rating = mysql_real_escape_string($playerinfo['rating']);
	$email = mysql_real_escape_string($playerinfo['email']);
	$passwd = $playerinfo['passwd'];
	$encrypted_passwd = mysql_real_escape_string(md5($passwd));
	
	if ($firstname == '' || $lastname == '')
		return "error: the firstname or the lastname is not filled out";
	
	$query = "INSERT INTO `players` 
			(`playerid`,`firstname`,`lastname`,`rating`,`email`,`password`,`status`)
			VALUES(
				NULL,
				'$firstname',
				'$lastname',
				$rating,
				'$email',
				'$encrypted_passwd',
				200);";
	if(mysql_query($query, $conn))
		return true;
	else
		return "error: query could not be executed ".mysql_error();
}

/**
 * relates a playerid with a matchid in `match_players`
 * 
 * assumes a default status of 200, which is "not replied yet" to an invitation
 */
function insertMatchPlayer($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	if (isPlayerInMatch($matchid, $playerid))
		return "Error: the player is already in the match.";
		
	$query = "insert into `match_players`
						(`primaryid`, `matchid`, `playerid`)
						VALUES(NULL, $matchid, $playerid);";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	else
		return true;
}

/**
 * for a given board, insert the user into the match
 * 
 * assumes that the user isn't already signed up for that board
 */
function insertUserOnBoard($matchid, $boardid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	if (isUserOnBoard($matchid, $boardid, $playerid))
		return false; //because the user is already registered on that board
		
	$matchid = mysql_real_escape_string($matchid);
	$boardid = mysql_real_escape_string($boardid);
	$playerid = mysql_real_escape_string($playerid);
	$query = "insert into `match_users$matchid`
						(`primaryid`,`boardid`,`playerid`,`status`)
						values(null, $boardid, $playerid, 600);";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	else
		return true;
}

/**
 * inserts the user into every board in the match
 */
function insertUserIntoMatch($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	// need to get a list of all the boardids
	$query = "select `boardid` from `match_boards$matchid`";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	while ($board = mysql_fetch_array($result)){
		if(!insertUserOnBoard($matchid, $board['boardid'], $playerid))
			return false;
	}
	return true;
}

function chgPlayerInfo($playerid, $columnname, $newvalue){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$playerid = mysql_real_escape_string($playerid);
	$columnname = mysql_real_escape_string($columnname);
	$newvalue = mysql_real_escape_string($newvalue);
	$query = "update `players`
						set `$columnname`='$newvalue'
						where `playerid`=$playerid
						limit 1;";
	if (mysql_query($query, $conn))
		return true;
	else
		return mysql_error();
}

function chgMatchInfo($matchid, $columnname, $newvalue){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database ".mysql_error());
		
	$query = "update `matches`
						set `$columnname`='$newvalue'
						where `matchid`=$matchid
						limit 1;";
	if (mysql_query($query, $conn))
		return true;
	else 
		return mysql_error();
}

/**
 * for a given playerid and a matchid in `match_players`, change the status to a new value
 */
function chgMatch_PlayersStatus($matchid, $playerid, $status){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database");
		
	$matchid = mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$status = mysql_real_escape_string($status);
	$query = "update `match_players`
						set `status` = $status
						where `matchid` = $matchid
						and `playerid` = $playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	else
		return true;
}

/**
 * for a given playerid, matchid, boardid in `match_users`, change the status
 */
function chgPlayerVoteStatus($matchid, $boardid, $playerid, $status){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database");
		
	$matchid = mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$boardid = mysql_real_escape_string($boardid);
	$status = mysql_real_escape_string($status);
	$query = "update `match_users$matchid`
						set `status` = $status
						where `boardid` = $boardid
						and `playerid` = $playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	else
		return true;
}

function chgBoardVoteStatus($matchid, $boardid, $status){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database");
		
	$matchid = mysql_real_escape_string($matchid);
	$boardid = mysql_real_escape_string($boardid);
	$status = mysql_real_escape_string($status);
	$query = "update `match_users$matchid`
						set `status`=$status
						where `boardid`=$boardid;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	else
		return true;
}

function chgBoardInfo($matchid, $boardid, $column, $newvalue){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database");
		
	$matchid = mysql_real_escape_string($matchid);
	$boardid = mysql_real_escape_string($boardid);
	$column = mysql_real_escape_string($column);
	$newvalue = mysql_real_escape_string($newvalue);
	$query = "update `match_boards$matchid`
						set `$column` = '$newvalue'
						where `boardid` = $boardid";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	else
		return true;
}

/**
 * @param int $matchid
 * @param int $boardid
 * @param int $playerid
 * @return int status
 * 
 * status is 600 if the player has not yet voted
 * status is 606 if the player has submitted his vote
 */
function getPlayerVoteStatus($matchid, $boardid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$boardid = mysql_real_escape_string($boardid);
	$playerid = mysql_real_escape_string($playerid);
	$query = "select `status`
						from `match_users$matchid`
						where `boardid` = $boardid
						and `playerid` = $playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	while ($status = mysql_fetch_array($result)){
		return $status['status'];
		break;
	}
}

/**
 * for a given playerid and a matchid, get the current status code in `match_players`
 * 
 * note: this is repeated in getInvitationStatus, but seriously, I'm not going to go through
 * the trouble of changing the name of the function where it's referenced...so it stays there
 */
function getMatch_PlayersStatus($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$query = "select `status`
						from `match_players`
						where `matchid`=$matchid
						and `playerid`=$playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	while ($invitation = mysql_fetch_array($result)){
		return $invitation['status'];
		break;
	}
}

/**
 * return true if the given director is indeed the director of the given matchid
 */
function IsDirector_MatchTrue($matchid, $directorid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to the database");
		
	$matchid = mysql_real_escape_string($matchid);
	$directorid = mysql_real_escape_string($directorid);
	$query = "select `directorid`
						from `matches`
						where `matchid`=$matchid
						limit 1;";
	$result = mysql_query($query, $conn);
	
	if (mysql_num_rows($result) > 0){
		while ($match = mysql_fetch_array($result)){
			if ($directorid == $match['directorid'])
				return true;
			else 
				return false;
		}
	} else 
		return "Error: match is not registered in the database";
}

function checkMatchStatus($matchid, $status_code){
	$conn = db_connect();
	if (!$conn)
		die("Could not connect to the database");
		
	$query = "select `status`
						from `matches`
						where `matchid`=$matchid
						limit 1;";
	$result = mysql_query($query, $conn);
	
	if (mysql_num_rows($result) > 0){
		while ($match = mysql_fetch_array($result)){
			if ($status_code == $match['status'])
				return true;
			else 
				return false;
			break;
		}
	} else 
		return "Match does not exist in the database";
}

/**
 * given the email, return the player's id
 */
function getPlayerID($email){
	$conn = db_connect();
	if (!$conn)
		die("could not connect to database ".mysql_error());
		
	$email = mysql_real_escape_string($email);
	$query = "select `playerid`
						from `players`
						where `email`='$email'
						limit 1;";
	$result = mysql_query($query, $conn);
	
	if (mysql_num_rows($result) > 0){
		while ($player = mysql_fetch_array($result)){
			return $player['playerid'];
			break;
		}
	} else {
		return "The player's email was not found in the database";
	}
}

function getBoardID($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());

	$matchid  = mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$table = "match_boards$matchid";
	$query = "select `boardid` from `$table`
						where `playerid`=$playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	
	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) == 0)
		return "There is no board id for this player: $playerid";
		
	while ($board = mysql_fetch_array($result)){
		return $board['boardid'];
		break;
	}
}

/**
 * given the director's username, look up the director's id
 */
function getDirectorID($username){
	$conn = db_connect();
	if (!$conn)
		die("could not connect to database ".mysql_error());
		
	$query = "select `directorid`
						from `tourney_directors`
						where `username`='$username'
						limit 1;";
	$result = mysql_query($query, $conn);
	
	if (mysql_num_rows($result) > 0){
		while ($director = mysql_fetch_array($result)){
			return $director['directorid'];
			break;
		}
	} else {
		return "The username not found in database";
	}
}

function getMatchName($matchid){
	$conn = db_connect();
	if (!$conn)
		die("could not connect to database ".mysql_error());
		
	$query = "select `matchname`
						from `matches`
						where `matchid`=$matchid
						limit 1;";
	$result = mysql_query($query, $conn);
	
	if (mysql_num_rows($result) > 0){
		while ($match = mysql_fetch_array($result)){
			return $match['matchname'];
			break;
		}
	} else 
		return "No such match exists";
}

function getMatchStatus($matchid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to database ".mysql_error());
	
	$query = "select `status`
						from `matches`
						where `matchid`=$matchid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (mysql_num_rows($result) > 0){
		while ($match = mysql_fetch_array($result)){
			return $match['status'];
			break;
		}
	} else 
		return "No such match exists";
}

function getNumBoards($matchid){
	$conn = db_connect();
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$query = "select `numBoards`
						from `matches`
						where `matchid`=$matchid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (mysql_num_rows($result) > 0){
		while ($match = mysql_fetch_array($result)){
			return $match['numBoards'];
		}
	} else 
		return "Error: this match does not exist";
}

function getPlayerInfo($playerid, $column){
	$conn = db_connect();
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$column = mysql_real_escape_string($column);
	$playerid = mysql_real_escape_string($playerid);
	$query = "select `$column`
						from `players`
						where `playerid`=$playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if(!$result)
		return mysql_error();
	while ($player = mysql_fetch_array($result)){
		return $player[$column];
		break;
	}
}

function getDirectorInfo($directorid, $column){
	$conn = db_connect();
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$column = mysql_real_escape_string($column);
	$directorid = mysql_real_escape_string($directorid);
	$query = "select `$column`
						from `tourney_directors`
						where `directorid`=$directorid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	while ($director = mysql_fetch_array($result)){
		return $director[$column];
		break;
	}
}

function getMatchInfo($matchid, $column){
	$conn = db_connect();
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$column = mysql_real_escape_string($column);
	$matchid = mysql_real_escape_string($matchid);
	$query = "select `$column`
						from `matches`
						where `matchid`=$matchid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	while ($player = mysql_fetch_array($result)){
		return $player[$column];
		break;
	}
}

function getBoardInfo($matchid, $boardid, $column){
	$conn = db_connect();
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$boardid = mysql_real_escape_string($boardid);
	$column  = mysql_real_escape_string($column);
	$table 	 = "match_boards$matchid";
	
	$query = "select * from `$table`
						where `boardid`=$boardid
						limit 1;";
	$result = mysql_query($query, $conn);

	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) == 0)
		return "Error: boardid $boardid does not exist";
		
	while ($board = mysql_fetch_array($result)){
		return $board[$column];
		break; 
	}
}

function getInvitationStatus($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$query = "select `status`
						from `match_players`
						where `matchid`=$matchid
						and `playerid`=$playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return msyql_error();
	while ($invitation = mysql_fetch_array($result)){
		return $invitation['status'];
		break;
	}
}

/**
 * @return arr playerid of all the playerids registered in the match 
 */
function getPlayersInMatch($matchid){
	$conn = db_connect(); 
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		 
	$matchid = mysql_real_escape_string($matchid);
	$query = "select `playerid` 
						from `match_players`
						where `matchid`=$matchid;";
	$result = mysql_query($query, $conn); 
	
	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) == 0)
		return "Error: matchid $matchid is not in our database";
	
	$playerid = array();
	$i = 0;
	while ($invitation = mysql_fetch_array($result)){
		$playerid[$i] = $invitation['playerid'];
		$i++;
	}
	
	return $playerid;
}

/**
 * @param int $matchid the id of the match
 * @return arr boardids[] stores all the board ids in a match
 */
function getBoardsInMatch($matchid){
	$conn = db_connect(); 
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$query = "select `boardid` from `match_boards$matchid`;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	$boardids = array();
	$numBoards = 0;
	while ($board = mysql_fetch_array($result)){
		$boardids[$numBoards] = $board['boardid'];
		$numBoards++;
	}
	return $boardids;
}

/**
 * Find the move that got the move most votes for a given matchid and boardid
 */
function getNewMove($matchid, $boardid){
	$conn = db_connect(); 
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$boardid = mysql_real_escape_string($boardid);		
	$query = "select * from `match_votes$matchid` 
							where `boardid`=$boardid
							order by `numvotes` asc";
	$result = mysql_query($query, $conn);
	
	$firstsquare = '';
	$lastsquare = '';
	
	while ($chessmove = mysql_fetch_array($result)){
		$firstsquare = $chessmove['firstsq'];
		$lastsquare = $chessmove['lastsq'];
	}
	
	return array($firstsquare, $lastsquare);
}

/**
 * For a given match and board, get the nth highest move that was voted on
 */
function getVoteRankedNumber($matchid, $boardid, $rank){ // why don't you read up on the sql manual as well as the construction manual
	$conn = db_connect(); 
	if (!$conn)
		die ("Error: could not connect to database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$boardid = mysql_real_escape_string($boardid);
	$rank = mysql_real_escape_string($rank);
	$query = "select * from `match_votes$matchid`
						where `boardid` = $boardid
						order by `numvotes` desc
						limit $rank;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	if (mysql_num_rows($result) < $rank)
		return false;
	$current_rank = 1;
	while ($vote = mysql_fetch_array($result)){
		if ($current_rank < $rank){
			$current_rank++;
			continue;
		} else {
			return array($vote['firstsq'], $vote['lastsq']);
			break;
		}
	}
}

?>
<?php

include_once('database_fns.php');
include_once('player_fns.php'); // need the function getInvitationStatusName();
include_once('general.inc.php');

function dispDirectorAccInfo($directorid){
	$conn = db_connect();
	if(!$conn)
		return "Error: there was a problem accessing the database. ".mysql_error();
		
	$directorid = mysql_real_escape_string($directorid);
	$query = "select * from `tourney_directors`
						where `directorid`=$directorid
						limit 1;";
	$result = mysql_query($query, $conn);
	if(!$result)
		return mysql_error();
	$account_info = '';
	while ($director = mysql_fetch_array($result)){
		$account_info .= 'Username: '.$director['username'].'<br />';
		$account_info .= 'First name: '.$director['firstname'].'<br />';
		$account_info .= 'Last name: '.$director['lastname'].'<br />';
		$account_info .= 'Email: '.$director['email'].'<br />';
		$account_info .= 'Password: **********<br />';
		$account_info .= 'Director Identification Number: '.$director['directorid'].'<br />';
		return $account_info;
		break;
	}
}

function dispBoardInfoForm($numBoards){
	echo "Note: * indicates that the field is required<br />";
	echo "<table border='1'>";
		echo "<tr>";
			echo "<td>Board Number*</td>";
			echo "<td>Player ID*</td>";
			echo "<td>White?*</td>";
			echo "<td>Description of player's relationship with your organization</td>";
		echo "</tr>";
		for ($i=1; $i<=$numBoards; $i++){
			echo "<tr>";
				echo "<td>$i</td>";
				echo "<td><input type='text' id='playerid$i' /></td>";
				echo "<td><input type='checkbox' id='color$i' /></td>";
				echo "<td><textarea id='desc$i' rows='10' maxlength='255'></textarea></td>";
			echo "</tr>";
		}
	echo "</table>";
	echo "<input type='hidden' id='numBoards' value='$numBoards' />";
}

/**
 * For now, this table just displays the status of the invites.
 * 
 * TO-DO:
 * -this table has extremely limited functionality
 * -need to create a functionality for the director to invite other players aside from the one
 *	 	he invited
 * -need to make sure that the boards are displayed in the correct order
 * -possibly add a column for the players' email addresses?
 */
function dispPlayersRSVPS($matchid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to database ".mysql_error());
		
	$matchid = mysql_real_escape_string($matchid);
	$query = "select * from `match_players`
						where `matchid` = $matchid;";
	$result = mysql_query($query, $conn);
	if (!$result){
		echo mysql_error();
		return;
	}
	
	$numInvitations = 0;
	$playerid = array();
	$status = array();
	while ($invitation = mysql_fetch_array($result)){
		$playerid[$numInvitations] = $invitation['playerid'];
		$status[$numInvitations] 	 = $invitation['status'];
		$boardnum[$numInvitations] = getBoardID($matchid, $invitation['playerid']);
		$numInvitations++;
	}

	echo "<table border='1'>";
		// top row
		echo "<tr>";
			echo "<td>Board Number</td>";
			echo "<td>Player Name</td>";
			echo "<td>RSVP</td>";
		echo "</tr>";
	for ($i=0; $i<$numInvitations; $i++){
		$status_name 			= getInvitationStatusName($status[$i]);
		$player_firstname = getPlayerInfo($playerid[$i], 'firstname');
		$player_lastname  = getPlayerInfo($playerid[$i], 'lastname');
		
		echo "<tr>";
			echo "<td>".$boardnum[$i]."</td>";
			echo "<td>$player_firstname $player_lastname</td>";
			echo "<td>$status_name</td>";
		echo "</tr>";
	}
	echo "</table>";
}

function getPlayerPic($matchid, $numBoard){
	if (file_exists("../matches/match$matchid/board$numBoard"."pic.png"))
		return "../matches/match$matchid/board$numBoard"."pic.png";
	if (file_exists("../matches/match$matchid/board$numBoard"."pic.jpg"))
		return "../matches/match$matchid/board$numBoard"."pic.jpg";
	if (file_exists("../matches/match$matchid/board$numBoard"."pic.bmp"))
		return "../matches/match$matchid/board$numBoard"."pic.bmp";
	if (file_exists("../matches/match$matchid/board$numBoard"."pic.gif"))
		return "../matches/match$matchid/board$numBoard"."pic.gif";
	return '';
}

/**
 * displays the matches that are currently being created by the director id
 * in an HTML table
 */
function dispMatchesBeingCreated($directorid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to database ".mysql_error());
		
	$query = "select * from `matches`
						where `directorid`=$directorid
						and not `status`=100;";
	$srcMatches = mysql_query($query, $conn);
	
	if (mysql_num_rows($srcMatches) <= 0)
		exit("There are no matches currently being created");
	
	// display the table of matches being created
	echo "<table id='MatchWizard' border='1'>";
		echo "<tr>";
			echo "<td>Match name</td>";
			echo "<td>Status</td>";
			echo "<td>Action</td>";
		echo "</tr>";
	while($match = mysql_fetch_array($srcMatches)){
		$status_code = $match['status'];
		$status_name = getMatchStatusName($status_code);
		if (!$status_name) // no status was returned
			continue; // skip this iteration	
		$matchname = $match['matchname'];
		$matchid = $match['matchid'];
		$status = getStatusStr($status_code);
		
		
		echo "<tr>";
		echo "<td>$matchname</td>";
		echo "<td>$status</td>";
		echo "<td>";
			echo "<a href='#' name='$status_name' id='$matchid'>Resume Creation</a>";
			//echo "<input type='hidden' name='matchid' value='$matchid' />"; 
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
}

/**
 * otuput all the matches that are currently in progress for a given directorid
 */
function dispMatchesInProgress($directorid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to database ".mysql_error());

	$directorid = mysql_real_escape_string($directorid);
	$query = "select * from `matches`
						where `status` = 500";
	$result = mysql_query($query, $conn);
	echo "<table border='1'>";
		echo "<tr>";
			echo "<td>Match ID</td>";
			echo "<td>Match name</td>";
		echo "</tr>";
	while ($match = mysql_fetch_array($result)){
		$link = 'director_index.php?url=managematch&matchid='.$match['matchid'];
		echo "<tr>";
			echo "<td><a href='$link'>".$match['matchid']."</a></td>";
			echo "<td>".$match['matchname']."</td>";
		echo "</tr>";
	}
	echo "</table>";
}

/**
 * using the matchid, displays all the boards and the moves that have been voted
 */
function dispMatchControlPanel($matchid){
	$conn = db_connect();
	if (!$conn)
		die("Error: could not connect to database ".mysql_error());
		
	// get all the boards in a match
	$boardids = getBoardsInMatch($matchid);
	
	/**
	$matchid = mysql_real_escape_string($matchid);
	$query = "select `boardid` from `match_boards$matchid`
						order by `boardid`;";
	$result = mysql_query($query, $conn);
	if (!$result){
		echo mysql_error();
		return;
	}
	**/
	
	// display a summary of all the boards
	echo "<table border='1'>";
		echo "<tr>";
			echo "<td></td>";
			echo "<td>Board Number</td>";
			echo "<td>Player's Name</td>";
		echo "</tr>";
	for ($i=0; $i<count($boardids); $i++){
		$boardid = $boardids[$i];
		$playerid = getBoardInfo($matchid, $boardid, 'playerid');
		$player_firstname = getPlayerInfo($playerid, 'firstname');
		$player_lastname = getPlayerInfo($playerid, 'lastname');
		echo "<tr>";
			echo "<td><input type='checkbox' id='boardid$boardid' value='$boardid' /></td>";
			echo "<td>$boardid</td>";
			echo "<td>$player_firstname $player_lastname</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<input type='hidden' id='numBoards' value='".getMatchInfo($matchid, 'numBoards')."' />";
	
	// this is where the user begins to select the appropriate action
	echo "<br />With selected: <select id='action'>";
	echo "<option value='contChessMatch'>Close Polling</option>";
	echo "</select>";
	echo "<input type='button' id='submit_action' value='Go' />";
	
	// display the current director's message associated with each board
	echo "<h3><u>Your current messages for each board</u></h3>";
	for ($i=0; $i<count($boardids); $i++){
		$boardid = $boardids[$i];
		$director_message = getBoardInfo($matchid, $boardid, 'director_message');
		if (empty($director_message)){
			$director_message = "No message has been posted.";
		}
		echo "Board $boardid: $director_message<br />";
	}
	// display the form for the director to change the message
	echo "<form id='director_message_form'>";
	echo "New message: <input type='text' id='director_message' /> for board number:";
	echo "<select id='message_board'>";
		for ($i=1; $i<=count($boardids); $i++){
			echo "<option value=$i>$i</option>";
		}
	echo "</select>";
	echo "<br /><input type='submit' value='Change Message' />";
	echo "</form>";
	
	// display all the moves registered with their number of votes
	echo "<h3><u>Polling Results</u></h3>";
	for ($i=0; $i<count($boardids); $i++){
		$boardid = $boardids[$i];
		
		// display the title of the table
		$playerid = getBoardInfo($matchid, $boardid, 'playerid');
		$player_firstname = getPlayerInfo($playerid, 'firstname');
		$player_lastname = getPlayerInfo($playerid, 'lastname');
		include_once('../matches/match'.$matchid.'/board_settings'.$boardid.'.php');
		if ($board_settings['color'] == getBoardInfo($matchid, $boardid, 'color'))
			echo "<h3>Board $boardid: $player_firstname $player_lastname to play</h3>";
		else 
			echo "<h3>Board $boardid: $player_firstname $player_lastname awaiting turn</h3>";
		
		// display the votes in the table
		$i=1;
		$query = "select * from `match_votes$matchid`
							where `boardid` = $boardid
							order by `numvotes` desc;";
		$result = mysql_query($query, $conn);
		echo "<table border='1'>";
			echo "<tr>";
				echo "<td></td>";
				echo "<td>First Square</td>";
				echo "<td>Finishing Square</td>";
				echo "<td>Number of Votes</td>";
			echo "</tr>";
		while($chessmove = mysql_fetch_array($result)){
			// display all the moves voted so far, with the number of votes
			echo "<tr>";
				echo "<td>$i</td>";
				echo "<td>".NumToAlgebraicSquare($chessmove['firstsq'])."</td>";
				echo "<td>".NumToAlgebraicSquare($chessmove['lastsq'])."</td>";
				echo "<td>".$chessmove['numvotes']."</td>";
			echo "</tr>";
			$i++;
		}
		echo "</table>";
	}
	return true;
}

/**
 * using the status_code, figure out what stage that the match is in
 */
function getMatchStatusName($status_code){
	if ($status_code == 1)
		return "SettingNumBoards";
	if ($status_code == 2)
		return "ConfigBoards";
	if ($status_code == 400)
		return "view_rsvps";
	if ($status_code == 500)
		return "the_match_is_in_progress"; // will need to edit this status name
	return false;
}

function getStatusStr($status_code){
	if ($status_code == 1)
		return "Setting the number of boards";
	if ($status_code == 2)
		return "Entering information for each board";
	if ($status_code == 400)
		return "Pending the players' acceptance of your invitations";
	if ($status_code == 500)
		return "The match is currently in progress";
	return "No existing status exists for this status code";
}

?>
<?php

/**
 * this script stores all the functions related to scripting for the player's account
 */

include_once('database_fns.php');

function acceptInvitation($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		return "Error: there was a problem accessing the database. ".mysql_error();
		
	$matchid = mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$query = "update `match_players`
						set `status`=400
						where `matchid`=$matchid
						and `playerid`=$playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	else
		return true;
}

function declineInvitation($matchid, $playerid){
	$conn = db_connect();
	if (!$conn)
		return "Error: there was a problem accessing the database. ".mysql_error();
		
	$matchid = mysql_real_escape_string($matchid);
	$playerid = mysql_real_escape_string($playerid);
	$query = "update `match_players`
						set `status`=404
						where `matchid`=$matchid
						and `playerid`=$playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
	else
		return true;
}

function getInvitationStatusName($status_code){
	if ($status_code == 400)
		return 'Accepted';
	if ($status_code == 404)
		return 'Declined';
	if ($status_code == 200)
		return 'RSVP needed';
	return "No status name exists for $status_code";
	//return false;
}

function dispInvitations($playerid){
	$conn = db_connect();
	if (!$conn)
		return "Error: there was a problem accessing the database. ".mysql_error();
		
	$playerid = mysql_real_escape_string($playerid);
	$query = "select * from `match_players`
						where `playerid`=$playerid";
	$result = mysql_query($query, $conn);
	if (!$result){
		echo mysql_error(); 
		return;
	}
		
	if (mysql_num_rows($result) == 0){
		echo "You do not have any match invitations yet."; 
		echo "<input type='hidden' id='numInvitations' value='0' />";
		return;
	}
		
	$invitations = array();
	$numInvitations = 0;
	while ($row = mysql_fetch_array($result)){
		$invitations[$numInvitations] = $row['matchid'];
		$status[$numInvitations] = $row['status'];
		$numInvitations++;
	}
	// display the matchid, director name, match name
	echo "<table border='1'>";
		//top row
		echo "<tr>";
			echo "<td></td>";
			echo "<td>Match ID</td>";
			echo "<td>Match Name</td>";
			echo "<td>Director Name</td>";
			echo "<td>Your Choice</td>";
		echo "</tr>";
		//other rows
		for ($i=0; $i<$numInvitations; $i++){
			if ($status[$i] != 400 && $status[$i] != 404 && $status[$i] != 200)
				continue; // skip this iteration
			$matchid = $invitations[$i];
			$matchname = getMatchInfo($matchid, 'matchname');
			$status_name = getInvitationStatusName($status[$i]);
			$directorid = getMatchInfo($matchid, 'directorid'); 
			$director_firstname = getDirectorInfo($directorid, 'firstname');
			$director_lastname = getDirectorInfo($directorid, 'lastname');
			echo "<tr>";
				echo "<td><input type='checkbox' id='matchid$i' value='$matchid' /></td>";
				echo "<td>$matchid</td>"; // matchid
				echo "<td>$matchname</td>"; // match name
				echo "<td>$director_firstname $director_lastname</td>"; // director name
				echo "<td>$status_name</td>"; // the status name
			echo "</tr>";
		}
	echo "</table>";
	echo "<input type='hidden' id='numInvitations' value='$numInvitations' />";
}

function dispPlayerPic($playerid){
	// something to display the player's picture
}

function dispPlayerAccInfo($playerid){
	$conn = db_connect();
	if(!$conn)
		return "Error: there was a problem accessing the database. ".mysql_error();
		
	$playerid = mysql_real_escape_string($playerid);
	$query = "select * from `players`
						where `playerid`=$playerid
						limit 1;";
	$result = mysql_query($query, $conn);
	if(!$result)
		return mysql_error();
	$account_info = '';
	while ($player = mysql_fetch_array($result)){
		$account_info .= 'Email: '.$player['email'].'<br />';
		$account_info .= 'First name: '.$player['firstname'].'<br />';
		$account_info .= 'Last name: '.$player['lastname'].'<br />';
		$account_info .= 'Rating: '.$player['rating'].'<br />';
		$account_info .= 'Password: **********<br />';
		$account_info .= 'Player Identification Number: '.$player['playerid'].'<br />';
		return $account_info;
		break;
	}
}

function dispMatchBoards($matchid){
	$conn = db_connect();
	if(!$conn)
		return "Error: there was a problem accessing the database. ".mysql_error();
		
	$matchid = mysql_real_escape_string($matchid);
	$query = "select * from `match_boards$matchid`
						order by `boardid`";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
		
	if (mysql_num_rows($result) == 0){
		echo "There are no boards in this match.";
		return;
	}
		
	echo "<table border='1'>";
		echo "<tr>";
			echo "<td>Board Number</td>";
			echo "<td>Player</td>";
			echo "<td>Color</td>";
		echo "</tr>";
	while ($board = mysql_fetch_array($result)){
		echo "<tr>";
			$url = "playmatch.php?match=$matchid&board=".$board['boardid'];
			echo "<td><a href='$url'>".$board['boardid']."</a></td>";
			echo "<td>".getPlayerInfo($board['playerid'], 'firstname')." ".getPlayerInfo($board['playerid'], 'lastname')."</td>";
			echo "<td>";
				if ($board['color'] == 1)
					echo "White";
				else if ($board['color'] == 0)
					echo "Black";
			echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
}

function dispMatchesSignedupFor($playerid){
	$conn = db_connect();
	if(!$conn)
		return "Error: there was a problem accessing the database. ".mysql_error();
	
	$playerid = mysql_real_escape_string($playerid);
	$query = "select * from `match_players`
						where `playerid` = $playerid;";
	$result = mysql_query($query, $conn);
	if (!$result)
		return mysql_error();
		
	if (mysql_num_rows($result) == 0)
		return "You aren't playing in any matches right now";
	$output = "<table border='1'>";
		$output .= "<tr>";
			$output .= "<td>#</td>";
			$output .= "<td>Match ID</td>";
			$output .= "<td>Match Name</td>";
			$output .= "<td>Board(s)</td>";
		$output .= "</tr>";
	$numrows = 1;
	while($game = mysql_fetch_array($result)){
		$matchid = $game['matchid'];
		$matchname = getMatchInfo($matchid, 'matchname');
		if (isPlayerInMatch($matchid, $playerid)){
			$boardid = getBoardID($matchid, $playerid);
			$board_output = "<a href='http://".$_SERVER['SERVER_NAME']."/match_portal/playmatch.php?match=$matchid&board=$boardid'>$boardid</a>";
		} else
			$board_output = 'All boards';
		$match_output = "<a href='http://".$_SERVER['SERVER_NAME']."/match_portal/playmatch.php?match=$matchid'>$matchid</a>";
		$output .= "<tr>";
			$output .= "<td>$numrows</td>";
			$output .= "<td>$match_output</td>";
			$output .= "<td>$matchname</td>";
			$output .= "<td>$board_output</td>";
		$output .= "</tr>";
		$numrows++;
	}
	$output .= "</table>";
	return $output;
}

?>
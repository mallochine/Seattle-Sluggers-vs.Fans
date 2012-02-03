<?php 

/**
 * could probably produce a randomly generated matchid instead of doing it incrementally
 * any thoughts on increasing the security of this thing?
 */

include_once ('../lib/DispChessboard.inc.php');
include_once ('../lib/general.inc.php');
include_once ('../lib/LegalMoveGenerator.class.php');
include_once ('../lib/user_auth_fns.php');
include_once ('../lib/database_fns.php');
$DBconn = db_connect();

$match_name = $_POST['match_name'];
$username = $_POST['username'];
$password = $_POST['password'];

if (!IsPasswordCorrect($username, $password, 'director'))
	exit("The login information you provided is incorrect.");

$directorid = getDirectorID($username);
$match_id = rand(1000000,999999999);
	
// create an entry into the table that lists all the matches
$query = "INSERT INTO `matches` (`matchid`,`matchname`,`directorid`,`status`,`numBoards`) VALUES(
	$match_id, '$match_name', $directorid, 1, 0);";
mysql_query($query, $DBconn) or die ("Could not create match ".mysql_error());

$match_id = mysql_insert_id();
$match_boards_table = "match_boards".$match_id;
$match_users_table = "match_users".$match_id;
$match_votes_table = "match_votes".$match_id;
$match_players_table = "match_players".$match_id;

/**
 * edit: cut firstname, lastname from the table
 * edit: edit the playerbio to desc
 */
// create a table about the boards listed with a match
$query = "CREATE TABLE `$match_boards_table` (
					boardid int(50) NOT NULL AUTO_INCREMENT,
					playerid int(50) NOT NULL,
					color int(1) NOT NULL,
					description text(500),
					director_message text(500) DEFAULT '',
					status int(4) DEFAULT '200',
					PRIMARY KEY (boardid));";
mysql_query($query, $DBconn) or die ("Could not create the table for the match ".mysql_error());

// create a table to store all the ip addresses that have voted
$query = "create table `match_usersip$match_id`
					primaryid int(255) NOT NULL AUTO_INCREMENT,
					ipaddress int(50) NOT NULL";

// create a table about the users signed up to vote on a match/board
$query = "CREATE TABLE `$match_users_table` (
					primaryid int(255) NOT NULL AUTO_INCREMENT,
					boardid int(50) NOT NULL,
					playerid int(50) NOT NULL,
					status int(5) default '600',
					PRIMARY KEY (primaryid));";
mysql_query($query, $DBconn) or die("Could not create registration table ".mysql_error());

// create a table about votes on legal moves for a board
$query = "CREATE TABLE `$match_votes_table` (
					primaryid int(255) NOT NULL AUTO_INCREMENT,
					boardid int(50) NOT NULL,
					firstsq int(3) NOT NULL,
					lastsq int(3) NOT NULL,
					numvotes int(255) NOT NULL default '1',
					PRIMARY KEY (primaryid));";
mysql_query($query, $DBconn) or die("Could not create table for counting votes ".mysql_error());

// create the directory and store the files there
if(!mkdir ("../matches/match$match_id/", 0777, true)){
	echo "could not create directory for the matches";
}

echo 'success';

?>
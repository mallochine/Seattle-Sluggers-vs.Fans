<?php 

include_once('../lib/player_fns.php');

$action 		= $_POST['action'];
$numChecked = $_POST['numChecked'];
$playerid		= $_POST['playerid'];

if ($action == 'accept'){
	for ($i=0; $i<$numChecked; $i++){
		$matchid = $_POST['matchid'.$i];
		acceptInvitation($matchid, $playerid);
	}
} else if ($action == 'decline'){
	for ($i=0; $i<$numChecked; $i++){
		$matchid = $_POST['matchid'.$i];
		declineInvitation($matchid, $playerid);
	}
} else {
	exit("An appropriate action has not been specified");
}

echo true;

?>
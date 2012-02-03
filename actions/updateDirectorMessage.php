<?php 

include('../lib/database_fns.php');

$director_message = $_POST['director_message'];
$matchid = $_POST['matchid'];
$boardid = $_POST['boardid'];

$result = chgBoardInfo($matchid, $boardid, 'director_message', $director_message);
if(!$result)
	exit($result);
else
	exit("Your message is posted.");

?>
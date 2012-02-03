<?php 

include('../lib/database_fns.php');
$matchid = $_POST['matchid'];
$numBoards = $_POST['numBoards'];
chgMatchInfo($matchid, 'status', 2);
echo chgMatchInfo($matchid, 'numBoards', $numBoards);

?>
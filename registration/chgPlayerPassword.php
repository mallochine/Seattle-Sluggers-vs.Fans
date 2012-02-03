<?php

include("../lib/database_fns.php");

$password = $_POST['password'];
$email 		= $_POST['email'];
$key			= $_POST['key'];

$playerid = getPlayerID($email);

$result = confirmPlayer($email, $key);
if ($result != 1)
	exit($result);
	
$result = activatePlayer($email, $key);
if ($result != 1)
	exit($result);
	
$result = chgPlayerInfo($playerid, 'password', md5($password));
if ($result != 1)
	exit($result);
	
echo true;

?>
<?php 

include_once('./lib/database_fns.php');
include_once('./lib/user_auth_fns.php');

$username = $_POST['username'];
$password = $_POST['password'];
$account_type = $_POST['account_type'];
$result = login($username, $password, $account_type);

if($result){
// start the session if the user is authenticated
	session_start();
  if ($account_type == 'director'){
  	$session_name = 'valid_director';
		$_SESSION['username'] = $username;
		$_SESSION['directorid'] = getDirectorID($username);
		createSession($account_type);
		$url = 'director_index.php';
  } else { // account_type is user/player
  	$session_name = 'valid_user';
  	$_SESSION['username'] = $username;
  	$_SESSION['playerid'] = getPlayerID($username);
  	createSession($account_type);
  	$url = 'player_index.php';
  }

  header("location: $url");
} else if(!$result)
  header("location: login.php?mess=loginerror");
else
  echo $result;

?>
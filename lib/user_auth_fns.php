<?php

include_once("database_fns.php");

function login($username, $password, $account_type){
  if (IsPasswordCorrect($username, $password, $account_type)){
  	createSession($account_type); 
  	return true;
	} else 
  	return false;
}

function IsPasswordCorrect($username, $password, $account_type){
	$conn = db_connect();
	
	$encrypted_password = md5($password);
	if ($account_type == 'director'){
		$query = "select * from `tourney_directors`
							where `username`='$username'
							and `password`='$encrypted_password'
							limit 1;";
	} else if ($account_type == 'user'){
		$query = "select * from `players`
							where `email`='$username'
							and `password`='$encrypted_password'
							limit 1;";
	}
	
  $result = mysql_query($query, $conn);
  
  if (!$result)
    return false;
  if (mysql_num_rows($result)>0)
    return true;
  else 
    return false;
}

function generatefingerprint(){
	$result = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
	return $result;
}

function createSession($account_type){
	session_regenerate_id(); 
	$fingerprint = generatefingerprint();
	$_SESSION['last_active'] = time();
	$_SESSION['fingerprint'] = $fingerprint;
	$_SESSION['account_type'] = $account_type;
	return true;
}

//assumes that session_start() has already been called
function CheckLoginValid($account_type='user'){
//checks to see if the user is logged in
	if ($_SESSION['account_type'] != $account_type)
		return false;
	
  $timeout = 60 * 30; // In seconds, i.e. 30 minutes.
  $fingerprint = generatefingerprint();
  
	if ($_SESSION['last_active'] < (time()-$timeout)
     || $_SESSION['fingerprint'] != $fingerprint)
  // if the session is hijacked or timed out, then destroy the session
	{
    //echo("<script>window.location.href='logout.php';</script>");
    return false;
	} else {
		createSession($account_type);
		return true;
	}
}

?>
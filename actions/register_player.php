<?php 

include("../lib/database_fns.php");

// get the parameters
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$rating = $_POST['rating'];
$email = $_POST['email']; // this is incorrect code
$account_type = $_POST['account_type'];
$passwd = rand(1000000, 999999999);

if ($firstname == '' || $lastname == '')
	exit('The first name or the last name is not filled out');

if (IsPlayerRegistered($email))
	exit('The player is already registered in our database');

$playerinfo = array(
	'firstname'	=> $firstname,
	'lastname' 	=> $lastname,
	'rating'		=> $rating,
	'email'			=> $email,
	'passwd' 		=> $passwd
);

$result = insertPlayer($playerinfo);
if($result != 1)
	exit("a confirmation email has not been sent because: $result");

$key = md5($passwd);
$deactivate_url  = "http://".$_SERVER['SERVER_NAME']."/match/registration/deactivate_account.php?";
$deactivate_url .= "key=$key&";
$deactivate_url .= "email=$email";
$activate_url  = "http://".$_SERVER['SERVER_NAME']."/match/registration/activate_account.php?";
$activate_url .= "key=$key&";
$activate_url .= "email=$email";

$message 	= "Hello $firstname, "; // \n\n
if ($account_type == 'director')
	$message .= "a director signed you up for an account. ";
else 
	$message .= "you signed up for an account. ";
$message .= "To activate your account, click this link:";
$message .= "$activate_url. ";
$message .= ""; // \n
$message .= "If this is a mistake, you can deactivate your account, by clicking this link:";
$message .= "$deactivate_url";

$headers = "From: the Seattle Sluggers"; // the qmail server doesn't accept this heade

if(mail($email, "Please confirm your account with us", $message))
	echo "<br />Confirmation email has been successfully sent.";
else
	echo "<br />Due to a technical difficulty, the confirmation email could not be sent.";

?>
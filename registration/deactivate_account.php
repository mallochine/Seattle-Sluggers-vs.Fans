<?php

include('../lib/database_fns.php');

$email = $_GET['email'];
$key 	 = $_GET['key'];

$result = deactivatePlayer($email, $key);
if ($result == 1)
	echo "Your account was successfully deactivated";
else
	echo "Something went wrong in deactivating your account. ".mysql_error();

?>
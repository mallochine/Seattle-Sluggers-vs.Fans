<?php
	include_once('../lib/user_auth_fns.php');

  session_start();
  if(!CheckLoginValid('director'))
  	echo "<script>window.location.href='logout.php';</script>";
?>
<?php

include_once("../lib/user_auth_fns.php");

session_start();
if (!CheckLoginValid('user'))
	echo "<script>window.location.href='logout.php';</script>"; // um should probably do something else

?>
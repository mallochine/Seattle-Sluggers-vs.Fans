<?php

/**
 * the purpose of this script is to load the player's account information
 */

include('../lib/player_fns.php');
session_start();
echo "<h2>Account Information</h2>";
echo dispPlayerAccInfo($_SESSION['playerid']);

?>
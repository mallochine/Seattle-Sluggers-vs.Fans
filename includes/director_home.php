<?php

include_once('../headers/validate_director.php');
include_once('../lib/match_wizard_fns.php');
session_start();

echo "<h2>Account Information</h2>";
echo dispDirectorAccInfo($_SESSION['directorid']);

?>
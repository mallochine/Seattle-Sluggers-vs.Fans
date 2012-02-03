<?php 

include_once('../headers/validate_player.php');
include_once('../lib/player_fns.php');

session_start();
$playerid = $_SESSION['playerid'];

?>

<h2>Matches You Are Currently Signed Up For</h2>

<?php echo dispMatchesSignedupFor($playerid);?>
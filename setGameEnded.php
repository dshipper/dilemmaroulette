<?php

include("inc/dbconn.php");
$g = $_GET['g'];
$ended = GameState::ENDED;
$sql = "UPDATE `games` SET `state` = $ended WHERE `id`=$g";
$result = mysql_query($sql);
print "1";

?>
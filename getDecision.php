<?php
/*
	This file will get take an opponent's user id, and a game id and return a new decision from that game if one is found.
*/
include("inc/dbconn.php");
$opponent_id = $_GET['o'];
$game_id = $_GET['g'];

if(isset($_GET['o']) && isset($_GET['g'])){
	$sql = "SELECT * FROM `decisions` WHERE `game_id` = '$game_id' AND `user_id` = '$opponent_id' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);  
	if($row['id']){
		//then we found something
		print $row['decision'];
	}
}


?>

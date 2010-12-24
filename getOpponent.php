<?php
include("inc/dbconn.php"); 
$peer_id = $_GET['p'];  
$waiting = GameState::WAITING;  
$in_progress = GameState::IN_PROGRESS;  

$sql = "SELECT * FROM `users` WHERE `peer_id` = '$peer_id' LIMIT 1";   
$result = mysql_query($sql);
if($row = mysql_fetch_array($result)){
	$user_id = $row['id'];
}                                               

$sql = "SELECT * FROM `games` WHERE `state`=$waiting LIMIT 1";
$result = mysql_query($sql);

if($row = mysql_fetch_array($result)){ 
	$sql = "SELECT * FROM `users` WHERE `id`=".$row['user_one']." LIMIT 1";
	$result = mysql_query($sql);
	$opp = mysql_fetch_array($result);
	$opponent_peer_id = $opp['peer_id'];
	$sql = "UPDATE `games` SET state=$in_progress, user_two = $user_id WHERE id = ".$row['id'];  
	mysql_query($sql);                       
	print "".$row['user_one']."/".$row['id']."/".$row['type']."/".$opponent_peer_id;
}                       
else{   
	$game_type = mt_rand(1,4);
	$sql = "INSERT INTO `games` (user_one,type) VALUES ('$user_id', '$game_type')";
	mysql_query($sql);
	$game_id = mysql_insert_id();
	print "$game_type/$game_id";
}


?>
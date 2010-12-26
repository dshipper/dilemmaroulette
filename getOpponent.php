<?php
include("inc/dbconn.php"); 
$user_id = $_GET['u'];  
$waiting = GameState::WAITING;  
$in_progress = GameState::IN_PROGRESS;  


$sql = "SELECT * FROM `games` WHERE `state`= '$waiting' AND `user_one` != '$user_id' AND `user_two` != '$user_id' LIMIT 1";   
$result = mysql_query($sql);
$row = mysql_fetch_array($result);   
$u = $row['user_one'];
if($u != 0){ 
	$sql = "SELECT * FROM `users` WHERE `id`=".$row['user_one']." LIMIT 1";
	$result = mysql_query($sql);
	$opp = mysql_fetch_array($result);
	$opponent_peer_id = $opp['peer_id'];
	$sql = "UPDATE `games` SET state=$in_progress, user_two = $user_id WHERE id = ".$row['id'];  
	mysql_query($sql);                       
	print "".$row['user_one']."/".$row['id']."/".$row['type']."/".$opponent_peer_id;
}                       
else{
	$sql = "SELECT * FROM `games` WHERE `state`='$waiting' AND `user_one` = '$user_id' AND `user_two` = 0 LIMIT 1";
	$r = mysql_query($sql);
	$row = mysql_fetch_array($r);     
	if(!$row['id']){  
		//$game_type = mt_rand(0,3);  
		$game_type = GameType::ROCK_PAPER_SCISSORS;
		$sql = "INSERT INTO `games` (user_one,type) VALUES ('$user_id', '$game_type')";
		mysql_query($sql);
		$game_id = mysql_insert_id();
		print "$game_type/$game_id";     
   }
   else{  	                                 
		$game_type = $row['type'];
		$game_id = $row['id'];
		print "$game_type/$game_id"; 
   }
}


?>
<?php
include("inc/dbconn.php"); 
$user_id = $_GET['u'];
$known = $_GET['k'];  
$waiting = GameState::WAITING;
$waiting_for_user = GameState::WAITING_FOR_USER;  
$in_progress = GameState::IN_PROGRESS;  

if($known == 0){
	$sql = "SELECT * FROM `games` WHERE `state`= '$waiting' AND `user_one` != '$user_id' AND `user_two` != '$user_id' LIMIT 1"; 
}   
else{
	$sql = "SELECT * FROM `games` WHERE `state` = '$waiting_for_user' AND `user_one` != '$user_id' AND `user_two` != '$user_id' LIMIT 1"; 
}
$result = mysql_query($sql);
$row = mysql_fetch_array($result);   
$u = $row['user_one'];
if($u != 0){   
	if($known == 1){
		//print "!Got the U PIECE";
	}
	$sql = "SELECT * FROM `users` WHERE `id`=".$row['user_one']." LIMIT 1";
	$result = mysql_query($sql);
	$opp = mysql_fetch_array($result);
	$opponent_peer_id = $opp['peer_id'];
	$sql = "UPDATE `games` SET state=$in_progress, user_two = $user_id WHERE id = ".$row['id'];  
	mysql_query($sql);                       
	print "".$row['user_one']."/".$row['id']."/".$row['type']."/".$opponent_peer_id;
}                       
else{
	if($known == 0){
		$sql = "SELECT * FROM `games` WHERE `state`='$waiting' AND `user_one` = '$user_id' AND `user_two` = 0 LIMIT 1";    
	}   
	else{
	   //first get his last game
		$sql = "SELECT * FROM `games` WHERE `state`='0' AND (`user_one` = '$user_id' OR `user_two` = '$user_id') ORDER BY `id` DESC LIMIT 1";   
		//print $sql;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result); 
		$opponent_id = "";
		if($row['id']){
			if($row['user_one'] == $user_id){
				$opponent_id = $row['user_two'];
			}                                   
			else{
				$opponent_id = $row['user_one'];
			} 
			$game_type = $row['type'];
		}
		$sql = "SELECT * FROM `games` WHERE `state`='$waiting_for_user' AND `user_one` = '$user_id' AND `user_two` = 0 LIMIT 1";  
	}
	$r = mysql_query($sql);
	$row = mysql_fetch_array($r);        
	if($known == 0){
		if(!$row['id']){  
			$game_type = mt_rand(0,2);
			//$game_type = 1;
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
   else{
   	   $sql = "INSERT INTO `games` (`user_one`,`type`,`state`) VALUES ('$user_id', '$game_type', '$waiting_for_user')";    
	   mysql_query($sql);                                                                                           
	   $game_id = mysql_insert_id();
	   print "$game_type/$game_id";
   }		
}


?>
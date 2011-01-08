<?php
include("inc/dbconn.php");

//As a rule a decision of -1 = the guy who made it quit
//Any decision of -2 means that we have already run the score counter.

$homeScore = 0;
$awayScore = 0;
$opponentQuit = 0; 
$homeDecision = -2;
$awayDecision = -2; 

function checkAdvanceRank($old_score, $new_score){     
	$old_rank = getRank($old_score);
	$new_rank = getRank($new_score);  
	if($old_rank == $new_rank){
		//do nothing     
	   setcookie("leveledup", "tie-$old_rank-$new_rank");
	}               
	else if($old_rank < $new_rank){
		//leveled up  
		setcookie("levelup", "leveledup");
		
	}               
	else if($old_rank > $new_rank){
		//leveled down
		setcookie("levelup", "leveleddown");
		
		
	} 
	else{
	    setcookie("fuck", "shit");
	}
	
}


$user_id = $_GET['u'];
$opponent_id = $_GET['o'];

$sql = "SELECT * FROM `games` WHERE (`user_one` = '$user_id' AND `user_two` = '$opponent_id') OR (`user_one` = '$opponent_id' AND `user_two` = '$user_id') ORDER BY `id` DESC LIMIT 1";
$result = mysql_query($sql);          

$game = mysql_fetch_array($result);
$game_type = $game['type'];
$game_id = $game['id'];

$sql = "SELECT * FROM `decisions` WHERE `game_id` = '$game_id'";
$result = mysql_query($sql);  

while($decision = mysql_fetch_array($result)){
	if($decision['user_id'] == $user_id){
		$homeDecision = $decision['decision'];
	}   
	else if($decision['user_id'] == $opponent_id){
	    $awayDecision = $decision['decision'];
		if($awayDecision == '-1'){
			$opponentQuit = 1;
		}
	}
}


//this counts up the scores of all the users based on what type of game we have   
if($game_type == GameType::ROCK_PAPER_SCISSORS){ 
	if(($homeDecision == RPS::ROCK && $awayDecision == RPS::PAPER) || ($homeDecision == RPS::PAPER && $awayDecision == RPS::SCISSOR)|| ($homeDecision == RPS::SCISSOR && $awayDecision == RPS::ROCK)){
		//we lost dude      
		$homeScore += -10;
		$awayScore += 10;
	}                                                                                                        
	else if(($awayDecision == RPS::ROCK && $homeDecision == RPS::PAPER) || ($awayDecision == RPS::PAPER && $homeDecision == RPS::SCISSOR)|| ($awayDecision == RPS::SCISSOR && $homeDecision == RPS::ROCK)){
		//we won         
		$homeScore += 10;
		$awayScore += -10;
	}                                                                                               
	else if($homeDecision == $awayDecision){  
		//we tied so don't do anything
	} 
	else if($awayDecision == "-1"){
		//the other guy quit 
		$homeScore = 20;
		$awayScore = -20; 
		$opponentQuit = 1;   
		//print "quit";
	}                       
}
else if($game_type == GameType::PRISONER){    
	if($homeDecision == PRISONER::SILENT && $awayDecision == PRISONER::SQUEAL){
		//lost
		$homeScore += -10;
		$awayScore += 20;     
		//print "lost";
	}         
	else if($homeDecision == PRISONER::SQUEAL && $awayDecision == PRISONER::SILENT){
		//won   
		$homeScore += 20;
		$awayScore += -10; 
		//print "won";
	}        
	else if($homeDecision == PRISONER::SQUEAL && $awayDecision == PRISONER::SQUEAL){
		//tie-b 
		$homeScore += -20;
		$awayScore += -20;  
		//print "tie-b";
	}          
	else if(($homeDecision == PRISONER::SILENT) && ($awayDecision == PRISONER::SILENT)){
		//tie-g 
		$homeScore += 10;
		$awayScore += 10; 
		//print "tie-g";
	}
	else if($awayDecision == "-1"){
		//the other guy quit 
		$homeScore = 20;
		$awayScore = -20; 
		$opponentQuit = 1;     
		//print "quit";
	}
}
else if($game_type == GameType::STAG_HUNT){
	if($homeDecision == STAG::STAG && $awayDecision == STAG::STAG){
		$homeScore += 20;
		$awayScore += 20;
	}                           
	else if($homeDecision == STAG::STAG && $awayDecision == STAG::HARE){
		$homeScore += -20;
		$awayScore += 5;
	}                          
	else if($homeDecision == STAG::HARE && $awayDecision == STAG::STAG){
		$homeScore += 5;
		$awayScore += -20;
	}                           
	else if($homeDecision == STAG::HARE && $awayDecision == STAG::HARE){
		$homeScore += 5;
		$awayScore += 5;
	}
	else if($awayDecision == "-1"){
		//the other guy quit 
		$homeScore = 20;
		$awayScore = -20; 
		$opponentQuit = 1;  
		//print "quit";
	}
}

                                

if($opponentQuit){
	$sql = "UPDATE `users` SET `score` = `score` + $awayScore WHERE `id` = '$opponent_id'";
	$result = mysql_query($sql);	
} 

//now we get our old score
$sql = "SELECT `score` FROM `users` WHERE `id`= '$user_id'";
$result = mysql_query($sql);
$user = mysql_fetch_array($result);

$old_score = $user['score']; 
$new_score = $old_score + $homeScore;
checkAdvanceRank($old_score, $new_score);

$sql = "UPDATE `users` SET `score` = `score` + $homeScore WHERE `id` = '$user_id'";
$result = mysql_query($sql);
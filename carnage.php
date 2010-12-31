<?php
include("inc/dbconn.php");

//As a rule a decision of -1 = the guy who made it quit
//Any decision of -2 means that we have already run the score counter.  

function processScores($item, $key, $game){
	//this counts up the scores of all the users based on what type of game we have   
	if($game->game_type == GameType::ROCK_PAPER_SCISSORS){ 
		if(($item->homeUserDecision == RPS::ROCK && $item->awayUserDecision == RPS::PAPER) || ($item->homeUserDecision == RPS::PAPER && $item->awayUserDecision == RPS::SCISSOR)|| ($item->homeUserDecision == RPS::SCISSOR && $item->awayUserDecision == RPS::ROCK)){
			//we lost dude      
			$game->homeScore += -10;
			$game->awayScore += 10;
		}                                                                                                        
		else if(($item->awayUserDecision == RPS::ROCK && $item->homeUserDecision == RPS::PAPER) || ($item->awayUserDecision == RPS::PAPER && $item->homeUserDecision == RPS::SCISSOR)|| ($item->awayUserDecision == RPS::SCISSOR && $item->homeUserDecision == RPS::ROCK)){
			//we won         
			$game->homeScore += 10;
			$game->awayScore += -10;
  		}                                                                                               
		else if($item->homeUserDecision == $item->awayUserDecision){  
			//we tied so don't do anything
    	} 
		else if($item->awayUserDecision == "-1"){
			//the other guy quit 
			$game->homeScore = 20;
			$game->awayScore = -20; 
			$game->opponentQuit = 1;
		}                       
	}
	
}


class Game{
	public $homeUser = "";
	public $awayUser = "";
	public $game_type = "";
	public $homeScore = 0;
	public $awayScore = 0;
	public $alreadyRan = 0;
	public $opponentQuit = 0;
	public $results = array();
	
	function __construct($home, $away, $updateDB){
		$this->homeUser = $home;
		$this->awayUser = $away;
		
		$sql = "SELECT * FROM `games` WHERE (`user_one` = '$home' AND `user_two` = '$away') OR (`user_one` = '$away' AND `user_two` = '$home')";
		$result = mysql_query($sql);                                                                 
	    $game_id = "";
		$round = 0; 
		while($row = mysql_fetch_array($result)){
			$this->game_type = $row['type'];                           
			$game_id = $row['id'];
			$sql = "SELECT * FROM `decisions` WHERE `game_id` = '$game_id'";
			$decisions_result = mysql_query($sql);   
			$homeDecision = -5;
			$awayDecision = -5;
			while($decisions_row = mysql_fetch_array($decisions_result)){ 
	        	if($decisions_row['user_id'] == $this->homeUser){
		        	$homeDecision = $decisions_row['decision'];
				}
				else if($decisions_row['user_id'] == $this->awayUser){
					$awayDecision = $decisions_row['decision'];
				}
				else if($decisions_row['decision'] == -2){
					$this->alreadyRan = 1;
				}
		   	}
			if($homeDecision != -5 && $awayDecision != -5){
				array_push($this->results, new roundInformation($round, $homeDecision, $awayDecision)); 
				$round += 1;                                   
			}
		}
		array_walk($this->results, 'processScores', $this);
		if($this->alreadyRan == 0 && $updateDB == 1){
			//we should upload the home user score to the db 
			$sql = "UPDATE `games` SET `score` = `score` + $this->homeScore WHERE `id` = $this->homeUser";
			$result = mysql_query($sql);
			if($this->opponentQuit){
				$sql = "UPDATE `games` SET `score` = `score` + $this->awayScore WHERE `id` = $this->awayUser";
				$result = mysql_query($sql);
			}
			//now make sure that no one can run this again...
			$sql = "INSERT INTO `decisions` (`user_id`, `game_id`,`decision`) VALUES (-1, $game_id, -2)";
			$result = mysql_query($sql);
		} 
		
	
	}
	
	public function getResult(){                
		if($this->homeScore > $this->awayScore){
			return "won";
		}               
		else if($this->homeScore < $this->awayScore ){
			return "lost";
		}                 
		else{
			return "tie";
		}
	}
	
	
	
}
class roundInformation{
	public $round = "";
	public $homeUserDecision = 0;
	public $awayUserDecision = 0;
	public $result = "";
	
	function __construct($r, $home, $away){
		$this->round = "$r";
		$this->homeUserDecision = $home;
		$this->awayUserDecision = $away;
	}
}

$user_id = $_GET['u'];
$opponent_id = $_GET['o']; 

$game = new Game($user_id, $opponent_id, 1); 
$result = $game->getResult();                 
if($result == "won"){
	print "Congrats you won!";
}                             
else if($result == "lost"){
	print "You suck you lost.";
}                              
else{
	print "Ehh you tied.";
}



?>
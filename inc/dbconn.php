<?php
//mysql_connect("localhost", "root", "root") or die(mysql_error());
//mysql_select_db("dilemmaroulette") or die(mysql_error()); 
  
mysql_connect('localhost', 'dshipper_dilemma', 'v^iv$XqP');
mysql_select_db('dshipper_dilemma');

session_start();
  
class GameState{ 
	const WAITING_FOR_USER = 3;
	const WAITING = 2;
	const IN_PROGRESS = 1;
	const ENDED = 0;
}          

class GameType{
	const ROCK_PAPER_SCISSORS = 0;
	const PRISONER = 1;
	const STAG_HUNT = 2;
	const BLOTTO = 3;
}

class RPS {
	const ROCK = 0;
	const PAPER = 1;
	const SCISSOR = 2;
}

class PRISONER{
	const SILENT = 0;
	const SQUEAL = 1;
} 

class STAG{
	const STAG = 0;
    const HARE = 1;
}

class Ranks{
	const PRIVATE_FIRST_CLASS = 0;
	const CORPORAL = 1;
	const SERGEANT = 2;
	const STAFF_SERGEANT = 3;
	const MASTER_SERGEANT = 4;
	const SERGEANT_MAJOR = 5;
	const CAPTAIN = 6;
	const MAJOR = 7;
	const COLONEL = 8;
	const GENERAL = 9;
}

$ranksArray = array("Private First Class", "Corporal", "Sergeant", "Staff Sergeant", "Master Sergeant", "Sergeant Major", "Captain", "Major", "Colonel", "General"); 

function genRandomString($length){
	if(!$length){$length = 254;}
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()_=+?';
	$string = "";
	for($p = 0; $p < $length; $p++){
		$string .= $characters[mt_rand(0, strlen($characters-1))];
	}                                                           
	return $string;
}   

function validateEmail($email){
	if(preg_match("/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/", $email)){
		return 1;
	}
	else{
		return 0;
	}
}      

function getRank($score){
	if($score <= 0){
		return Ranks::PRIVATE_FIRST_CLASS; 
	}                                   
	else if(1 <= $score && $score <= 99){
		return Ranks::CORPORAL;
	} 
	else if(100 <= $score && $score <= 199){
		return Ranks::SERGEANT;
	}                              
	else if(200 <= $score && $score <= 299){
		return Ranks::STAFF_SERGEANT;
	}                               
	else if(300 <= $score && $score <= 399){
		return Ranks::MASTER_SERGEANT;
	}                               
	else if(400 <= $score && $score <= 499){
		return Ranks::SERGEANT_MAJOR;
	}                        
	else if(500 <= $score && $score <= 599){
		return Ranks::CAPTAIN;
	}                        
	else if(600 <= $score && $score <= 699){
		return Ranks::MAJOR;
	}                      
	else if(700 <= $score && $score <= 799){
		return Ranks::COLONEL;
	}                        
	else if ($score >= 800){
		return Ranks::GENERAL;
	}   
}

?>

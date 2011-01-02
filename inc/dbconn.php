<?php
mysql_connect("localhost", "root", "root") or die(mysql_error());
mysql_select_db("dilemmaroulette") or die(mysql_error()); 
  
//mysql_connect('localhost', 'dshipper_dilemma', 'v^iv$XqP');
//mysql_select_db('dshipper_dilemma');

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

?>

<?php
mysql_connect("localhost", "root", "root") or die(mysql_error());
mysql_select_db("dilemmaroulette") or die(mysql_error()); 
	
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
?>

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
?>

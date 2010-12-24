<?php
mysql_connect("localhost", "root", "root") or die(mysql_error());
mysql_select_db("dilemmaroulette") or die(mysql_error()); 
	
class GameState{
	const WAITING = 2;
	const IN_PROGRESS = 1;
	const ENDED = 0;
}          

class GameType{
	const PRISONER = 1;
	const STAG = 2;
	const RPS = 3;
	const BLOTTO = 4;
}
?>

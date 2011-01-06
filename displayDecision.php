<?php
include("inc/dbconn.php");
if(!isset($_GET['g'])){header("Location: http://google.com");}
$game_type = $_GET['g'];
if($game_type == GameType::ROCK_PAPER_SCISSORS){
	$decision_framework = "<input type='radio' class='rock' name='decision' id='decision'/>Rock<br><br>  
	<input type='radio' class='paper' name='decision' id='decision'/>Paper<br><br>  
	<input type='radio' class='scissor' name='decision' id='decision'/>Scissor<br><br>  
	<input type='submit' value=\"Shoot!\"/>";					
}
else if($game_type == GameType::PRISONER){                                                               
	$decision_framework = "<input type='radio' class='squeal' name='decision' id='decision'/>Squeal<br><br>  
	<input type='radio' class='silent' name='decision' id='decision'/>Stay silent<br><br>      
	<input type='submit' value=\"That's my final answer\"/>";
}   
else if ($game_type == GameType::STAG_HUNT){                               
	$decision_framework = "<input type='radio' class='stag' name='decision' id='decision'/>Stag<br><br>
	<input type='radio' class='hare' name='decision' id='decision'/>Hare<br><br>    
	<input type='submit' value=\"That's my final answer\"/>";
}     
else{
	
}

print "<b>Decide</b><br>";
?> 
<br>
<form action="javascript:game.makeDecision()" name="decisionForm">
<?php print $decision_framework; ?>
</form>      
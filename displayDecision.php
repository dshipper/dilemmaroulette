<?php
include("inc/dbconn.php");
if(!isset($_GET['g'])){header("Location: http://google.com");}
$game_type = $_GET['g'];
if($game_type == GameType::ROCK_PAPER_SCISSORS){
	$description = "Just your garden variety rocks, paper scissors game. Scissors beats paper. Paper beats rock. Rock beats scissors. 
						Best of three rounds (not including ties) wins 50 points.";
	$decision_framework = "<input type='radio' class='rock' name='decision' id='decision'/>Rock<br>
	<input type='radio' class='paper' name='decision' id='decision'/>Paper<br>
	<input type='radio' class='scissor' name='decision' id='decision'/>Scissor<br>
	<input type='submit' value=\"Shoot!\"/>";					
}
else if($game_type == GameType::PRISONER){
	$description = "Prisoner's Dilemma. Are you gonna screw over your partner or are you trustworthy?";
	$decision_framework = "<input type='radio' class='squeal' name='decision' id='decision'/>Squeal<br>
	<input type='radio' class='silent' name='decision' id='decision'/>Stay silent<br>    
	<input type='submit' value=\"That's my final answer\"/>";
}   
else if ($game_type == GameType::STAG_HUNT){
	
}   
else if ($game_type == GameType::BLOTTO){
	
}   
else{
	
}

print $description;
?> 
<br>
<form action="javascript:game.makeDecision()" name="decisionForm">
<?php print $decision_framework; ?>
</form>      
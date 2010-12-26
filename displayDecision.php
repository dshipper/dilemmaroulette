<?php
include("inc/dbconn.php");
if(!isset($_GET['g'])){header("Location: http://google.com");}
$game_type = $_GET['g'];
if($game_type == GameType::ROCK_PAPER_SCISSORS){
	$description = "Just your garden variety rocks, paper scissors game. Scissors beats paper. Paper beats rock. Rock beats scissors. 
						Best of three rounds (not including ties) wins 50 points.";
	$decision_framework = "<input type='radio' class='decision' name='decision' id='decision'/>Rock<br>
	<input type='radio' class='decision' name='decision' id='decision'/>Paper<br>
	<input type='radio' class='decision' name='decision' id='decision'/>Scissor<br>
	<input type='submit' value=\"That's My Final Answer\"/>";					
}
else if($game_type == GameType::PRISONER){
	
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
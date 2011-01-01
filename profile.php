<?php
include("inc/dbconn.php");
include("inc/head.php");

$user_id = $_COOKIE['user_id'];
if(isset($_GET['u'])){ 
	//then we're looking for someone elses profile
	$user_id = $_GET['u'];                        
}                                                 

$sql = "SELECT * FROM `users` WHERE `id` = '$user_id' LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
if($row['id']){
	if(!isset($_GET['u'])){
		print "Welcome back, ".$row['first_name'];
	}
	else{
		print "Profile information for ".$row['first_name'];
	}
	print "<br><br>";
	$sql = "SELECT * FROM `games` WHERE `user_one` = '".$row['id']."' OR `user_two` = '".$row['id']."'";
	$result = mysql_query($sql);
	$games_played = 0;
	while($games = mysql_fetch_array($result)){
		$games_played += 1;
	}
	print "This user has played: $games_played games.<br>";
	print "This user has scored: ".$row['score']." points through those games.<br>";
	print "This user has a rank of: General.<br>";    
	print "<a href='game.php'>Start new game</a>";
}   
else{
	print "Sorry that user does not exist in our database. If you would like to view your profile please click <a href='profile.php'>here</a>";
}



?>
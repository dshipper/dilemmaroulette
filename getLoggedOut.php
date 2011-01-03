<?php

include("inc/dbconn.php");
$o = $_GET['o'];
$u = $_GET['u'];
$sql = "SELECT * FROM `users` WHERE `id`=$o LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$quit = 0;
if($row['last_updated'] >= (time()-20)){
	//do nothing
	$sql = "SELECT * FROM `games` WHERE (`state` = '2' OR `state`='1') AND ((`user_one` = '$o' AND `user_two` != '$u') OR (`user_one` != '$u' AND `user_two` = '$o'))";
	$result = mysql_query($sql);
	$error = 0;
	while($row = mysql_fetch_array($result)){
		$error = 1;
	}
	if($error == 1){  
		$quit = 1;
		print "quit";
	}
}    
else{  
	$quit=1;
	print "quit";
}

if($quit == 1){
	$sql = "SELECT * FROM `games` WHERE (`user_one` = '$u' AND `user_two` = '$o') OR (`user_one` = '$o' AND `user_two` = '$u') ORDER BY `id` DESC LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$game_id = $row['id'];
	 
	$sql = "INSERT INTO `decisions` (`user_id`, `game_id`, `decision`) VALUES ('$o', '$game_id', '-1')";    
	$result = mysql_query($sql);
}

?>
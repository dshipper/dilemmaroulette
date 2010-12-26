<?php

include("inc/dbconn.php");
$o = $_GET['o'];
$u = $_GET['u'];
$sql = "SELECT * FROM `users` WHERE `id`=$o LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
if($row['last_updated'] >= (time()-12)){
	//do nothing
	$sql = "SELECT * FROM `games` WHERE (`state` = '2' OR `state`='1') AND ((`user_one` = '$o' AND `user_two` != '$u') OR (`user_one` != '$u' AND `user_two` = '$o'))";
	$result = mysql_query($sql);
	$error = 0;
	while($row = mysql_fetch_array($result)){
		$error = 1;
	}
	if($error == 1){
		print "quit";
	}
}    
else{
	print "quit";
}

?>
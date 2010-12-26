<?php
include("inc/dbconn.php");
if(isset($_GET['u']) && isset($_GET['g']) && isset($_GET['d'])){
	$user_id = $_GET['u'];                                                                                       
	$game_id = $_GET['g'];
	$decision = $_GET['d'];

	$sql = "INSERT INTO `decisions` (`user_id`, `game_id`, `decision`) VALUES ('$user_id', '$game_id', '$decision')";   
	print $sql;
	mysql_query($sql);
}
?>
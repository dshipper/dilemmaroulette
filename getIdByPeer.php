<?php
	include("inc/dbconn.php");
	$p = $_GET['p'];
	$sql = "SELECT * FROM `users` WHERE `peer_id` = '$p' LIMIT 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);                          
	print $row['id'];
?>
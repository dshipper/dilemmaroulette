<?php

include("inc/dbconn.php");

$user_id = $_GET['u'];
$peer_id = $_GET['p'];
$sql = "UPDATE `users` SET `peer_id`='$peer_id' WHERE `id`=$user_id";
mysql_query($sql);  
print $sql;

?>
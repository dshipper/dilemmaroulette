<?php

include("inc/dbconn.php");
$o = $_GET['o'];
$sql = "SELECT * FROM `users` WHERE `id`=$o LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
if($row['last_updated'] >= (time()-12)){
	//do nothing
}    
else{
	print "quit";
}

?>
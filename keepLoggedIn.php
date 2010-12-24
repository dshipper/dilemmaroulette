<?php         

include("inc/dbconn.php");
$user_id = $_GET['u'];
$sql = "UPDATE `users` SET last_updated = ".time()." WHERE `id` = $user_id";      
print $sql;  
mysql_query($sql);           

?>        

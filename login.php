<?php

$id = $_GET['id'];
setcookie("user_id", $id);    
header("Location: profile.php?u=$id");

?>
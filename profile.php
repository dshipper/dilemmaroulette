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
	
	$rank = $ranksArray[getRank($row['score'])];
	print "This user has a rank of: $rank <br>";    
	print "<a href='game.php'>Start new game</a>"; 
	
	# We require the library
	require("facebook.php");

	# Creating the facebook object
	$facebook = new Facebook(array(
		'appId'  => '166764346686167',
		'secret' => 'a8b376685a10465f4b49cf7fafb90b3b',
		'cookie' => true
	));

	# Let's see if we have an active session
	$session = $facebook->getSession();

	if(!empty($session)) {
		# Active session, let's try getting the user id (getUser()) and user info (api->('/me'))
		try{
			$uid = $facebook->getUser();
			$user = $facebook->api('/me');
		} catch (Exception $e){}

		if(!empty($user)){
			try{ 
				$friends = $facebook->api('/me/friends');
				print_r($friends);
			}catch(Exception $o){
				print "Exception: $o";
			}                         
			
		}
	}

			
			
}   
else{
	print "Sorry that user does not exist in our database. If you would like to view your profile please click <a href='profile.php'>here</a>";
}



?>
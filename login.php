<?php
include("inc/dbconn.php"); 
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
	} catch (Exception $e){
		print "ERROR: $e";
	}
	
	if(!empty($user)){
		# We have an active session, let's check if we have already registered the user
		$query = mysql_query("SELECT * FROM users WHERE oauth_provider = 'facebook' AND oauth_uid = ". $user['id']);
		$result = mysql_fetch_array($query);
		
		# If not, let's add it to the database
		if(empty($result)){
			$name = $user['first_name']. ' '.$user['last_name'];
			if(strlen($name) > 10){
				$name = substr($name, 0,19);
			}
			$query = mysql_query("INSERT INTO users (oauth_provider, oauth_uid, username) VALUES ('facebook', {$user['id']}, '$name')");
			$query = mysql_query("SELECT * FROM users WHERE id = " . mysql_insert_id());
			$result = mysql_fetch_array($query);
		}
		// this sets variables in the session       
		setcookie("user_id", $result['id']);           
		$_SESSION['id'] = $result['id'];
		$_SESSION['oauth_uid'] = $result['oauth_uid'];
		$_SESSION['oauth_provider'] = $result['oauth_provider'];
		$_SESSION['username'] = $result['username']; 
		
		header("Location: profile.php?u=".$result['id']);  
	} else {
		# For testing purposes, if there was an error, let's kill the script
		die("There was an error.");
	}
} else {
	# There's no active session, let's generate one
	$login_url = $facebook->getLoginUrl();
	header("Location: ".$login_url);
}                                     

?>
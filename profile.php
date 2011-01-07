<?php
include("inc/dbconn.php");
include("inc/head.php");   

function printFriends($item, $key){
	if($key < 5){
		print "<br>".($key+1).". $item";
	}
}

function facebookPrint($item, $key){
	if($key < 5){
		print '"'.($key+1).'" : "'.$item.'",';
	}
}


$user_id = $_COOKIE['user_id'];
if(isset($_GET['u'])){ 
	//then we're looking for someone elses profile
	$user_id = $_GET['u'];                        
}                                                 

$sql = "SELECT * FROM `users` WHERE `id` = '$user_id' LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
if($row['id']){ 
	$score = $row['score'];
		
	$sql = "SELECT * FROM `games` WHERE `user_one` = '".$row['id']."' OR `user_two` = '".$row['id']."'";
	$result = mysql_query($sql);
	$games_played = 0;
	while($games = mysql_fetch_array($result)){
		$games_played += 1;
	}
	$name = $row['username'];
	$my_score = $row['score'];
}

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
$beaten_friends = 0;
$friends_array = array();
if(!empty($session)) {
	# Active session, let's try getting the user id (getUser()) and user info (api->('/me'))
	try{
		$uid = $facebook->getUser();
		$user = $facebook->api('/me');
	} catch (Exception $e){}

	if(!empty($user)){
		try{ 
			$friends = $facebook->api('/me/friends'); 
			$friends = $friends["data"];
			$friends_array = array();
			while (list($array, $index) = each($friends)) {
			    while(list($i, $value) = each($index)){
					if(preg_match("/\d+/", $value)){
						$oauth = $value;
						$sql = "SELECT `id`,`username` FROM `users` WHERE (`oauth_uid` = '$oauth') AND (`score` < $score) ORDER BY `score` ASC LIMIT 1";   
						$result = mysql_query($sql); 
						if(mysql_affected_rows()){           
							$row = mysql_fetch_array($result);
							$friend_id = $row['id'];
							$sql = "SELECT `id` FROM `friends` WHERE `user_id` = '$user_id' AND `friend_id` = '$friend_id'";
							$result = mysql_query($sql); 
							if(!mysql_affected_rows()){                                                               
								$sql = "INSERT INTO `friends` (`user_id`, `friend_id`) VALUES ('$user_id', '$friend_id')";
								$result = mysql_query($sql);
								$beaten_friends += 1;
								array_push($friends_array, $row['username']);
							}
						}
					}                
				}
			}
		}catch(Exception $o){
			print "Exception: $o";
		}                         

	}
}

?>
     <script type="text/javascript">
	 	$(document).ready(function(){
	   		if(getCookie('levelup') != '') {
				if(getCookie('levelup') == "leveledup"){
					leveledUp();
	     			$('.announce').fadeIn('slow');        
				}   
				else if (getCookie("levelup") == "leveleddown"){
					leveledDown();
					$(".announce").fadeIn('slow');
				}
	   		}
	 	});
	 </script>
	</head>                                               
	<body> 
			<div id="fb-root"></div>
			<div class="announce" style="display:none;">        
			</div>
			 <script src="http://connect.facebook.net/en_US/all.js"></script>
			 <script>
			   FB.init({
			     appId  : '166764346686167',
			     status : true, // check login status
			     cookie : true, // enable cookies to allow the server to access the session
			     xfbml  : true  // parse XFBML
			   });
            function doPost(name){
	        	var text = $("#beaten_friends").html();
				array = text.split(":");
				var caption = array[0];
				var description = array[1];
				caption = caption.replace("You have", name + " has");
				caption = caption.replace("your", "their");
				caption = caption + ":";
				var names = {
					<?php
						array_walk($friends_array, 'facebookPrint');
					?>
				};
				post(caption, names);
			}  

			function post(caption, names){
				
				 FB.ui(
				  {
				    method: 'stream.publish',
				    attachment: {
				      name: 'Dilemma Roulette',
				      caption: caption, 
				      properties: names,
				      href: 'http://dilemmaroulette.com/'
				    },
				    action_links: [
				      { text: 'fbrell', href: 'http://fbrell.com/' }
				    ]
				  },
				  function(response) {
				    if (response && response.post_id) {
				      alert('Post was published.');
				    } else {
				      alert('Post was not published.');
				    }
				  }
				);
			}
			</script>	
		<div class="profile-container">
			<br><br>    
			<div id="logo">dilemma_roulette</div>   
		<div class="profile-content">
		<div class="profile-right-super-column">
			<div class="profile-right-right-column"> 
				       
			global leaderboard<br>
			<div id='names'> 
			<?php
			$sql = "SELECT `username`, `score` FROM `users` ORDER BY `score` DESC LIMIT 20";     
			$result = mysql_query($sql);
			$rank = 1;
			while($row = mysql_fetch_array($result)){
				$n_score =  $row['score'];
				print "<br>$rank.  ".$row['username']." ($n_score)";
				$rank+=1;
			}
			?>
			</div> <br>
			
			</div>
			<div class="profile-right-left-column">
			<center>
   			<div class="profile-rank">
			<?php
			$rank = $ranksArray[getRank($score)];
			print "<div id ='rank'>Rank: $rank</div> ";
			print "<img src='".rankToImage(getRank($score))."'>";
			?>
			</div> 
			
		<?php
			print "<br><a href='game.php'>Start new game</a>";
			
		 ?>
		</center>
		</div>
	    
	</div> 
	<div class="profile-left-column"> 
	<?php   
	print "<br>Player name: $name";
print "<br>";
	print "Games played: $games_played games.<br>";
	print "Score: $my_score points.<br><br>";			
    ?>
    	<br>friends<br><br>
	<div id='names'>
<?php  
if($beaten_friends == 0){
	print "You haven't overtaken any of your friends at Dilemma Roulette. Better get cracking.";
}
else{
	print "<div id='beaten_friends'>You have beaten $beaten_friends of your friends at Dilemma Roulette including:<br> ";
	array_walk($friends_array, 'printFriends');
	print "</div><br><br><a href='#'><img src='images/post.png' onclick='doPost(\"$name\")'></a>";
}

?> 
 
</div> 
</div>
</div>
</body>
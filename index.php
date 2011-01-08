<?
	include("inc/dbconn.php");  
    if(!empty($_SESSION)){
		header("Location: profile.php?u=".$_SESSION['id']);
	}        
	
  	include("inc/head.php");
?>         
  	
	<body> 
	<div class="wrapper">
	   	<div class="container">   
			<br><br><br><h1>dilemma_roulette</h1> 
			<div id="subtitle">use charisma, intelligence, strategy and statistics to rise to the top of the social ladder. may the best player win.  </div>
			<div class="right-column">
			   <div class="box3"><h2>enter the battle</h2> 
				<center>
			   	<fb:login-button size="large"
				                 onlogin="document.location = 'login.php'">
				  Login with Facebook
				</fb:login-button>    
				</center>
				</div> 
			</div>
			<div class="left-column">
			<div class="box2"><h2>what you need</h2>
					a brain<br><br>
					a microphone<br><br>
					a camera<br><br>
			</div>
			<div class="box1"><h2>how it works</h2>
				Dilemma Roulette allows you to connect via video to random people across the globe and play simple games with them. <br><br>     
				The games include the prisoner's dilemma, stag hunt, and rock, paper, scissors shoot. <br><br>
				Win games in order to advance in rank and beat your friends.
			</div>  
			</div> 
			
		                                                   <br>
		</div>
		<div class="push"></div>
		</div>   
		 
			<div class="footer">
			created by <a class="no-decor" href="http://www.twitter.com/danshipper">dan shipper.</a>
			please send job offers, deep expressions of gratitude and offers of free money  to: dan (at) danshipper.com <br><br>
			all princely/nigerian monetary solicitations may be directed to <a class="no-decor" href="mailto:info@christine2010.com">Christine O'Donnell</a> for most immediate response.  
			</div>
		<div id="fb-root"></div>
		<script src="http://connect.facebook.net/en_US/all.js"></script>
		<script>
		  FB.init({appId: '166764346686167', status: true, cookie: true, xfbml: true});
		  FB.Event.subscribe('auth.sessionChange', function(response) {
		    if (response.session) {
		      // A user has logged in, and a new cookie has been saved    
				window.location="login.php"
		    } else {
		      // The user has logged out, and the cookie has been cleared     
		    }
		  });
		</script>                                                                                                           
		
	</body>
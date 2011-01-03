<?
	include("inc/dbconn.php");  
    if(!empty($_SESSION)){
		header("Location: profile.php?u=".$_SESSION['id']);
	}        
	
  	include("inc/head.php");
?>         
                     
	<body>
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
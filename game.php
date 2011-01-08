<?php  

include("inc/dbconn.php");

if(!isset($_COOKIE['user_id'])){
	header("Location: index.php");
}                       

include("inc/head.php");   
$user_id = $_COOKIE['user_id'];                                         
?> 
	<link rel="stylesheet" type="text/css" href="history/history.css" />
    <script type="text/javascript" src="history/history.js"></script>
    <!-- END Browser History required section -->  
	    
    <script type="text/javascript" src="swfobject.js"></script>
    <script type="text/javascript">
        <!-- For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. --> 
        var swfVersionStr = "10.0.0";
        <!-- To use express install, set to playerProductInstall.swf, otherwise the empty string. -->
        var xiSwfUrlStr = "playerProductInstall.swf";
        var flashvars = {};
        var params = {};
        params.quality = "high";
        params.bgcolor = "#e5f3fc";
        params.allowscriptaccess = "sameDomain";
        params.allowfullscreen = "true";
        var attributes = {};
        attributes.id = "DilemmaRoulette";
        attributes.name = "DilemmaRoulette";
        attributes.align = "middle";
        swfobject.embedSWF(
            "DilemmaRoulette.swf", "flashContent", 
            "454", "584", 
            swfVersionStr, xiSwfUrlStr, 
            flashvars, params, attributes);
		<!-- JavaScript enabled so display the flashContent div in case it is not replaced with a swf object. -->
		swfobject.createCSS("#flashContent", "display:block;text-align:left;");
    </script>
</head>                                               
<body>
	<div class="container">
		<br><br>   
		<div id="status-bar">
		<b>start</b> > conspire & decide > postgame report
		</div>  
		<div id="logo"><a href='profile.php' style='text-decoration: none; color: black;'>dilemma_roulette</a></div> 
		<br>
	<div class="content">
	<div id='waiting' name='waiting'><center><img src='images/waiting.gif'><br><br>Hey there captain. We're waiting to get into a game.</div>
	</div>
	<div class="right"> 
	<div id="game-header"></div><br>   
	<div id="rules"> 
	</div>
	<br><br>                                                 
	<div id="decision">
	</div>
	<div id="linkback">
	</div>
	</div>
	<div class="left"> 
	<div id="buffer"></div> 
	<div class="flash">
 <!-- SWFObject's dynamic embed method replaces this alternative HTML content with Flash content when enough 
		 JavaScript and Flash plug-in support is available. The div is initially hidden so that it doesn't show
		 when JavaScript is disabled.
	-->                                                 
 	<div id="flashContent">
    	<p>
        	To view this page ensure that Adobe Flash Player version 
			10.0.0 or greater is installed. 
		</p>
		<script type="text/javascript"> 
			var pageHost = ((document.location.protocol == "https:") ? "https://" :	"http://"); 
			document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
							+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
		</script> 
    </div>
   	
   	<noscript>
        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="454" height="584" id="DilemmaRoulette">
            <param name="movie" value="DilemmaRoulette.swf" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#e5f3fc" />
            <param name="allowScriptAccess" value="sameDomain" />
            <param name="allowFullScreen" value="true" />
            <!--[if !IE]>-->
            <object type="application/x-shockwave-flash" data="DilemmaRoulette.swf" width="454" height="584">
                <param name="quality" value="high" />
                <param name="bgcolor" value="#e5f3fc" />
                <param name="allowScriptAccess" value="sameDomain" />
                <param name="allowFullScreen" value="true" />
            <!--<![endif]-->
            <!--[if gte IE 6]>-->
            	<p> 
            		Either scripts and active content are not permitted to run or Adobe Flash Player version
            		10.0.0 or greater is not installed.
            	</p>
            <!--<![endif]-->
                <a href="http://www.adobe.com/go/getflashplayer">
                    <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" />
                </a>
            <!--[if !IE]>-->
            </object>
            <!--<![endif]-->
        </object>
    </noscript>  	
	</div>
	<br><br>
	</div> 
	</div>
	
	<br><br><br><br><br><br><br><br><br>
	
</body>
	
</html>
//Game.js
//Copyright 2010 Dan Shipper 
  
     
/****************************************************************
	******** Utility Functions ********
*/          

GameType = {
	ROCK_PAPER_SCISSORS: 0,
	PRISONER: 1,
	STAG_HUNT: 2
}

RPS = {
	ROCK: 0,
	PAPER: 1,
	SCISSOR: 2
}

PRISONER = {
	SILENT: 0,
	SQUEAL: 1
} 

STAG = {
	STAG: 0,
	HARE: 1
}

var gameTypeArray = new Array("Rock, Paper, Scissors", "Prisoner's Dilemma", "Stag Hunt", "Blotto");
var rpsArray = new Array("rock", "paper", "scissor");
//rounds array structure: RPS, PRISONER, STAG_HUNT, BLOTTO      
var roundsArray = new Array(3,3,1,1); 

function leveledUp(){
	$(".announce").html("You leveled up! <a href = '#' onclick = 'setCookie('levelup', ''); $('.announce').fadeOut('slow')'>X</a>");  
	setCookie("levelup", "true");
}

function leveledDown(){
	$(".announce").html("You leveled down. <a href = '#' onclick = 'setCookie('levelup', ''); $('.announce').fadeOut('slow')'>X</a>");  
	setCookie("levelup", "true");
}

function getCookie(c_name){
	if(document.cookie.length > 0){
		c_start = document.cookie.indexOf(c_name + "=");
		if(c_start != -1){
			c_start = c_start + c_name.length+1;
			c_end = document.cookie.indexOf(";", c_start);
			if(c_end == -1) c_end = document.cookie.length;
			return unescape(document.cookie.substring(c_start, c_end));
		}
	}
	return "";
}

function setCookie(c_name,value)
{
	document.cookie=c_name+ "=" +escape(value);
}   

// This function returns the appropriate reference, 
// depending on the browser.
function getFlexApp(appName)
{
  if (navigator.appName.indexOf ("Microsoft") !=-1)
  {
    return window[appName];
  } 
  else 
  {
    return document[appName];
  }
}
  
/****************************************************************/

function Game(){
	this.user_id = -1;
	this.opponent_id = -1; 
	this.user_peer_id = -1;
	this.opponent_peer_id = -1;
	this.game_id = -1;
	this.game_type = -1;
	this.game_state = 0;
	this.keep_checking_for_opponent_logged_out = null;
	this.game_over = true; 
	this.rounds = 0;
	
	this.setUserPeerId = setUserPeerId;
	this.keepUserLoggedIn = keepUserLoggedIn; 
	this.getOpponent = getOpponent;
	this.newGame = newGame;
	this.checkIfOpponentLoggedOut = checkIfOpponentLoggedOut;
	this.gameSwitch = gameSwitch; 
	this.makeDecision = makeDecision;
	this.processDecision = processDecision; 
}       

var game = new Game();

function newGame(){        
	this.opponent_id = -1;
	this.opponent_peer_id = -1;
	this.game_id = -1;
	this.game_type = -1;
	this.game_state = 0;
	if(this.keep_checking_for_opponent_logged_out != null){
		clearTimeout(this.keep_checking_for_opponent_logged_out);
	}                                          
	this.gameSwitch();
}
  
function processDecision(user_decision, opponent_decision){
	if(this.game_type == GameType.ROCK_PAPER_SCISSORS){                        
		if((user_decision == RPS.ROCK && opponent_decision == RPS.PAPER) || (user_decision == RPS.PAPER && opponent_decision == RPS.SCISSOR)|| (user_decision == RPS.SCISSOR && opponent_decision == RPS.ROCK)){
			//we lost dude
			$("#decision").html("<b>Decide</b><br><br>You lost! You played " + rpsArray[user_decision] + " and your opponent played " + rpsArray[opponent_decision] + ". Try again next time!");
		}                                                                                                        
		else if((opponent_decision == RPS.ROCK && user_decision == RPS.PAPER) || (opponent_decision == RPS.PAPER && user_decision == RPS.SCISSOR)|| (opponent_decision == RPS.SCISSOR && user_decision == RPS.ROCK)){
			//we won
		   $("#decision").html("<b>Decide</b><br><br>You won! You played "+ rpsArray[user_decision] + " and your opponent played " + rpsArray[opponent_decision] + ". Nice work!"); 
		}                                                                                               
		else if(user_decision == opponent_decision){  
			//we tied
			$("#decision").html("<b>Decide</b><br><br>You tied. You played " + rpsArray[user_decision] + " and so did your opponent. Try again.");
		}
	}
	else if(this.game_type == GameType.PRISONER){
		if(user_decision == PRISONER.SILENT && opponent_decision == PRISONER.SQUEAL){
			//got screwed 
			$("#decision").html("<b>Decide</b><br><br>You lost. You stayed silent and your opponent squealed. Sorry.");
		}                
		else if(user_decision == PRISONER.SQUEAL && opponent_decision == PRISONER.SILENT){
			//won                                                                                
			$("#decision").html("<b>Decide</b><br><br>You won. You squealed while your opponent stayed silent.");
		}        
		else if(user_decision == PRISONER.SQUEAL && opponent_decision == PRISONER.SQUEAL){
			//tie-b   
			$("#decision").html("<b>Decide</b><br><br>You both squealed and both lost.");
		}   
		else if(user_decision == PRISONER.SILENT && opponent_decision == PRISONER.SILENT){
		    //tie-g                                                                
			$("#decision").html("<b>Decide</b><br><br>You tied. You both stayed silent and both stayed positive.");
		}
	}
	else if(this.game_type == GameType.STAG_HUNT){
		if(user_decision == STAG.STAG && opponent_decision == STAG.STAG){
			$("#decision").html("<b>Decide</b><br><br>Congrats your partner is trustworthy! You both win.");
		}                                                               
		else if(user_decision == STAG.STAG && opponent_decision == STAG.HARE){
			$("#decision").html("<b>Decide</b><br><br>Sorry your partner screwed you over.");
		}                                                              
		else if(user_decision == STAG.HARE && opponent_decision == STAG.STAG){
			$("#decision").html("<b>Decide</b><br><br>Nice work you screwed over your partner. Have a hare.");
		}                                                                              
		else if(user_decision == STAG.HARE && opponent_decision == STAG.HARE){
			$("#decision").html("<b>Decide</b><br><br>Neither of you trust eachother. You both are frightened ninny's. No guts not glory, but take a few points.");
		}
	}
    this.rounds = this.rounds+1;
 	/*if(this.rounds < roundsArray[this.game_type]){   //TODO: make sure this works lol
		this.game_state = 3; //reconnect
	}                       
	else{
		this.game_state = 4; //carnage
	}*/    
	
	
	/* REMOVE THIS */ //this.game_state = 4; /*REMOVE THIS WHEN READY !!!!!!!!!!!!*/   
	
    this.game_state = 5; 
	var game_id = game.game_id; 
	var user_id = this.user_id;
	var opponent_id = this.opponent_id;
	$.get("setGameEnded.php?g="+game_id, function(data){
		if(data != "1"){
			alert("Error. SetGameEnded.");
		}
		else{ 
			//alert("Loading carnage: " + user_id + "  " + opponent_id);                       
			$.get("carnage.php?u="+user_id + "&o="+opponent_id, function(data){
				setTimeout("game.gameSwitch()", 5000);  
			});
			
		}
	});
}

function makeDecision(){ 
	var user_id = this.user_id;
	var opponent_id = this.opponent_id;
	var game_type = this.game_type;
	var game_id = this.game_id;
	var decision = -1;
	
	if(game_type == GameType.ROCK_PAPER_SCISSORS){
		var rock = $("input[class='rock']:checked").val();   
		var paper = $("input[class='paper']:checked").val();   
		var scissor = $("input[class='scissor']:checked").val();
		if(rock == "on"){
			decision = RPS.ROCK; //rock
		}                  
		else if (paper == "on"){
			decision = RPS.PAPER;  //paper
		}                   
		else if(scissor == "on"){
			decision = RPS.SCISSOR;  //scissor
		}
		else{
			alert("Please choose one.");
			return;
		}
	}
	else if(game_type == GameType.PRISONER){
		var squeal = $("input[class='squeal']:checked").val();
		var silent = $("input[class='silent']:checked").val();
		if(silent == "on"){
			decision = PRISONER.SILENT; //silent
		}   
		else if(squeal == "on"){
			decision = PRISONER.SQUEAL; //squeal
		}   
		else{
			alert("Please choose one.");
			return;
		}
	}
	else if(game_type == GameType.STAG_HUNT){
		var stag = $("input[class='stag']:checked").val();
		var hare = $("input[class='hare']:checked").val();
		if(stag == "on"){
			decision = STAG.STAG;
		}                        
		else if(hare == "on"){
			decision = STAG.HARE;
		}                        
		else{
			alert("Please choose one.");
			return;
		}
	}    
	$("#decision").html("<b>Decide</b><br><br><center><img src='images/waiting.gif'><br><br>Waiting for your partner.</center>");    
	$.get("makeDecision.php?u="+user_id+"&g="+game_id+"&d="+decision);
	
	var checked = setInterval(function(){
		$.get("getDecision.php?o="+opponent_id+"&g="+game_id, function(data){
			if(data != ""){
				//then we got a decision 
				$("#status-id").html("Decision!"); 
				clearInterval(checked);            
				game.processDecision(decision,data); 
			}
		});
	}, 3000);
}

function updateGameInfo(){                     
	if(game.game_type == GameType.ROCK_PAPER_SCISSORS){
		$("#game-header").html("<center>Connected. Game type is: Rock, Paper, Scissors</center><br>");
		$("#rules").html("<b>Rules</b><br><br>The classic third grade strategy game. Paper beats rock, rock beats scissors, and scissors beats paper.<br><br><b>Do you have what it takes to dominate your opponent?</b><br><br>");  
	}
	else if(game.game_type == GameType.PRISONER){
		$("#game-header").html("<center>Connected. Game type is: Prisoner's Dilemma</center><br>");
		 $("#rules").html("<b>Rules</b><br><br>You are a criminal who is about to be arrested. You have two choices: squeal to the police or stay silent. <br><br>If you both squeal you both lose 20 points. If you squeal and your partner stays silent you gain 20 points and he loses 20 points. <br><br>If you both stay silent you both gain 10 points (but no one wins). <br><br><b>What are you going to do?</b>");
	}
	else if(game.game_type == GameType.STAG_HUNT){
		$("#game-header").html("<center>Connected. Game type is: Stag Hunt</center><br>"); 
		$("#rules").html("<b>Rules</b><br><br>You and your partner are on a hunt. You can each decide whether you want to hunt a stag or a hare. <br><br>If you decide to hunt a stag, your partner must also hunt a stag. If you both decide to hunt stags you both gain 20 points. <br><br>If you decide to hunt a hare you will get 5 points regardless of what your partner does. <br><br>But there's a catch! If you decide to hunt a hare and your partner decides to hunt a stag, you will gain 5 points while he loses 20 points and you will win the round.<br><br><b>What will you choose - points or security?</b>");
	} 
}

function gameSwitch(){       
	if(this.game_state == 0){
		//we are looking for an opponent 
		this.getOpponent(false);        
		$("#status-bar").html("<b>start</b> > conspire & decide > postgame report");             
		this.game_state = 1;   
		return;
	}
	else if(this.game_state == 1){ 
		//that means we're in a game
		var opponent_id = this.opponent_id;
		$("#status-bar").html("start > <b>conspire & decide </b> > postgame report");     
		updateGameInfo();             
		$("#decision").load("displayDecision.php?g="+game.game_type);
	  	$(".content").html("");
		$("#buffer").slideUp("normal");
		if(game.game_type == null || gameTypeArray[game.game_type] == null){
			alert(game_type);
			alert("Error 166");
		}                      
	}   
	else if(this.game_state == 3){
		//this means we have to reconnect to the last guy
		$("#status-bar").html("start > conspire & decide > <b>postgame report</b>");
		game.game_state = 1;
		game.getOpponent(true);
	}
	/*else if(this.game_state == 4){
		clearInterval(this.keep_checking_for_opponent_logged_out);
		$("#status-id").html("Postgame report");
		$(".content").load("carnage.php?u="+this.user_id + "&o="+this.opponent_id); 
		this.game_state = 5;
		setTimeout("game.gameSwitch();", 3000); 
	}*/
	else if(this.game_state == -1){
		//that means our opponent logged out 
		alert("Opponent quit."); 
		clearInterval(this.keep_checking_for_opponent_logged_out);
		$("#status-id").html("Opponent quit.");
		$(".content").html(""); 
		this.game_state = 4; 
		/* TODO FIX THIS*/  
		setTimeout("game.gameSwitch();", 3000);
	} 
	else if(this.game_state == 5){
		document.location = "profile.php?u="+this.user_id;
	}                      
	       
}                     

function setUserPeerId(id){
	this.user_peer_id = id;
	$.get("setPeerId.php?p="+id+"&u="+this.user_id, function(){
		return;
	});
}                          

function start(){
	this.game_over = false;
}

function keepUserLoggedIn(){
	var user_id = this.user_id;
	$.get("keepLoggedIn.php?u="+user_id, function(data){
		setInterval(function(){ 
			$.get("keepLoggedIn.php?u="+user_id);
		}, 15000);
	});
	
}

function checkIfOpponentLoggedOut(){
	var opponent_user_id = game.opponent_id;   
	var user_id = game.user_id;           
	game.keep_checking_for_opponent_logged_out = setInterval(function(){
	   	$.get("getLoggedOut.php?o="+opponent_user_id+"&u="+user_id, function(data){
			if(data == "quit" ){                
				clearInterval(game.keep_checking_for_opponent_logged_out);  
				//TODO: this may not be the best way to handle it.
				game.game_state = -1;
				game.gameSwitch();          
			}       
		});
	}, 30000);
}

function checkConnected(){                  
	var ret = getFlexApp('DilemmaRoulette').getConnected();
	if(ret == 1){
	    clearInterval(game.keep_checking_for_opponent_logged_out);                           
		game.checkIfOpponentLoggedOut();
		game.gameSwitch(); 
	}   
	else{                                   
		var game_id = game.game_id;
		$.get("setGameEnded.php?g="+game_id, function(data){
			if(data != "1"){
				alert("Error. SetGameEnded.");
			}
		});
		game.newGame();
	}
}

function rctrue(reconnect){
	if(reconnect){
		return "1";
	}              
	else{
		return "0";
	}
}

function getOpponent(reconnect){   
	var user_id = this.user_id;  
	var game_type = -1;
	var game_id = -1;
	$.get("getOpponent.php?u="+user_id+"&k="+rctrue(reconnect), function(data){
		if(!data.match(/^[\d]+\/[\d]+$/) && !data.match(/^[\d]+\/[\d]+\/[\d]+\/[\w]+$/)){
			alert("Error with data: " + data);
			game.newGame();
			return;
		}
		var array = data.split("/");
		if(array.length < 3){
			//alert("We didn't find a match. Now we wait for someone to connect to us."); 
			game.game_type = array[0];                           
			game.game_id = array[1];     
			if(game.game_type == -1 || game.game_id == -1){
				alert("Error 67.");
			}
//			if(reconnect = true){
  //  			getFlexApp('DilemmaRoulette').reset();
	//		}
			//now the only piece of info we don't have is the opponent's id
			//so this should be given to us when we get connected to by another
			//flash instance.             
		}  
		else{
			//alert("We found a match. Now connecting..."); 
			game.opponent_id = array[0];
			game.game_id = array[1];
			game.game_type = array[2] ;
			game.opponent_peer_id = array[3];  
			if(game.opponent_id == -1 || game.game_id == "-1" || game.game_id == -1 || game.game_type == -1 || game.opponent_peer_id == -1){
				alert("Error 80");
			}                                                                             
			//now connect via flash...                        
			var ret = getFlexApp('DilemmaRoulette').connect(game.opponent_peer_id);
			if(ret == "failed"){    
				var game_id = game.game_id;
				alert("Error connecting");
				$.get("setGameEnded.php?g="+game_id, function(data){
					if(data != "1"){
						alert("Error. SetGameEnded.");
					}
				});
				
			}
			else{
				setTimeout("checkConnected()", 1000);
			}
		} 
			
	});              
}

function startGame(peer_id){
	$(document).ready(function(){
		game.user_id = getCookie("user_id");
		game.setUserPeerId(peer_id);
		game.keepUserLoggedIn();          
		game.newGame();
	});
}    

function connectedToOpponent(peer_id){ 
	if(peer_id != null){   
		game.opponent_peer_id = peer_id;
   		//now get their id
   		$.get("getIdByPeer.php?p="+peer_id, function(data){
			if(data != ""){                
				game.opponent_id = data;
				game.checkIfOpponentLoggedOut();
				game.gameSwitch();
			}   
			else{
				alert("Error 192.");
			}
		}); 
	}
}                     


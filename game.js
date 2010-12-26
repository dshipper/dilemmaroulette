//Game.js
//Copyright 2010 Dan Shipper 
  
     
/****************************************************************
	******** Utility Functions ********
*/

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
	
	this.setUserPeerId = setUserPeerId;
	this.keepUserLoggedIn = keepUserLoggedIn; 
	this.getOpponent = getOpponent;
	this.newGame = newGame;
	this.checkIfOpponentLoggedOut = checkIfOpponentLoggedOut;
	this.gameSwitch = gameSwitch;  
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

function gameSwitch(){       
	if(this.game_state == 0){
		//we are looking for an opponent
		this.getOpponent();        
		$("#status-id").html("Looking for an opponent....");
		this.game_state = 1;   
		return;
	}
	else if(this.game_state == 1){
		//that means we're in a game
		var opponent_id = this.opponent_id;
		$("#status-id").html("Connected to an opponent. His id is: " + this.opponent_id);
	}
	else if(this.game_state == -1){
		//that means our opponent logged out
		$("#status-id").html("Opponent quit.");
	}                       
	       
}                     

function setUserPeerId(id){
	this.user_peer_id = id;
	$.get("setPeerId.php?p="+id+"&u="+this.user_id);
}                          

function start(){
	this.game_over = false;
}

function keepUserLoggedIn(){
	var user_id = this.user_id;
	$.get("keepLoggedIn.php?u="+user_id);
	setInterval(function(){ 
		$.get("keepLoggedIn.php?u="+user_id);
	}, 10000);
}

function checkIfOpponentLoggedOut(){
	var opponent_user_id = game.opponent_id;              
	game.keep_checking_for_opponent_logged_out = setInterval(function(){
	   	$.get("getLoggedOut.php?o="+opponent_user_id, function(data){
			if(data == "quit" ){                
				clearInterval(game.keep_checking_for_opponent_logged_out);  
				//TODO: this may not be the best way to handle it.
				game.game_state = -1;
				game.gameSwitch();          
			}       
		});
	}, 10000);
}

function checkConnected(){                  
	var ret = getFlexApp('DilemmaRoulette').getConnected();
	if(ret == 1){                           
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

function getOpponent(){   
	var user_id = this.user_id;
	alert("USER ID: " + user_id);
	var game_type = -1;
	var game_id = -1;
	$.get("getOpponent.php?u="+user_id, function(data){
		if(!data.match(/^[\d]+\/[\d]+$/) && !data.match(/^[\d]+\/[\d]+\/[\d]+\/[\w]+$/)){
			alert("Error with data: " + data);
			game.newGame();
			return;
		}
		var array = data.split("/");
		if(array.length < 3){
			alert("We didn't find a match. Now we wait for someone to connect to us."); 
			game.game_type = array[0];
			game.game_id = array[1];     
			if(game.game_type == -1 || game.game_id == -1){
				alert("Error 67.");
			}
			//now the only piece of info we don't have is the opponent's id
			//so this should be given to us when we get connected to by another
			//flash instance.             
		}  
		else{
			alert("We found a match. Now connecting..."); 
			game.opponent_id = array[0];
			game.game_id = array[1];
			game.game_type = array[2] ;
			game.opponent_peer_id = array[3];  
			if(game.opponent_id == -1 || game.game_id == "-1" || game.game_id == -1 || game.game_type == -1 || game.opponent_peer_id == -1){
				alert("Error 80");
			} 
			alert("Opponent id: " + game.opponent_id + ". "+array[0]);                    
			//now connect via flash... 
			var ret = getFlexApp('DilemmaRoulette').connect(game.opponent_peer_id);
			if(ret == "failed"){    
				var game_id = game.game_id;
				$.get("setGameEnded.php?g="+game_id, function(data){
					if(data != "1"){
						alert("Error. SetGameEnded.");
					}
				});
				game.newGame();
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


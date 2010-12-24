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
	this.game_over = true; 
	
	this.setUserPeerId = setUserPeerId;
	this.keepUserLoggedIn = keepUserLoggedIn; 
	this.getOpponent = getOpponent;
	this.newGame = newGame;  
}

function newGame(){
	this.opponent_id = -1;
	this.opponent_peer_id = -1;
	this.game_id = -1;
	this.game_type = -1;
	this.game_state = 0;  
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

function getOpponent(){   
	var peer_id = this.user_peer_id;
	var game_type = -1;
	var game_id = -1;
	$.get("getOpponent.php?p="+peer_id, function(data){
		var array = data.split("/");
		if(array.length < 3){
			alert("We didn't find a match. Now we wait for someone to connect to us."); 
			this.game_type = array[0];
			this.game_id = array[1];    
			if(this.game_type == -1 || this.game_id == -1){
				alert("Error 67.");
			}
			//now the only piece of info we don't have is the opponent's id
			//so this should be given to us when we get connected to by another
			//flash instance.             
		}  
		else{
			alert("We found a match. Now connecting..."); 
			this.opponent_id = array[0];
			this.game_id = array[1];
			this.game_type = array[2] ;
			this.opponent_peer_id = array[3];  
			if(this.opponent_id == -1 || this.game_id == -1 || this.game_type == -1 || this.opponent_peer_id == -1){
				alert("Error 80");
			}                     
			//now connect via flash... 
			var ret = getFlexApp('DilemmaRoulette').connect(this.opponent_peer_id);
			if(ret == "failed"){    
				var game_id = this.game_id;
				$.get("setGameEnded.php?g="+game_id, function(data){
					if(data != "1"){
						alert("Error. SetGameEnded.");
					}
				});
				this.newGame();
				this.getOpponent();
			}
		} 
			
	});              
} 

var game = new Game();

function startGame(peer_id){
	$(document).ready(function(){
		game.user_id = getCookie("user_id");
		game.setUserPeerId(peer_id);
		game.keepUserLoggedIn();          
		game.getOpponent(); 
	});
}                         


// JavaScript Document

// Runnosphere JavaScript


//Globals
var search_msg = "Rechercher sur le blog";



//Functions

//Do more easily the document.getElementById()
function $div(id){
	return(document.getElementById(id));
}

//go to top by scrolling effect
function go_scrolling_top(){
	
	$.scrollTo("#header", 800);
	
}

//Verifie si une chaîne n'est pas qu'espace
function check_value(str){
	
	var space = 0;
	
	for(var i=0; i<str.length;i++){
		if(str[i] == ' '){
			space++; 
		}
	}
	
	if(space == str.length){
		return(false);
	}else{
		return(true);
	}
		
}

//Detecte l'évènement 'ENTREE' sur le champ de recherche
function search_keypress(evenement){
	var touche = window.event ? evenement.keyCode : evenement.which;
	if(touche==13){
		check_search();
	}
}

//Check the search form before submit
function check_search(){
	
	if($div("search_input").value == search_msg || !check_value($div("search_input").value)){
		
		$div("search_input").value == search_msg;
		
	}else{
	
		$div("search_form").submit();
		
	}
	
}

//Init the search input value
function init_search_input(){
	
	if($div("search_input").value == search_msg){
		
		$div("search_input").value = "";
		
	}else if(!check_value($div("search_input").value)){
		
		$div("search_input").value = search_msg;
		
	}
	
}

//COMMENTS

function show_tab(tab){

	switch(tab){
		
		case 'coms':
			
			mask_all_tabs();
			$div('coms_on').style.display = "block";
			$div('tracks_off').style.display = "block";
			
			mask_all_tabs_content();
			$div('coms_content').style.display = "block";
			
		break;
		
		case 'tracks':
			
			mask_all_tabs();
			$div('coms_off').style.display = "block";
			$div('tracks_on').style.display = "block";
			
			mask_all_tabs_content();
			$div('tracks_content').style.display = "block";
			
		break;
		
		default:
		break;	
		
	}

}

function mask_all_tabs(){
	
	$div('coms_on').style.display = "none";
	$div('coms_off').style.display = "none";
	$div('tracks_on').style.display = "none";
	$div('tracks_off').style.display = "none";
		
}

function mask_all_tabs_content(){
	
	$div('coms_content').style.display = "none";
	$div('tracks_content').style.display = "none";
		
}

function com_reply(name){
	
	var good_name = name.replace("____", '"');
	good_name = good_name.replace("___", "'");
	
	var html = "";
	
	if($div('comment').value != ""){
		
		html += "\n\n";
	}
		
	html += "@" + name + " : ";
	
	$div('comment').value += html;
	
	$.scrollTo("#comment", 800);
	
}

var com_error = 0;

function is_empty(str){
	
	var space = 0;
	
	for(var i=0; i<str.length;i++){
		 if( str[i] == ' '){
			space++; 
		 }
	 }
	 
	 if(space == str.length || str == ''){
	 	return(true);
	 }
	 
	 return(false);

}

function check_validity(type){
	
	//var verif = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/;
	var verif = /^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$/;
	
	switch(type){
		
		case 'com':
			
			if(is_empty($div('comment').value)){
				com_error = 1;
				$div('comment').style.borderColor = "#eb0d0d";
				$div('comment').style.borderWidth = "2px";
			}else{
				$div('comment').style.borderColor = "#CCC";
				$div('comment').style.borderWidth = "1px";
			}
			
		break;
		
		case 'name':
			
			var test;
			if(test = document.getElementById('author')){
				if(is_empty($div('author').value)){
					com_error = 1;
					$div('author').style.borderColor = "#eb0d0d";
					$div('author').style.borderWidth = "2px";
				}else{
					
					$div('author').style.borderColor = "#CCC";
					$div('author').style.borderWidth = "1px";
				}
			}
			
		break;
		
		case 'mail':
			
			var test;
			if(test = document.getElementById('email')){
				if(!verif.exec($div('email').value)){
					com_error = 1;
					$div('email').style.borderColor = "#eb0d0d";
					$div('email').style.borderWidth = "2px";
				}else{
					
					$div('email').style.borderColor = "#CCC";
					$div('email').style.borderWidth = "1px";
				}
			}
				
		break;
		
		default:
		break;	
		
	}
	
}


function valid_com(){
	
	check_validity('com');
	check_validity('name');
	check_validity('mail');
	
	if(com_error == 0){
		$div('commentform').submit();
	}else{
		alert("Veuillez remplir tous les champs");
	}
	
}

/* Members Page */

//Vars
var current_open = 0;

//Show infos of one member
function show_members_infos(uid){
	
	if(current_open == 0){
		
		current_open = uid;
		$("#member-infos-"+current_open).slideDown();
		var offset = $("#member-"+uid).offset();
		$.scrollTo((offset.top - 40)+"px", 800);
		
	}else{
	
		$("#member-infos-"+current_open).slideUp('slow', function(){
			if(uid != current_open){
			
				//Juste close if current open ;-)
				current_open = uid;
				$("#member-infos-"+current_open).slideDown();
				var offset = $("#member-"+uid).offset();
				$.scrollTo((offset.top - 40)+"px", 800);
					
			}else{
				current_open = 0;	
			}
		});
	
	}
		
}
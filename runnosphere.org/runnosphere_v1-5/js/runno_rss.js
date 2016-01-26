// JavaScript Document

var nb_pages = 0;
var current_page = 1;

var nb;
var item_page;

var url_site;
var ajax_url = "/wp-admin/admin-ajax.php";

function init_rss(n, items){
	
	nb = n;
	item_page = items;

	refresh_rss();
	
}

function hide_all_pages(){
	
	for(var i=1; i<=nb_pages; i++){
		
		document.getElementById("rss_page_"+i).style.display = "none";
			
	}
	
}

function show_one_page(page){
	
	hide_all_pages();
	current_page = page;
	document.getElementById("rss_page_"+page).style.display = "block"; //EFFECTS ?
	
	show_pagination();
		
}

function show_pagination(){
	
	document.getElementById("rss_pagination_refresh").style.display = "block";
	document.getElementById("prev_link").style.visibility = "hidden";
	document.getElementById("next_link").style.visibility = "hidden";
	
	if(current_page > 1){
		document.getElementById("prev_link").style.visibility = "visible";
	}
	
	if(current_page < nb_pages){
		document.getElementById("next_link").style.visibility = "visible";
	}
	
}

function hide_pagination(){
	
	document.getElementById("rss_pagination_refresh").style.display = "none";
	
}

function go_prev_rss(){
	
	hide_pagination();
	current_page--;
	show_one_page(current_page);
	show_pagination();
	
}

function go_next_rss(){
	
	hide_pagination();
	current_page++;
	show_one_page(current_page);
	show_pagination();
	
}

function refresh_rss(){
	
	hide_all_pages();
	document.getElementById("rss_loading").style.display = "block";
	hide_pagination();

	var datas = 'action=refresh_rss'
	+ '&nb=' + encodeURIComponent(nb)
	+ '&item_page=' + encodeURIComponent(item_page);
	
	var success = 0;
	
	//JQUERY AJAX
	$.ajaxSetup ({  
		cache: false  
	 });
	 
	 var aurl = url_site + ajax_url;

	var response = $.ajax({
	   sync:false,
	   url: aurl,
	   dataType: 'json',
	   data: datas,
	   error: function(msg){
			
			show_pagination();
			//alert('Une erreur est survenue. Veuillez renouveler l\'opération');
			
	   },
	   success: function(json){
			
			if (json.response == 'ok'){
				//Success !
				nb_pages = json.nb_pages;
				current_page = 1;
				
				document.getElementById("rss_loading").style.display = "none";
				
				document.getElementById('rss_items_contener').innerHTML = "";
				document.getElementById('rss_items_contener').innerHTML = json.html;
				
				show_one_page(current_page);
				show_pagination();
				
			}else{
				show_pagination();
				//alert('Une erreur est survenue. Veuillez renouveler l\'opération');
				
			}
			
			   
	   }
	}).responseText;
	
}
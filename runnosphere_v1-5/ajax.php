<?php

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	
	if(isset($_REQUEST['actioned'])){
		
		switch($_REQUEST['actioned']){
			
			case 'get_all_rss':
				print(refresh_all_rss());
			break;
			
			default:
				print('no action requested');
			break;
		}
		
	}else{
		print('no action requested');
	}
	
/**********************************************	FUNCTIONS *************************************************/
	
	function refresh_all_rss(){
		
		include(dirname(__FILE__).'/../../../wp-blog-header.php');
		
		$nb = $_REQUEST['nb'];
		$item_page = $_REQUEST['item_page'];
		
		return rss_parsing_html($nb, $item_page);	
		
	}
	
?>
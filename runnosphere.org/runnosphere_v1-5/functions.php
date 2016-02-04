<?php

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'functions.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');

//New for MENU
add_theme_support('menus');

function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action('init', 'register_my_menu');

//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output="" ) {
	return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'add_opengraph_doctype');

//Lets add Open Graph Meta Info
function insert_fb_in_head() {
	global $post;
	if ( !is_singular()){
		 //if it is not a post or a page
		echo '<meta property="fb:admins" content="690907201"/>';
		echo '<meta property="og:title" content="' . get_bloginfo('name') . '"/>';
		echo '<meta property="og:type" content="website"/>';
		echo '<meta property="og:locale" content="fr_FR"/>';
		echo '<meta property="og:url" content="' . get_bloginfo('url') . '"/>';
		echo '<meta property="og:site_name" content="' .get_bloginfo('name'). ' : '. get_bloginfo('description') . '"/>';
		echo "<meta property=\"og:description\" content=\"".get_bloginfo('description')."\"/>";
		echo '<meta property="og:image" content="'.get_bloginfo('stylesheet_directory').'/images/avatar_fb.jpg" />';
		return;
	}else{
		echo '<meta property="fb:admins" content="690907201"/>';
		echo '<meta property="og:title" content="' . get_bloginfo('name') ." - ". get_the_title() . '"/>';
		echo '<meta property="og:type" content="article"/>';
		echo '<meta property="og:locale" content="fr_FR"/>';
		echo '<meta property="og:url" content="' . get_permalink() . '"/>';
		echo '<meta property="og:site_name" content="' .get_bloginfo('name'). ' : '. get_bloginfo('description') . '"/>';
	}

	$content = $post->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);

	$meta = strip_tags($content);
	$meta = str_replace(array("\n", "\r", "\t"), ' ', $meta);
	$meta = cut_text($meta);
	echo "<meta property=\"og:description\" content=\"".$meta."\"/>";

	$custom_fields = get_post_custom($post->ID);
	$elts = explode("\r\n",$custom_fields['illustration'][0]);

	if("".$elts[0] != "") { //the post does not have featured image, use a default image
		$img = catch_that_image();
	}
	else{
		$img = $elts[0];
	}
	echo '<meta property="og:image" content="' . $img . '"/>';
}

function cut_text($description){
	$max_caracteres = 200;
	if (strlen($description) > $max_caracteres){
		$description = substr($description, 0, $max_caracteres);
		$position_espace = strrpos($description, " ");
		$description = substr($description, 0, $position_espace);

		// Ajout des "..."
		$description = $description."...";
	}
	return $description;
}

function catch_that_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches [1] [0];

	if(empty($first_img)){ //Defines a default image
		$first_img = get_bloginfo('stylesheet_directory')."/images/avatar_fb.jpg";
	}
	return $first_img;
}

add_action( 'wp_head', 'insert_fb_in_head', 5 );

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => ''
	));

//Get onReady function
function get_on_ready(){
	if((is_single() || is_page()) && !is_user_logged_in()){
		$tab = array("running", "marathon", "runno", "course", "runners");
		$word = array_rand($tab);
		return("go_captcha('".$tab[$word]."');");
	}
	return("");
}

//Rss parsing
add_action('wp_ajax_refresh_rss', 'refresh_all_rss');
add_action('wp_ajax_nopriv_refresh_rss', 'refresh_all_rss');

function refresh_all_rss(){
	$nb = $_REQUEST['nb'];
	$item_page = $_REQUEST['item_page'];

	$resp = json_encode(rss_parsing_html($nb, $item_page));
	echo $resp;
	exit;
}

function rss_parsing_html($nb, $item_on_page){
	$rss_tab = array();
	$date_tab = array();

	$flux = array();
	$images = array();
	$images_def = array();
	$ids = array();

	$args = array(
		'exclude' => array(),
		'orderby' => 'login',
		'order' => 'ASC',
		'fields' => 'all'
	);
	$users = get_users($args);
	foreach($users as $usr){
		$uid = $usr->ID;
		if(get_user_meta($uid, "rss_active", true) == "1"){
			//Admin enable this RSS
			$rss = get_user_meta($uid, "rss_address", true);
			if(!empty($rss)){
				$flux[] = stripslashes($rss);
				$images[] = 1;
				$images_def[] = get_simple_local_avatar( $uid, '37', '', false, true);
				$ids[] = $uid;
			}
		}
	}

	$html = "";
	$pages = 1;
	$p = 1;
	$n = 0;

	for($j = 0; $j < sizeof($flux); $j++){

		$error = 0;
		$rss_web = dirname(__FILE__)."/cron/xml_v2/rss-".$ids[$j].".xml";
		$rss_image = $images[$j];
		$rss_def = $images_def[$j];

		$fluxrss = array();

		if(!file_exists($rss_web)){
			$error = 1;
		}else{
			if(!@$fluxrss=simplexml_load_file($rss_web, 'SimpleXMLElement', LIBXML_NOCDATA)){
				$error = 1;
			}
			if(@file_get_contents($rss_web) == ""){
				$error = 1;
			}
		}

		if(empty($fluxrss->channel->title) || empty($fluxrss->channel->item->title)){
			$error = 1;
		}

		if($error == 0){
			$i = 0;
			$rss_item = array();

			foreach($fluxrss->channel->item as $item){
				$i++;
				if($i <= $nb){
					$rss_item = array();
					$rss_item['blog_title'] = ''.$fluxrss->channel->title;
					$rss_item['blog_link'] = ''.$fluxrss->channel->link;
					$rss_item['title'] = ''.$item->title;
					$rss_item['link'] = ''.$item->link;
					$rss_item['description'] = ''.truncate_str(strip_tags(''.$item->description), 0, 300, $item->link);
					$rss_item['date'] = date(get_option('date_format'),strtotime($item->pubDate));
					$rss_item['enclosure'] = '';

					if($rss_image == 1){
						//enclosure !
						$rss_item['enclosure'] = ''.$item->enclosure[0]['url'];
						if($rss_item['enclosure'] == ''){
							$rss_item['enclosure'] = ''.$rss_def;
						}
					}

					$rss_tab[] = $rss_item;
					$index = sizeof($rss_tab) - 1;
					$date_tab[strtotime($item->pubDate)] = $index;
				}
			}
		}
	}

	krsort($date_tab);

	//NB PAGES
	$pages = ceil( sizeof($date_tab) / $item_on_page );
	$html .= '<div class="rss_paginate" id="rss_page_'.$p.'" style="display:none;">';
	foreach($date_tab as $d=>$id){
		$my_item = $rss_tab[$id];

		//HTML ITEM
		$html_item = "";

		//PAGINATE
		if($n % $item_on_page == 0 && $n != 0){
			$p++;
			$html .= "</div>";
			$html .= '<div class="rss_paginate" id="rss_page_'.$p.'" style="display:none;">';
		}else{
			//SEP
			if($n != 0){
				$html .= '<div class="rss_sep">&nbsp;</div>';
			}
		}

		//ITEM
		$html .= '<div class="rss_item">';

		if($my_item['enclosure'] == ''){

			//No enclosure
			$html .= '<div class="rss_item_content_left">';
			$html .= '<div class="rss_item_titles">';
			$html .= '<a href="'.$my_item['blog_link'].'" target="_blank">'.$my_item['blog_title'].'</a>';
			$html .= ' - ';
			$html .= '<span class="rss_item_post_title">';
			$html .= '<a href="'.$my_item['link'].'" target="_blank">'.$my_item['title'].'</a>';
			$html .= '</span>';
			$html .= '</div>';
			$html .= '<div class="rss_item_desc">';
			$html .= $my_item['description'];
			$html .= '</div>';
			$html .= '</div>';

		}else{
			//enclosure ok
			$html .= '<div class="rss_item_enclosure">';
			$html .= '<img class="rss_item_img_enclosure" src="'.$my_item['enclosure'].'" />';
			$html .= '</div>';

			$html .= '<div class="rss_item_content_right">';
			$html .= '<div class="rss_item_titles">';
			$html .= '<a href="'.$my_item['blog_link'].'" target="_blank">'.$my_item['blog_title'].'</a>';
			$html .= ' - ';
			$html .= '<span class="rss_item_post_title">';
			$html .= '<a href="'.$my_item['link'].'" target="_blank">'.$my_item['title'].'</a>';
			$html .= '</span>';
			$html .= '</div>';
			$html .= '<div class="rss_item_desc">';
			$html .= $my_item['description'];
			$html .= '</div>';
			$html .= '</div>';

			$html .= '<div style="clear:both;"></div>';
		}

		$html .= '</div>';

		$n++;
	}

	$html .= "</div>";

	//TEMP
	$response = "ok";
	$return = array("response" => $response, "nb_pages" => $pages, "html" => $html);
	return($return);
}

function parse_to_get_img($desc){

	preg_match_all("#<img(.*?)(src.*?)>#is",$desc,$mat,PREG_PATTERN_ORDER);
	foreach ($mat[2] as $im){
		//on récupère que le lien
		// tmp recoit un tableau de chaine comprenant des bout de chaine apres ' '
		$tmp = explode(' ',$im);
		foreach ($tmp as $value){
			if ((ereg('src',$value,$regs)) ){
				$img = eregi_replace('src=',"",$value);
				$img = eregi_replace('"',"",$img);
			}
			//enleve un enventuel espace après l'extension ou avant le debut de l'url.
			$img=trim($img);

			return($img);
		}
	}
}

function truncate_str($chaine, $debut, $max, $url, $ponct='[...]'){
	if (strlen($chaine) > $max){
		$chaine = substr($chaine, $debut, $max);
		$espace = strrpos($chaine, " ");
		$chaine = substr($chaine, $debut, $espace)." <a href='".$url."' target='_blank'>".$ponct."</a>";
	}else{
		$chaine = $chaine." <a href='".$url."' target='_blank'>".$ponct."</a>";
	}

	return($chaine);
}

function rss_parsing_rss($nb){

	$rss_tab = array();
	$date_tab = array();

	$flux = array();
	$images = array();
	$images_def = array();
	$ids = array();

	$args = array(
		'exclude' => array(),
		'orderby' => 'login',
		'order' => 'ASC',
		'fields' => 'all'
	);
	$users = get_users($args);
	foreach($users as $usr){
		$uid = $usr->ID;
		if(get_user_meta($uid, "rss_active", true) == "1"){
			//Admin enable this RSS
			$rss = get_user_meta($uid, "rss_address", true);
			if(!empty($rss)){
				$flux[] = stripslashes($rss);
				$images[] = 1;
				$images_def[] = get_simple_local_avatar( $uid, '96', '', false, true);
				$ids[] = $uid;
			}
		}
	}

	$html = "";
	$n = 1;

	for($j = 0; $j < sizeof($flux); $j++){
		$error = 0;
		$rss_web = dirname(__FILE__)."/cron/xml_v2/rss-".$ids[$j].".xml";

		if(!file_exists($rss_web)){
			$error = 1;
		}else{
			if(!@$fluxrss=simplexml_load_file($rss_web, 'SimpleXMLElement', LIBXML_NOCDATA)){
				$error = 1;
			}
			if(@file_get_contents($rss_web) == ""){
				$error = 1;
			}
		}

		if(empty($fluxrss->channel->title) || empty($fluxrss->channel->item->title)){
			$error = 1;
		}

		if($error == 0){
			$i = 0;
			$rss_item = array();

			foreach($fluxrss->channel->item as $item){
				$i++;
				if($i <= $nb){

					$rss_item = array();
					$rss_item['blog_title'] = ''.$fluxrss->channel->title;
					$rss_item['blog_link'] = ''.$fluxrss->channel->link;

					$rss_item['title'] = ''.$item->title;
					$rss_item['link'] = ''.$item->link;

					$rss_item['description'] = ''.truncate_str(strip_tags(''.$item->description), 0, 300, $item->link);

					$rss_item['date'] = date(get_option('date_format'),strtotime($item->pubDate));
					$rss_item['pub_date'] = ''.$item->pubDate.' +0000';

					//enclosure !
					if(''.$item->enclosure[0]['url'] != ''){
						$rss_item['enclosure'] = ''.$item->enclosure[0]['url'];
					}else{
						$rss_item['enclosure'] = $images_def[$j];
					}

					$rss_tab[] = $rss_item;

					$index = sizeof($rss_tab) - 1;

					$date_tab[strtotime($item->pubDate)] = $index;

				}
			}
		}
	}

	krsort($date_tab);

	//GO TO FLUX RSS

	$page = get_page_by_path('blogs-de-runners');
	$page_link = get_page_link($page->ID);
	$page_title = $page->post_title;

	$html .= '<?xml version="1.0" encoding="UTF-8"?><rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	>';

	$html .= '<channel>';
	$html .= '<title>'.get_bloginfo('title').' - '.$page_title.'</title>';
	$html .= '<atom:link href="'.get_bloginfo('url').'/runners-rss/'.'" rel="self" type="application/rss+xml" />';
	$html .= '<link>'.$page_link.'</link>';
	$html .= '<description>Le flux RSS des blogs de la runnosphère</description>';

	$html .= '<lastBuildDate>'.date("D, d M Y H:i:s").' +0000</lastBuildDate>';
	$html .= '<language>fr</language>';
	$html .= '<sy:updatePeriod>hourly</sy:updatePeriod> ';
	$html .= '<sy:updateFrequency>1</sy:updateFrequency>';
	$html .= '';
	$html .= '';

	foreach($date_tab as $d=>$id){

		$my_item = $rss_tab[$id];

		$html .= '<item>';

		$html .= '<title><![CDATA['.$my_item['blog_title'].' - '.str_replace("&", "&#x26;", $my_item['title']).']]></title>';
		$html .= '<link>'.str_replace("&", "&amp;", $my_item['link']).'</link>';
		$html .= '<guid isPermaLink="true">'.str_replace("&", "&amp;", $my_item['link']).'</guid>';
		$html .= '<description><![CDATA['.$my_item['description'].']]></description>';

		$date_base = $my_item['pub_date'];
		$new = date("D, d M Y H:i:s", strtotime($date_base)).' +0000';

		$html .= '<pubDate>'.$new.'</pubDate>';

		//Enclosure
		if($my_item['enclosure'] != ''){

			$t = explode(".", $my_item['enclosure']);
			$ext = $t[sizeof($t) - 1];

			if($ext == 'jpg'){
				$ext = "jpeg";
			}

							//TODO : Length for image

			$html .= '<enclosure url="'.str_replace("https", "http", $my_item['enclosure']).'" type="image/'.$ext.'"></enclosure>';

		}

		$html .= '</item>';

		$n++;

	}

	$html .= '</channel></rss>';

	return($html);

}

function getSizeFile($url) {
	if (substr($url,0,4)=='http') {
		$x = array_change_key_case(get_headers($url, 1),CASE_LOWER);
		if ( strcasecmp($x[0], 'HTTP/1.1 200 OK') != 0 ) { $x = $x['content-length'][1]; }
		else { $x = $x['content-length']; }
	}
	else { $x = @filesize($url); }

	return $x;
}


/* COMMENTS */

function get_comment_type_count($type='all', $post_id = 0) {

	global $cjd_comment_count_cache, $id, $post;
	if ( !$post_id )
		$post_id = $post->ID;
	if ( !$post_id )
		return;

	if ( !isset($cjd_comment_count_cache[$post_id]) ) {
		$p = get_post($post_id);
		$p = array($p);
		update_comment_type_cache($p);
	}

	if ( $type == 'pingback' || $type == 'trackback' || $type == 'comment' )
		return $cjd_comment_count_cache[$post_id][$type];
	elseif ( $type == 'ping' )
		return $cjd_comment_count_cache[$post_id]['pingback'] + $cjd_comment_count_cache[$post_id]['trackback'];
	else
		return array_sum((array) $cjd_comment_count_cache[$post_id]);

}

function comment_type_count($type = 'all', $post_id = 0) {
	echo get_comment_type_count($type, $post_id);
}

function update_comment_type_cache($queried_posts) {
	global $cjd_comment_count_cache, $wpdb;

	if ( !$queried_posts )
		return $queried_posts;


	foreach ( (array) $queried_posts as $post )
		if ( !isset($cjd_comment_count_cache[$post->ID]) )
			$post_id_list[] = $post->ID;

	if ( $post_id_list ) {
		$post_id_list = implode(',', $post_id_list);

		foreach ( array('', 'pingback', 'trackback') as $type ) {
			$counts = $wpdb->get_results("SELECT ID, COUNT( comment_ID ) AS ccount
			FROM $wpdb->posts
			LEFT JOIN $wpdb->comments ON ( comment_post_ID = ID AND comment_approved = '1' AND comment_type='$type' )
			WHERE post_status = 'publish' AND ID IN ($post_id_list)
			GROUP BY ID");

			if ( $counts ) {
				if ( '' == $type )
					$type = 'comment';
				foreach ( $counts as $count )
					$cjd_comment_count_cache[$count->ID][$type] = $count->ccount;
			}
		}
	}
	return $queried_posts;
}

add_filter('the_posts', 'update_comment_type_cache');

//FOR ADMIN
add_action('admin_menu', 'add_theme_runno');

function add_theme_runno() {
	//RSS ADMIN
	add_submenu_page('themes.php', 'Runno RSS', "G&eacute;rer les flux RSS", 1, __FILE__, go_to_rss);


}

function go_to_rss(){
	echo("<div><br /><br /><br /><a href='".get_bloginfo('url')."/wp-content/themes/runnosphere/rss_admin/'>Acc&eacute;der &agrave; la gestion des flux RSS pour la Runnosph&egrave;re</a></div>");
}


/* More infos on the user profil ! */
function fb_add_custom_user_profile_fields( $user ) {

	if(current_user_can('administrator')){
?>
		<br /><hr /><br />
		<h3>Administrateur -- Blogs de runners</h3>
		<table class="form-table">
			<tr>
				<th>
					<label for="rss_active">Ce flux rss doit-il appara&icirc;tre dans "Blogs de runners" ?</label>
				</th>
			  <td>
					<input type="checkbox" name="rss_active" id="rss_active" value="1" <?php if(esc_attr( get_the_author_meta( 'rss_active', $user->ID )) == "1"){ echo("checked='checked'"); } ?> />
				</td>
			</tr>
			<tr>
				<th>
					<label for="display_order">Ordre d'apparition sur la pages des membres</label>
				</th>
				<td>
					<input type="text" name="display_order" id="display_order" value="<?php echo esc_attr( get_the_author_meta( 'display_order', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description">Veuillez pr&eacute;ciser un chiffre pour ordonner l'apparition des membres sur la page d&eacute;di&eacute;e (Entre 1 et 2 000 000) :D</span>
				</td>
			</tr>
		</table>
		<br /><br /><hr /><br />

<?php
	} //End if
?>
	<h3>Membres Runnosphere</h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="no_display_user">Je ne veux pas appara&icirc;tre sur la page des membres Runnosph&egrave;re
				</label>
			</th>
			<td>
				<input type="checkbox" name="no_display_user" id="no_display_user" value="1" <?php if(esc_attr( get_the_author_meta( 'no_display_user', $user->ID )) == "1"){ echo("checked='checked'"); } ?> />
			</td>
		</tr>
	</table>
	<br />
	<h3>Infos sociales</h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="rss_address">Adresse RSS</label>
			</th>
			<td>
				<input type="text" name="rss_address" id="rss_address" value="<?php echo esc_attr( get_the_author_meta( 'rss_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'adresse du flux RSS de votre blog</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="fb_address">Facebook</label>
			</th>
			<td>
				<input type="text" name="fb_address" id="fb_address" value="<?php echo esc_attr( get_the_author_meta( 'fb_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'adresse de votre page Facebook</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="tw_address">Twitter</label>
			</th>
			<td>
				<input type="text" name="tw_address" id="tw_address" value="<?php echo esc_attr( get_the_author_meta( 'tw_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer votre pseudo Twitter (sans le @)</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="pint_address">Pinterest
			</label></th>
			<td>
				<input type="text" name="pint_address" id="pint_address" value="<?php echo esc_attr( get_the_author_meta( 'pint_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre page Pinterest</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="gplus_address">Google+
			</label></th>
			<td>
				<input type="text" name="gplus_address" id="gplus_address" value="<?php echo esc_attr( get_the_author_meta( 'gplus_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre compte Google+</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="instagram_address">Instagram
			</label></th>
			<td>
				<input type="text" name="instagram_address" id="instagram_address" value="<?php echo esc_attr( get_the_author_meta( 'instagram_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil Instagram</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="skype_address">Skype
			</label></th>
			<td>
				<input type="text" name="skype_address" id="skype_address" value="<?php echo esc_attr( get_the_author_meta( 'skype_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer votre pseudo Skype</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="youtube_address">YouTube
			</label></th>
			<td>
				<input type="text" name="youtube_address" id="youtube_address" value="<?php echo esc_attr( get_the_author_meta( 'youtube_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre cha&icirc;ne YouTube</span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="nikeplus_address">Nike+
			</label></th>
			<td>
				<input type="text" name="nikeplus_address" id="nikeplus_address" value="<?php echo esc_attr( get_the_author_meta( 'nikeplus_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil Nike+</span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="garmin_address">Garmin
			</label></th>
			<td>
				<input type="text" name="garmin_address" id="garmin_address" value="<?php echo esc_attr( get_the_author_meta( 'garmin_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil Garmin</span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="dailymile_address">Dailymile
			</label></th>
			<td>
				<input type="text" name="dailymile_address" id="dailymile_address" value="<?php echo esc_attr( get_the_author_meta( 'dailymile_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil Dailymile</span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="strava_address">Strava
			</label></th>
			<td>
				<input type="text" name="strava_address" id="strava_address" value="<?php echo esc_attr( get_the_author_meta( 'strava_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil Strava</span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="smashrun_address">Smashrun
			</label></th>
			<td>
				<input type="text" name="smashrun_address" id="smashrun_address" value="<?php echo esc_attr( get_the_author_meta( 'smashrun_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil Smashrun</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="runkeeper_address">RunKeeper
			</label></th>
			<td>
				<input type="text" name="runkeeper_address" id="runkeeper_address" value="<?php echo esc_attr( get_the_author_meta( 'runkeeper_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil RunKeeper</span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="flickr_address">FlickR
			</label></th>
			<td>
				<input type="text" name="flickr_address" id="runkeeper_address" value="<?php echo esc_attr( get_the_author_meta( 'flickr_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil FlickR</span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="picasa_address">Picasa
			</label></th>
			<td>
				<input type="text" name="picasa_address" id="picasa_address" value="<?php echo esc_attr( get_the_author_meta( 'picasa_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil Picasa</span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="movescount_address">Movescount
			</label></th>
			<td>
				<input type="text" name="movescount_address" id="movescount_address" value="<?php echo esc_attr( get_the_author_meta( 'movescount_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre profil Movescount</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="portrait_address">Votre portrait
			</label></th>
			<td>
				<input type="text" name="portrait_address" id="portrait_address" value="<?php echo esc_attr( get_the_author_meta( 'portrait_address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer l'url de votre portrait sur le site Runnosphere.org</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="last_run">Vos derni&egrave;res courses
			</label></th>
			<td>
				<textarea name="last_run" id="last_run" rows="5" cols="30"><?php echo esc_attr( get_the_author_meta( 'last_run', $user->ID ) ); ?></textarea><br />
				<span class="description">Vos derni&egrave;res courses, chronos...</span>
			</td>
		</tr>

		<tr>
			<th>
				<label for="next_run">Vos prochaines courses
			</label></th>
			<td>
				<textarea name="next_run" id="next_run" rows="5" cols="30"><?php echo esc_attr( get_the_author_meta( 'next_run', $user->ID ) ); ?></textarea><br />
				<span class="description">Vos prochaines courses, objectifs...</span>
			</td>
		</tr>
	</table>
	<h3>Infos supplémentaires (pour les admins uniquement ! - Ne seront pas publi&eacute;es)</h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="adm_phone">Votre num&eacute;ro de t&eacute;l&eacute;phone
			</label></th>
			<td>
				<input type="text" name="adm_phone" id="adm_phone" value="<?php echo esc_attr( get_the_author_meta( 'adm_phone', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Votre num&eacute;ro de t&eacute;l&eacute;phone sur lequel la Runnosph&egrave;re peut vous joindre...</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="postal_address">Votre adresse postale
			</label></th>
			<td>
				<textarea name="postal_address" id="postal_address" rows="5" cols="30"><?php echo esc_attr( get_the_author_meta( 'postal_address', $user->ID ) ); ?></textarea><br />
				<span class="description">Votre adresse postale, avec votre nom complet ;-)...</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="birth_date">Votre date de naissance
			</label></th>
			<td>
				<input type="text" name="birth_date" id="birth_date" value="<?php echo esc_attr( get_the_author_meta( 'birth_date', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Votre date de naissance (au format JJ/MM/AAAA si possible)...</span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="adm_textile">Votre taille de textile
			</label></th>
			<td>
				<input type="text" name="adm_textile" id="adm_textile" value="<?php echo esc_attr( get_the_author_meta( 'adm_textile', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer votre taille de textile <i>(6mois, 1an,...,S, M, L, XL, XXL ...)</i></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="adm_pointure">Votre pointure
			</label></th>
			<td>
				<input type="text" name="adm_pointure" id="adm_pointure" value="<?php echo esc_attr( get_the_author_meta( 'adm_pointure', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Veuillez entrer votre pointure de chaussures</span>
			</td>
		</tr>
	</table>
<?php }
function fb_save_custom_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;

	if(current_user_can('administrator')){
		update_usermeta( $user_id, 'rss_active', $_POST['rss_active'] );
		if(empty($_POST['display_order'])){
			update_usermeta( $user_id, 'display_order', "10" );
		}else{
			update_usermeta( $user_id, 'display_order', $_POST['display_order'] );
		}
	}
	update_usermeta( $user_id, 'no_display_user', $_POST['no_display_user'] );
	update_usermeta( $user_id, 'rss_address', $_POST['rss_address'] );
	update_usermeta( $user_id, 'fb_address', $_POST['fb_address'] );
	update_usermeta( $user_id, 'tw_address', $_POST['tw_address'] );
	update_usermeta( $user_id, 'pint_address', $_POST['pint_address'] );

	update_usermeta( $user_id, 'gplus_address', $_POST['gplus_address'] );
	update_usermeta( $user_id, 'instagram_address', $_POST['instagram_address'] );
	update_usermeta( $user_id, 'skype_address', $_POST['skype_address'] );
	update_usermeta( $user_id, 'youtube_address', $_POST['youtube_address'] );

	update_usermeta( $user_id, 'nikeplus_address', $_POST['nikeplus_address'] );
	update_usermeta( $user_id, 'garmin_address', $_POST['garmin_address'] );
	update_usermeta( $user_id, 'dailymile_address', $_POST['dailymile_address'] );
	update_usermeta( $user_id, 'strava_address', $_POST['strava_address'] );
	update_usermeta( $user_id, 'smashrun_address', $_POST['smashrun_address'] );
	update_usermeta( $user_id, 'runkeeper_address', $_POST['runkeeper_address'] );
	update_usermeta( $user_id, 'picasa_address', $_POST['picasa_address'] );
	update_usermeta( $user_id, 'flickr_address', $_POST['flickr_address'] );
	update_usermeta( $user_id, 'movescount_address', $_POST['movescount_address'] );
	update_usermeta( $user_id, 'portrait_address', $_POST['portrait_address'] );

	update_usermeta( $user_id, 'last_run', $_POST['last_run'] );
	update_usermeta( $user_id, 'next_run', $_POST['next_run'] );

	update_usermeta( $user_id, 'adm_phone', $_POST['adm_phone'] );
	update_usermeta( $user_id, 'postal_address', $_POST['postal_address'] );
	update_usermeta( $user_id, 'birth_date', $_POST['birth_date'] );
	update_usermeta( $user_id, 'adm_textile', $_POST['adm_textile'] );
	update_usermeta( $user_id, 'adm_pointure', $_POST['adm_pointure'] );
}
add_action( 'show_user_profile', 'fb_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'fb_add_custom_user_profile_fields' );
add_action( 'personal_options_update', 'fb_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'fb_save_custom_user_profile_fields' );

/* More infos on profil users ! */


/* Avatar */
if(!function_exists("get_simple_local_avatar")){

	function get_simple_local_avatar( $id_or_email, $size = '96', $default = '', $alt = false, $src_only = false){

		$avatar = get_avatar( $id_or_email, $size, $default, $alt );

		if($src_only == true){
			preg_match("/src='(.*?)'/i", $avatar, $matches);
			return $matches[1];
		}else{
			return $avatar;
		}
	}

}
/* Avatar */

/* New on V2 -> Page ThemeOptions ! */

add_action('admin_menu', 'RunnosphereAdminMenu');
// à l'initialisation de l'administration
if (isset($_GET['page']) && $_GET['page'] == 'runno-page') {
	add_action('admin_print_scripts', 'Runno_scripts');
	add_action('admin_print_styles', 'Runno_styles');
	add_filter('get_media_item_args', 'my_get_media_item_args');

	add_action('admin_init', 'RunnosphereRegisterSettings');
}

function Runno_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('farbtastic');
	wp_enqueue_script('farbtastic');
	wp_register_script('Runno-admin', get_bloginfo('stylesheet_directory').'/js/admin/Runno-admin.js', array('jquery','media-upload','thickbox','farbtastic'));
	wp_enqueue_script('Runno-admin');
}

function Runno_styles() {
wp_enqueue_style('thickbox');
}

function my_get_media_item_args($args) {
	$args['send'] = true;
	return $args;
}

function RunnosphereRegisterSettings()
{
   register_setting('runnosphere', 'bckgrnd_enable_or_not'); // Image de fond spéciale ou non ?
   register_setting('runnosphere', 'bckgrnd_img'); // Image de fond
   register_setting('runnosphere', 'bckgrnd_fixed'); // BG Fixé ?
   register_setting('runnosphere', 'bckgrnd_color'); // Couleur de fond
   register_setting('runnosphere', 'bckgrnd_link_enable_or_not'); // Un lien avec ou pas ?
   register_setting('runnosphere', 'bckgrnd_link_url'); // URL de lien
   register_setting('runnosphere', 'bckgrnd_link_target'); // Lien externe ou pas ?

   register_setting('runnosphere', 'banniere_enable_or_not'); // Une banniere avec ou pas ?
   register_setting('runnosphere', 'banniere_img'); // Banniere
   register_setting('runnosphere', 'banniere_link_enable_or_not'); // Un lien avec ou pas ?
   register_setting('runnosphere', 'banniere_link_url'); // URL de lien
   register_setting('runnosphere', 'banniere_link_target'); // Lien externe ou pas ?

   if(isset($_GET['updated']) && $_GET['updated'] == true){

	   update_option("bckgrnd_enable_or_not", $_POST['bckgrnd_enable_or_not']);
	   update_option("bckgrnd_img", $_POST['bckgrnd_img']);
	   update_option("bckgrnd_fixed", $_POST['bckgrnd_fixed']);
	   update_option("bckgrnd_color", $_POST['bckgrnd_color']);
	   update_option("bckgrnd_link_enable_or_not", $_POST['bckgrnd_link_enable_or_not']);
	   update_option("bckgrnd_link_url", $_POST['bckgrnd_link_url']);
	   update_option("bckgrnd_link_target", $_POST['bckgrnd_link_target']);

	   update_option("banniere_enable_or_not", $_POST['banniere_enable_or_not']);
	   update_option("banniere_img", $_POST['banniere_img']);
	   update_option("banniere_link_enable_or_not", $_POST['banniere_link_enable_or_not']);
	   update_option("banniere_link_url", $_POST['banniere_link_url']);
	   update_option("banniere_link_target", $_POST['banniere_link_target']);

   }

}

function RunnosphereAdminMenu()
{
   add_menu_page(
	  'Options du thème Runnosphere', // le titre de la page
	  'Runnosphere - Options du thème',            // le nom de la page dans le menu d'admin
	  'administrator',        // le rôle d'utilisateur requis pour voir cette page
	  'runno-page',        // un identifiant unique de la page
	  'RunnosphereSettingsPage'  // le nom d'une fonction qui affichera la page
   );
}

function RunnosphereSettingsPage()
{
?>
   <div class="wrap">
	  <h2>Options du th&egrave;me Runnosph&egrave;re</h2>

	  <?php if(isset($_GET['updated']) && $_GET['updated'] == true){ ?>
	  <div id="message" class="updated">
		  <p>
			  <strong>Les options ont &eacute;t&eacute; mises &agrave; jour !</strong>
		  </p>
	  </div>
	  <?php } ?>

	  <hr></hr>
	  <h3>Fond et Banni&egrave;re personnalis&eacute;s</h3>

	  <form method="post" action="admin.php?page=runno-page&updated=true">
		 <?php
			// cette fonction ajoute plusieurs champs cachés au formulaire
			settings_fields( 'my_theme' );
		 ?>

	<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="bckgrnd_enable_or_not">Utiliser une image de fond personnalis&eacute;e ?</label></th>
				<td><input type="checkbox" id="bckgrnd_enable_or_not" name="bckgrnd_enable_or_not" value="1" <?php if(get_option('bckgrnd_enable_or_not' ) == 1){ print("checked='checked'"); } ?> />&nbsp;(Si non coch&eacute;e, le fond par d&eacute;faut sera activ&eacute;)</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="bckgrnd_img">Image de fond<br /><i>(Largeur du site : 950px)</i></label></th>
				<td><input type="text" class="regular-text" id="bckgrnd_img" name="bckgrnd_img" value="<?php echo get_option( 'bckgrnd_img' ); ?>" />&nbsp;&nbsp;<input type="button" id="bckgrnd_img_button" name="bckgrnd_img_button" value="Charger une image de fond" class="button-secondary" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="bckgrnd_fixed">Le fond doit-il être fixe ?<br />(background-attachement:fixed pour info ;-])</label></th>
				<td><input type="checkbox" id="bckgrnd_fixed" name="bckgrnd_fixed" value="1" <?php if(get_option('bckgrnd_fixed' ) == 1){ print("checked='checked'"); } ?> /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="bckgrnd_color">Couleur de fond<br /><i>(Code par d&eacute;faut: #cbcdca)</i></label></th>
				<?php
					$color = get_option('bckgrnd_color');
					if($color == ""){
						$color = "#cbcdca";
					}
				?>
				<td><input type="text" class="regular-text" id="bckgrnd_color" name="bckgrnd_color" value="<?php echo $color; ?>" /> <div style="position:absolute;" id="colorpicker"></div></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="bckgrnd_link_enable_or_not">Utiliser un lien sur le fond personnalis&eacute; ?</label></th>
				<td><input type="checkbox" id="bckgrnd_link_enable_or_not" name="bckgrnd_link_enable_or_not" value="1" <?php if(get_option( 'bckgrnd_link_enable_or_not' ) == 1){ print("checked='checked'"); } ?> /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="bckgrnd_link_url">URL du lien</label></th>
				<td><input type="text" class="regular-text" id="bckgrnd_link_url" name="bckgrnd_link_url" value="<?php echo get_option( 'bckgrnd_link_url' ); ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="bckgrnd_link_target">Lien externe au site ?<br /><i>(Cible '_blank')</i></label></th>
				<td><input type="checkbox" id="bckgrnd_link_target" name="bckgrnd_link_target" value="1" <?php if(get_option( 'bckgrnd_link_target' ) == 1){ print("checked='checked'"); } ?> /></td>
			</tr>

			<tr>
				<td colspan="2"><hr /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="banniere_enable_or_not">Utiliser une banni&egrave;re personnalis&eacute;e ?</label></th>
				<td><input type="checkbox" id="banniere_enable_or_not" name="banniere_enable_or_not" value="1" <?php if(get_option( 'banniere_enable_or_not' ) == 1){ print("checked='checked'"); } ?> />&nbsp;(Si non coch&eacute;e, le header par d&eacute;faut sera activ&eacute;)</td>
			</tr>


			<tr valign="top">
				<th scope="row"><label for="banniere_img">Banni&egrave;re du site<br /><i>(Largeur du site : 950px<br />Hauteur max. : Pas de limite, mais restez raisonnable ;-])</i></label></th>
				<td><input type="text" class="regular-text" id="banniere_img" name="banniere_img" value="<?php echo get_option( 'banniere_img' ); ?>" />&nbsp;&nbsp;<input type="button" id="banniere_img_button" name="banniere_img_button" value="Charger une banni&egrave;re" class="button-secondary" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="banniere_link_enable_or_not">Utiliser un lien sur la banni&egrave;re personnalis&eacute;e ?</label></th>
				<td><input type="checkbox" id="banniere_link_enable_or_not" name="banniere_link_enable_or_not" value="1" <?php if(get_option( 'banniere_link_enable_or_not' ) == 1){ print("checked='checked'"); } ?> /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="banniere_link_url">URL du lien</label></th>
				<td><input type="text" class="regular-text" id="banniere_link_url" name="banniere_link_url" value="<?php echo get_option( 'banniere_link_url' ); ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="banniere_link_target">Lien externe au site ?<br /><i>(Cible '_blank')</i></label></th>
				<td><input type="checkbox" id="banniere_link_target" name="banniere_link_target" value="1" <?php if(get_option( 'banniere_link_target' ) == 1){ print("checked='checked'"); } ?> /></td>
			</tr>

		</table>

		 <p class="submit">
			<input type="submit" class="button-primary" value="Mettre à jour" />
		 </p>
	  </form>
   </div>

	<!-- We need little Scripts -->
	<script type="text/javascript">
		<?php global $post; ?>
		attachMediaUploader('<?php echo $post->ID ?>', 'bckgrnd_img');
		attachMediaUploader('<?php echo $post->ID ?>', 'banniere_img');
	</script>

<?php
}

/* New on V2 -> Page ThemeOptions ! */

?>

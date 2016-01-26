<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Ne pas t&eacute;l&eacute;charger cette page directement, merci !');
if (!empty($post->post_password)) { // if there's a password
	if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
?>

<h2><?php _e('Prot&eacute;g&eacute; par mot de passe'); ?></h2>
<p><?php _e('Entrer le mot de passe pour voir les commentaires'); ?></p>

<?php return;
	}
}

	$coms_html = "";
	$coms_html2 = "";
	$tracks_html = "";
	
	$coms_n = 0;
	$tracks_n = 0;

?>

<!-- You can start editing here. -->
<?php if ($comments) : ?>
	
<?php foreach ($comments as $comment) : ?>

	<?php
	
		$com_type = get_comment_type();
		
		switch($com_type){
			
			case 'comment':
				
				//True com
				if ($comment->comment_approved != 0){
					
					$coms_n++;
					
					if($comment->user_id != 0){
						
						//Runno Team
						$coms_html .= "<div class='com'>";
						
					}else{
						
						$coms_html .= "<div class='com'>";
							
					}

					$coms_html .= "<div class='com_header'>";
					$coms_html .= "<div class='com_gravatar'>";
					$coms_html .= get_avatar($comment,$size='36');
					$coms_html .= "</div>";
					$coms_html .= "<div class='com_infos'>";
					$coms_html .= get_comment_author_link();
					$coms_html .= "<br />";
					$coms_html .= "<span class='com_date'>";
					$coms_html .= get_comment_date(get_option('date_format')).", &agrave; ".get_comment_date("H:i");
					$coms_html .= "</span>";
					$coms_html .= "</div>";
					$coms_html .= "<div style='clear:both;'></div>";
					$coms_html .= "</div>";
					$coms_html .= "<div class='com_text'>";
					$coms_html .= str_replace("\n", "<br />",get_comment_text());
					$coms_html .= "</div>";
					$coms_html .= "<div class='com_reply'>";
					$coms_html .= "<a href=\"javascript:com_reply('".addslashes(get_comment_author())."');\">";
					$coms_html .= "( R&eacute;pondre )";
					$coms_html .= "</a>";
					$coms_html .= "</div>";
					$coms_html .= "";
					
					$coms_html .= "</div>";
					
				}
				
				
			break;
			
			default:
				
				//Ping or tracks
				if ($comment->comment_approved != 0){
					
					$tracks_n++;
		
					$tracks_html .= "<div class='track' id='track-".get_comment_ID()."'>";
					$tracks_html .= get_comment_author_link();
					$tracks_html .= "</div>";
					
				}
				
			break;
				
			
		}
		
		
	?>

<?php endforeach; /* end for each comment */ ?>

<?php
	if($tracks_n == 0){
		
		$tracks_html .= "<div class='track'>";
		$tracks_html .= "Aucun trackback pour le moment";
		$tracks_html .= "</div>";	
		
	}
	
	if($coms_n == 0){
		
		$coms_html .= "<div class='com'>";
		$coms_html .= "Aucun commentaire pour le moment";
		$coms_html .= "</div>";	
		
	}
?>
    
<?php endif; ?>

	<?php if ('open' != $post->comment_status) : ?>

		<!-- If comments are closed. -->
		<?php
		
			$coms_html .= "<div class='com'>";
			$coms_html .= "Les commentaires sont fermés";
			$coms_html .= "</div>";	
			
			if($tracks_n == 0){
				
				$tracks_html .= "<div class='track'>";
				$tracks_html .= "Aucun trackback pour le moment";
				$tracks_html .= "</div>";	
				
			}
		
		?>

	<?php endif; ?>
    
    <?php if (!pings_open()) : ?>

		<!-- If Tracks are closed. -->
		<?php
		
			$tracks_html .= "<div class='track'>";
			$tracks_html .= "Les trackbacks ne sont pas autorisés";
			$tracks_html .= "</div>";	
			
			if($coms_n == 0){
		
				$coms_html .= "<div class='com'>";
				$coms_html .= "Aucun commentaire pour le moment";
				$coms_html .= "</div>";	
				
			}
			
		?>

	<?php endif; ?>





<?php if ('open' == $post->comment_status) : ?>
	
    <?php
		
		$coms_html .= '<div class="comment_form">';
		$coms_html .= '<div class="coment_form_title"><img src="'.get_bloginfo('stylesheet_directory').'/images/coms_header.jpg" alt="Laissez un commentaire" title="Laissez un commentaire" border="0" /></div>';
    
		if(get_option('comment_registration') && !$user_ID ){
        	
			$coms_html .= '<div class="comment_form_err">Vous devez &ecirc;tre <a href="'.get_option('siteurl').'/wp-login.php?redirect_to='.the_permalink().'">connect&eacute;</a> pour laisser un commentaire.</div>';
        
        }else{
        
        	$coms_html .= '<form action="'.get_option('siteurl').'/wp-comments-post.php" method="post" id="commentform">';
			$coms_html .= '<p><textarea name="comment" id="comment" cols="60" rows="10" tabindex="4"></textarea></p>';
			
			if($user_ID){
				
				$coms_html .= '<div class="already_connected">Connecté en tant que <a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a> - <a href="'.get_option('siteurl').'/wp-login.php?action=logout" title="D&eacute;connect&eacute; de ce compte">D&eacute;connexion</a></div>';
				
			}else{
				
				$coms_html .= '<p><input type="text" name="author" id="author" value="'.$comment_author.'" size="40" tabindex="1" class="coms_inputs" />
    <label for="author"><small>Nom (requis)</small></label></p>';
                $coms_html .= '<p><input type="text" name="email" id="email" value="'.$comment_author_email.'" size="40" tabindex="2" class="coms_inputs" />
    <label for="email"><small>E-mail (ne sera pas publi&eacute;) (requis)</small></label></p>';
                $coms_html .= '<p><input type="text" name="url" id="url" value="'.$comment_author_url.'" size="40" tabindex="3" class="coms_inputs" />
    <label for="url"><small>Site Web</small></label></p>';

			}
			
			
			//$coms_html .= '<p><small><strong>XHTML:</strong> '._e('Vous pouvez utiliser ces tags&#58;'). allowed_tags().'</small></p>';
			
			$coms_html .= '<p><input type="hidden" name="comment_post_ID" value="'.$id.'" /><a href="javascript:valid_com();"><img id="form_sub" src="'.get_bloginfo('stylesheet_directory').'/images/coms_submit.jpg" alt="Soumettre" title="Soumettre" border="0" /></a></p>';
						
			
			$coms_html2 .= '</form>';
			

        }
		
		$coms_html2 .= "</div>";
	
	?>
	
    
<?php endif; // If registration required and not logged in ?>


<div class="tabs_content" id="coms_content">
	<div id="comments"></div>

    <?php 
		print($coms_html);
    
		if(function_exists('show_subscription_checkbox')){
			show_subscription_checkbox();
		}
		
		do_action('comment_form', $post->ID);
		print($coms_html2);
    ?>
</div>

<div class="tabs_content" id="tracks_content">

	<?php print($tracks_html); ?>

</div>

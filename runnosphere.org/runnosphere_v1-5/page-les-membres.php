<?php
/**
 * The main template file.
 *
 * @package Wordpress
 * @subpackage Runnosphere Theme
 * @since Runnosphere 1.0
 */

get_header(); ?>

<div id="content">
	<!-- Two columns design -->
	<div id="left_column">
		<div id="left_col_content">
			<?php
				//The Loop
				if ( have_posts() ) : while ( have_posts() ) : the_post();?>
					<div class="post" id="post-<?php the_ID();?>">
						<div class="post_title">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php
									$title = get_the_title();
									$first = substr($title, 0, 1);
									$other = substr($title, 1);
								?>
								<span class="post_title_first"><?php print($first); ?></span><?php print($other); ?>
							</a>
						</div>
						<div class="post_sep">&nbsp;</div>
						<div class="post_sub_infos">
							<div class="post_coms_nb">
							</div>
							<div class="post_share">
								<!-- AddThis Button BEGIN -->
								<div class="addthis_toolbox addthis_default_style">
									<a class="addthis_button_facebook_like" fb:like:layout="button_count" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php the_title();?>"></a>
									<a class="addthis_button_tweet" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php the_title();?>" tw:via="Runnosphere"></a>
									<a class="addthis_counter addthis_pill_style" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php the_title();?>"></a>
								</div>
								<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4cd6f4b502da3ed3"></script>
								<!-- AddThis Button END -->
							</div>
							<div style="clear:both"></div>
						</div>
						<div class="page_cont">
							<?php

								$users = array();
								$args  = array(
									'fields' => 'all_with_meta',
									'meta_query' => array(
										array(
										'key' => 'display_order',
										)
								));

								//get_users calls WP_User_Query and returns an array of matching users
								$users = get_users($args);

								//custom function for comparing the data we want to sort by
								function cmp($a, $b){
									if ($a->display_order == $b->display_order) {
										return 0;
									}
									return ($a->display_order < $b->display_order) ? -1 : 1;
								}

								//usort sorts our $users array with our function cmp()
								usort($users, 'cmp');

								$first = true;

								for($i=0; $i<sizeof($users); $i++){

									$u = $users[$i];
									$disp = get_user_meta($u->ID, "no_display_user", true);

									if($disp != "1"){

										if($first == false){
											$html .= "<div class='author_sep'></div>";
										}else{
											$first = false;
										}

										$html .= "<div class='author' id='member-".$u->ID."'>";
										$html .= "<div class='author_avatar'>";
										$html .= "";
										$html .= get_simple_local_avatar( $u->ID, '75', '', false, false);
										$html .= "";
										$html .= "</div>";
										$html .= "<div class='author_infos'>";
										$html .= "<a href='".$u->user_url."' target='_blank'><h3>".$u->display_name."</h3></a>";

										$datas = get_userdata($u->ID);

										$html .= "<h5>".str_replace("\n", "<br />",$datas->user_description)."</h5>";
										$more = false;

										$html .= "<div id='member-infos-".$u->ID."' class='author_more_infos'>";

										$last_run = get_user_meta($u->ID, "last_run", true);
										if(!empty($last_run)){
											$more = true;
											$html .= "<b>Ses derni&egrave;res courses :</b><br /><br />";
											$html .= str_replace("\n", "<br />",$last_run);
											$html .= "<br /><br /><br />";
										}

										$next_run = get_user_meta($u->ID, "next_run", true);
										if(!empty($next_run)){
											$more = true;
											$html .= "<b>Ses prochaines courses :</b><br /><br />";
											$html .= str_replace("\n", "<br />",$next_run);
											$html .= "<br /><br /><br />";
										}

										$html .= "</div>";

										if($more == true){
											$html .= "<div class='author_more'>";
											$html .= "<a href='javascript:void(0);' onclick='show_members_infos(".$u->ID.");'>&gt; + d'infos...</a>";
											$html .= "</div>";
										}

										$html .= "<div class='author_socials'>";
										$html .= "<div class='author_social'>";

										$nb = 0;
										$iconperlign = 13;
										$social = "";

										unset($social);
										$social = $datas->user_email;
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='mailto:".$social."' title='Contact' class='mail_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "rss_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Flux RSS' class='rss_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "fb_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Facebook' class='fb_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "tw_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='http://twitter.com/".$social."' title='Twitter' class='tw_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "gplus_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Google+' class='gplus_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "instagram_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Instagram' class='instagram_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "strava_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Strava' class='strava_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "movescount_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Movescount' class='movescount_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "garmin_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Garmin' class='garmin_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "nikeplus_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Nike+' class='nikeplus_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "smashrun_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Smashrun' class='smashrun_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "dailymile_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='DailyMile' class='dailymile_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "pint_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Pinterest' class='pint_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "foursquare_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Foursquare' class='foursquare_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "youtube_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='YouTube' class='youtube_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "flickr_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='FlickR' class='flickr_social'></a>";
										}

										unset($social);
										$social = get_user_meta($u->ID, "picasa_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<a target='_blank' href='".$social."' title='Picasa' class='picasa_social'></a>";
										}

										$html .= "</div>";

										$html .= "<div class='author_links";
										if($nb > $iconperlign){
											 $html .= ' v_center';
										}
										$html .= "'>";
										$html .= "<a href='".$u->user_url."' target='_blank'>Visiter son blog</a>";

										unset($social);
										$social = get_user_meta($u->ID, "portrait_address", true);
										if(!empty($social)){
											$nb++;
											$html .= "<br/><br/>";
											$html .= "<a href='".$social."' target='_blank'>Lire son portrait</a>";
										}

										$html .= "</div>";
										$html .= "<div style='clear:both;'></div>";
										$html .= "</div>";
										$html .= "</div>";
										$html .= "<div style='clear:both;'></div>";
										$html .= "</div>";

									}
								}
								print($html);
						?>
						</div>
					</div>
				<?php endwhile; else: ?>
				<?php endif;
				//COMMENTAIRES + TRACKBACKS
			?>
		</div>
	</div>
	<div id="right_column">
		<?php get_sidebar(); ?>
	</div>
	<div style="clear:both;">&nbsp;</div>
</div>
<?php get_footer(); ?>
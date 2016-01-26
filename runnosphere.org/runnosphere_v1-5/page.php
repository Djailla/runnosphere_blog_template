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
                    	
                        <div class="post_little_infos">
                        	
                            <div class="post_cat"><?php the_category(", "); ?></div>
                            
                            <div class="post_date"><?php the_time(get_option('date_format')); ?></div>
                        	
                            <div style="clear:both;"></div>
                            
                        </div>
                        
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
                            	<a href="<?php comments_link(); ?>">
									<?php 
									
										//comments_number('Pas de commentaire','1 commentaire','% commentaires');
										
										$nb = get_comment_type_count('comment', get_the_ID());
										switch($nb){
											
											case 0:
												print('Pas de commentaire');
											break;
											
											case 1:
												print('1 commentaire');
											break;
											
											default:
												print($nb.' commentaires');
											break;
										}
									
									 ?>
                                </a>
                            </div>
                            
                            <div class="post_share">
                            	<!-- AddThis Button BEGIN -->
                                
                                <div class="addthis_toolbox addthis_default_style">
                                
                                	<a class="addthis_button_facebook_like" fb:like:layout="button_count" addthis:url="<?php the_permalink(); ?>" addthis:title="Runnosphere.org - <?php the_title();?>"></a>
                                	<a class="addthis_button_tweet" addthis:url="<?php the_permalink(); ?>" addthis:title="Runnosphere.org - <?php the_title();?>" tw:via="Runnosphere"></a>
                                	<a class="addthis_button_compact" addthis:url="<?php the_permalink(); ?>" addthis:title="Runnosphere.org - <?php the_title();?>"></a>
                                </div>
                                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4cd6f4b502da3ed3"></script>
                                <!-- AddThis Button END -->
                                
								<div class="gle_plus_one_n">
                                	<g:plusone size="medium" count="true" href="<?php the_permalink(); ?>"></g:plusone>
                                </div>
                                
                                <div style="clear:both;"></div>
                            </div>
                            
                            <div style="clear:both"></div>
                        </div>
                        
                        <div class="post_cont">
                        	<?php the_content(); ?>
                        </div>
                    
                    	<?php
							//VARS FOR COMS / Tracks
							$coms_nb = get_comment_type_count('comment', get_the_ID());
							$tracks_nb = get_comment_type_count('trackback', get_the_ID());
							
						?>
                        
                        <!-- COMS and Tracks -->
                        <div class="comments_tracks_div">
                
                            <div class="tabs_div">
                                
                                <div class="coms_tracks_tab tab_off" id="coms_off">
                                    <a href="javascript:show_tab('coms');">Commentaires (<?php print($coms_nb) ?>)</a>
                                </div>
                                
                                <div class="coms_tracks_tab tab_on" id="coms_on">Commentaires (<?php print($coms_nb) ?>)</div>
                                
                                <div class="coms_tracks_tab tab_off" id="tracks_off">
                                    <a href="javascript:show_tab('tracks');">Trackbacks (<?php print($tracks_nb) ?>)</a>
                                </div>
                                
                                <div class="coms_tracks_tab tab_on" id="tracks_on">Trackbacks (<?php print($tracks_nb) ?>)</div>
                                
                            </div>
                            
                            <?php
								comments_template();
							?>
                            
                        </div>
                        <script type="text/javascript" language="javascript">
                            show_tab('coms');
                        </script>
					
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

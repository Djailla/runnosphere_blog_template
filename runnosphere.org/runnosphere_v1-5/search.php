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
       		
            <div class="search_results">
                    	
                <div class="search_title">
                	<?php 
						$results = $wp_query->found_posts;
                    
						switch($results){
							
							case 0:
								print("Aucun résultat pour la recherche : \"".get_search_query()."\"");
							break;
							
							case 1:
								print("1 résultat pour la recherche : \"".get_search_query()."\"");
							break;
							
							default:
								print($results." résultats pour la recherche : \"".get_search_query()."\"");
							break;
							
						}
					?>
                </div>
                    
                <div class="search_sep">&nbsp;</div>
                
            </div>
                    
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
                            	<?php comments_number('Pas de commentaire','1 commentaire','% commentaires'); ?>
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
                        	<?php
                        		//Get the enclosure SRC
								$custom_fields = get_post_custom($post->ID);
								$elts=explode("\r\n",$custom_fields['enclosure'][0]);
								
								if("".$elts[0] != ""){
									print("<img class='post_thumb' src='".get_bloginfo('stylesheet_directory')."/resizer.php?width=183&height=183&file=".$elts[0]."' alt='".get_the_title()."' />");
								}
								
								//Img.php or not ?
							
							?>
                        	
							<?php print(get_the_excerpt().'[...]'); ?>
                            <div class="post_read_more">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?> - Lire la suite">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/images/read_more_arrow.jpg" alt="" />&nbsp;Lire la suite
                                </a>
                            </div>
                            
                            <div style="clear:both"></div>
                        </div>
                        
                    </div>
					
					
				<?php endwhile; else: ?>
					
					<?php //Aucun résultat : VIDE ?>
					
				<?php endif;
				
				//Reset Query
				wp_reset_query();

			?>
            
            <?php 
				if(function_exists('wp_pagenavi')){
					print('<div class="page_navigate">');
					wp_pagenavi();
					print('</div>');
				}
			?>
        
        </div>
    
    </div>
    
    <div id="right_column">
    	<?php get_sidebar(); ?>
    </div>

	<div style="clear:both;">&nbsp;</div>

</div>

<?php get_footer(); ?>

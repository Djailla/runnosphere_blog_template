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
                            
                            <?php
							
								$rss = get_page_by_path('runners-rss');
								$rss_link = get_page_link($rss->ID);
								
							?>
                            
                            <a href="<?php print($rss_link); ?>" class="rss_img_link" title="Flux RSS" target="_blank"><img class="rss_img_logo" alt="Flux RSS" title="Flux RSS" src="<?php bloginfo('stylesheet_directory'); ?>/images/rss_logo.jpg" /></a>
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
                        
                            <div id="rss_loading">
                                <img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/loading.gif" /><br />Chargement...
                            </div>
                            
                            <div id="rss_items_contener">
                                 
                            </div>
                            
                            <div id="rss_pagination_refresh" style="display:none;">
                                
                                <a id="prev_link" href="javascript:go_prev_rss();">Précédent</a>&nbsp;&nbsp;
                                <a href="javascript:refresh_rss();">Rafraîchir</a>&nbsp;&nbsp;
                                <a id="next_link" href="javascript:go_next_rss();">Suivant</a>
                                
                            </div>   
                            
                            <script type="text/javascript" language="javascript">
                                
                                url_site = "<?php print(get_bloginfo('url')); ?>";
                                init_rss(30, 25);
            
                            </script>       
                                
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

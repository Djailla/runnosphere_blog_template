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
				$cat_id = get_cat_ID('pasta-running-party');

				//Get article
				query_posts('category__not_in='.$cat_id.'&posts_per_page=3&order=DESC');

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
						</div>
						<div class="post_cont">
							<?php
								//Get the enclosure SRC
								$custom_fields = get_post_custom($post->ID);
								$elts=explode("\r\n",$custom_fields['illustration'][0]);

								if("".$elts[0] != ""){
									print("<img class='post_thumb' src='".get_bloginfo('stylesheet_directory')."/resizer.php?width=183&height=183&file=".$elts[0]."' alt='".get_the_title()."' />");
								}

								//Img.php or not ?

							?>

							<?php print(get_the_excerpt()); ?>
							<div class="post_read_more">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?> - Lire la suite">
									<img src="<?php bloginfo('stylesheet_directory'); ?>/images/read_more_arrow.jpg" alt="" />&nbsp;Lire la suite
								</a>
							</div>

							<div style="clear:both"></div>
						</div>

					</div>


				<?php endwhile; else: ?>

					Il n'y a aucun article dans cette catégorie

				<?php endif;

				//Reset Query
				wp_reset_query();

			?>

			<div id="rss_content">
				<?php

					$page = get_page_by_path('blogs-de-runners');
					$page_link = get_page_link($page->ID);
					$page_title = $page->post_title;

					$rss = get_page_by_path('runners-rss');
					$rss_link = get_page_link($rss->ID);

				?>
				<div class="rss_title">
					<a href="<?php print($page_link); ?>" title="<?php print($page_title); ?>">
						<?php
							$first = substr($page_title, 0, 1);
							$other = substr($page_title, 1);
						?>
						<span class="rss_title_first"><?php print($first); ?></span><?php print($other); ?>
					</a>
					<a href="<?php print($rss_link); ?>" class="rss_img_link" title="Flux RSS" target="_blank">
						<img class="rss_img_logo" alt="Flux RSS" title="Flux RSS" src="<?php bloginfo('stylesheet_directory'); ?>/images/rss_logo.png" />
					</a>
				</div>
				<div class="rss_sep">&nbsp;</div>
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
					init_rss(10, 15);
				</script>

			</div>

		</div>

	</div>

	<div id="right_column">
		<?php get_sidebar(); ?>
	</div>

	<div style="clear:both;">&nbsp;</div>

</div>

<?php get_footer(); ?>

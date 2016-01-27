<?php

/*
Plugin Name: Pasta Running Party - Runnosphère.org
Plugin URI:
Author: Jérémie PEREIRA
Author URI: http://www.runnosphere.org
Description: Widget "Pasta Running Party" : Affichage de la boîte Pasta Running Party
Version: 1.0

*/

/**
 * runnosphere_PASTA Class
 */

class runnosphere_PASTA extends WP_Widget {
	/** constructor */
	function runnosphere_PASTA() {
		parent::WP_Widget(false, $name = 'Pasta Running Party - Runnosphere');
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$link = apply_filters('widget_link', $instance['link']);

		$post_link = "";

		//Get article
		query_posts('category_name=pasta-running-party&posts_per_page=1&order=DESC');

		//The Loop
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			$post_link = get_permalink();

		endwhile; else:

		endif;

		//Reset Query
		wp_reset_query();

		//Operation with TITLE and LINK
		if(''.$link == ''){
			$link = "#";
			$target = "_self";
		}else{
			$target = "_blank";
		}

		if(''.$title == ''){

			$html_title = "";

		}else{

			$first = substr($title,0,1);
			$other = substr($title,1);

			$html_title = '<div class="widget_title">';
			$html_title .= '<a href="'.$post_link.'" target="'.$target.'">';
			$html_title .= $before_title;
			$html_title .= '<span class="widget_title_first">'.$first.'</span>';
			$html_title .= $other;
			$html_title .= $after_title;

			$html_title .= "</a>";

			//Facebook
			$html_title .= '<a href="'.$link.'" class="facebook_widget_link" title="Facebook"><img class="facebook_widget_logo" alt="" src="'.get_bloginfo('stylesheet_directory').'/images/widgets/facebook_logo.png" /></a>';

			$html_title .= "</div>";

			$html_title .= '<div class="widget_sep"></div>';

		}

		?>

			<?php echo $before_widget; ?>

			<div class="widget">

				<?php echo $html_title; ?>

				<div class="widget_content widget_PASTA_content">
					<!--A COMPLETER-->

					<?php
						//Get article
						query_posts('category_name=pasta-running-party&posts_per_page=1&order=DESC');

						//The Loop
						if ( have_posts() ) : while ( have_posts() ) : the_post();?>

							<div class="widget_PASTA_post_title">
								<?php the_title(); ?>
							</div>
							<div class="widget_PASTA_post_preview">
								<?php /*the_content('[...]');*/print(get_the_excerpt().'[...]'); ?>
							</div>
							<div class="widget_PASTA_post_more">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
									&gt; Lire la suite
								</a>
								&nbsp;&nbsp;&nbsp;
								<?php
									//Get the facebook event link
									$custom_fields = get_post_custom($post->ID);
									$elts=explode("\r\n",$custom_fields['facebook_event'][0]);
								?>

								<?php if(''.$elts[0] != '' ){ ?>
									<a href="<?php print($elts[0]); ?>" target="_blank" title="S'inscrire/Inviter">
										&gt; S'inscrire/Inviter
									</a>
								<?php } ?>


								<!-- Share buttons -->
								<!-- AddThis Button BEGIN -->
								<div class="gle_plus_one">
									<g:plusone size="small" count="false" href="<?php the_permalink(); ?>"></g:plusone>
								</div>

								<div class="addthis_toolbox addthis_default_style widget_PASTA_addthis">
								<a class="addthis_button_facebook" addthis:url="<?php the_permalink(); ?>" addthis:title="Runnosphere.org - <?php the_title();?>"></a>
								<a class="addthis_button_twitter" addthis:url="<?php the_permalink(); ?>" addthis:title="Runnosphere.org - <?php the_title();?>" tw:via="Runnosphere"></a>
								<a class="addthis_button_email" addthis:url="<?php the_permalink(); ?>" addthis:title="Runnosphere.org - <?php the_title();?>"></a>
								<span class="addthis_separator">|</span>
								<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4cd52ddf5330afbf" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php the_title();?>" class="addthis_button_compact">Share</a>
								</div>
								<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4cd52ddf5330afbf"></script>
								<!-- AddThis Button END -->

							</div>

						<?php endwhile; else: ?>
							<div class="widget_PASTA_post_title">
								Il n'y a aucun article dans cette catégorie
							</div>
						<?php endif;

						//Reset Query
						wp_reset_query();

					?>

				</div>
			</div>

			<?php echo $after_widget; ?>
		<?php
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['link'] = strip_tags($new_instance['link']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		$title = esc_attr($instance['title']);
		$link = esc_attr($instance['link']);
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Facebook Fan page url:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></label></p>
		<?php
	}

} // class runnosphere_FB

// register FooWidget widget
add_action('widgets_init', create_function('', 'return register_widget("runnosphere_PASTA");'));

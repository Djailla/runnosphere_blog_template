<?php

/*
Plugin Name: Runnosphère.org: Facebook Fanpage widget
Plugin URI:
Author: Jérémie PEREIRA
Author URI: http://www.runnosphere.org
Description: Widget "Rejoignez-nous sur Facebook" : Affichage de la boîte Facebook Fan
Version: 1.0

*/

/**
 * runnosphere_FB Class
 */
class runnosphere_FB extends WP_Widget {
		/** constructor */
		function runnosphere_FB() {
				parent::WP_Widget(false, $name = 'Runnosphere: Facebook fanpage');
		}

		/** @see WP_Widget::widget */
		function widget($args, $instance) {
				extract( $args );
				$title = apply_filters('widget_title', $instance['title']);
		$link = apply_filters('widget_link', $instance['link']);

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
			$html_title .= '<a href="'.$link.'" target="'.$target.'">';
			$html_title .= $before_title;
			$html_title .= '<span class="widget_title_first">'.$first.'</span>';
			$html_title .= $other;
			$html_title .= $after_title;
			$html_title .= "</a>";
			$html_title .= "</div>";
			$html_title .= '<div class="widget_sep"></div>';

		}

				?>

			<?php echo $before_widget; ?>

						<div id="widget_FB" class="widget">

								<?php echo $html_title; ?>

								<div id="widget_FB_content">
									<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FRunnosphere%2F115298091866515&amp;width=292&amp;colorscheme=light&amp;connections=10&amp;stream=false&amp;header=false&amp;height=250&amp;locale=fr_FR" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:250px;"></iframe>
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
						<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Url:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></label></p>
				<?php
		}

} // class runnosphere_FB

// register FooWidget widget
add_action('widgets_init', create_function('', 'return register_widget("runnosphere_FB");'));

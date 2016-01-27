<?php

/*
Plugin Name: Standard - Runnosphère.org
Plugin URI:
Author: The Runnosphere team
Author URI: http://www.runnosphere.org
Description: Widget "Standard" : Affichage de la boîte standard (titre)
Version: 1.1

Last MAJ : Insertion of HTML Code in the form, for custom Widget by the admin.

*/

/**
 * runnosphere_ALONE Class
 */
class runnosphere_ALONE extends WP_Widget {
    /** constructor */
    function runnosphere_ALONE() {
        parent::WP_Widget(false, $name = 'Standard - Runnosphere');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$link = apply_filters('widget_link', $instance['link']);
		$html_code = apply_filters('widget_html_code', $instance['html_code']);
		
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
            
            <div class="widget">
                
                <?php echo $html_title; ?>
                
                <div class="widget_content">
                	<?php echo $html_code; ?>
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
		$instance['html_code'] = $new_instance['html_code'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
		$link = esc_attr($instance['link']);
		$html_code = $instance['html_code'];
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Url:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('html_code'); ?>"><?php _e('HTML code:'); ?> <textarea class="widefat" id="<?php echo $this->get_field_id('html_code'); ?>" name="<?php echo $this->get_field_name('html_code'); ?>" rows="10" style="resize:vertical;"><?php echo $html_code; ?></textarea></p>
        <?php 
    }

} // class runnosphere_FB

// register FooWidget widget
add_action('widgets_init', create_function('', 'return register_widget("runnosphere_ALONE");'));

<?php

/*
Plugin Name: Geo-BlogRoll - Runnosphère.org
Plugin URI:
Author: The Runnosphere team
Author URI: http://www.runnosphere.org
Description: Widget "Geo-BlogRoll" : Affichage de la boîte blogroll avec le paramètre géographique
Version: 1.0

Last MAJ : 

*/

/**
 * runnosphere_GEO_BLOGROLL Class
 */
class runnosphere_GEO_BLOGROLL extends WP_Widget {
    /** constructor */
    function runnosphere_GEO_BLOGROLL() {
        parent::WP_Widget(false, $name = 'Geo-BlogRoll - Runnosphere');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$link = apply_filters('widget_link', $instance['link']);
		
		$cat_id = apply_filters('widget_cat_id', $instance['cat_id']);
		
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
		
		$args = array(
		'category_name'    => $cat_id,
		'hide_invisible'   => 1,
		'show_updated'     => 0,
		'echo'             => 1,
		'categorize'       => 0,
		'category_orderby' => 'name',
		'category_order'   => 'ASC',
		'class'            => '' );
				
        ?>
        
			<?php echo $before_widget; ?>
            
            <div class="widget">
                
                <?php echo $html_title; ?>
                
                <div class="widget_content blogroll_list">
                	<?php 
					
						$global = get_bookmarks( $args ); 
						
						foreach ( $global as $bm ) {
							
							$html = "<div class='blogroll_el'> - <a href='".$bm->link_url."' target='".$bm->link_target."'>".$bm->link_name."</a></div>";	
							print($html);
							
						}
						
						
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
		$instance['cat_id'] = strip_tags($new_instance['cat_id']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
		$link = esc_attr($instance['link']);
		$cat_id = esc_attr($instance['cat_id']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Url:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></label></p>
            
            <!--<p><label for="<?php echo $this->get_field_id('cat_id'); ?>"><?php _e('Category:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('cat_id'); ?>" name="<?php echo $this->get_field_name('cat_id'); ?>" type="text" value="<?php echo $cat_id; ?>" /></label></p>-->
            
        <?php
			
			//Select
			$taxonomy = 'link_category';
			$title = 'Link Category: ';
			$args ='';
			$terms = get_terms( $taxonomy, $args );
			
			$sel_html = '<p><label for="'.$this->get_field_id('cat_id').'">'._e('Category:').'<select class="widefat" id="'.$this->get_field_id('cat_id').'" name="'.$this->get_field_name('cat_id').'">';
			
			if ($terms) {
				
				foreach($terms as $term) {
					
					if ($term->count > 0) {
					
					$sel_html .= '<option value="'.$term->slug.'" ';
					
					if($cat_id == $term->slug){
						
						$sel_html .= 'selected="selected" ';
						
					}
					
					$sel_html .= '>'. $term->name .'('.$term->count.')'.'</option> ';
					
					}
					
				}
				
			}
			
			$sel_html .= '</select></label></p>';
			
			echo $sel_html;
			
		?>
     	
        <?php 
    }

} // class runnosphere_GEO_BLOGROLL

// register FooWidget widget
add_action('widgets_init', create_function('', 'return register_widget("runnosphere_GEO_BLOGROLL");'));

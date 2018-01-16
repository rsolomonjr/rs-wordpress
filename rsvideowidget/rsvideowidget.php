<?php

	// Plugin Name: Rodney Video Widget
	// Plugin URI: http://rodneysolomonjr.net
	// Description: Video Widget and Posting Metadata
	// Version: 1.0
	// Author: Rodney Solomon Jr 
	// Author URI: http://rodneysolomonjr.net
 	// License: GPL2

add_action('add_meta_boxes', 'rsavw_add_metabox');

//save metabox data
add_action('save_post', 'rsavw_save_metabox' ); 

//register widgets
add_action('widgets_init', 'rsavw_widget_init');

function rsavw_add_metabox (){
	//doc http://codex.wordpress.org/Function_Reference/add_meta_box
	add_meta_box('rsavw_youtube', 'YouTube Video Link', 'rsavw_youtube_handler', 'post');
}

/* metabox handler */ 

function rsavw_youtube_handler() {
	$value = get_post_custom($post->ID);
	$youtube_link = esc_attr($value['rsavw_youtube'][0]);
	echo '<label for="rsavw_youtube"></label>
			<input type="text" id="rsavw_youtube" name="rsavw_youtube" value="'.$youtube_link.'" />';
}

/* save metadata */ 

function rsavw_save_metabox($post_id) {
	//don't save metadata if it's autosaving
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
		return;
	}

	//check if the user can edit the post
	if(!current_user_can('edit_post')){
		return;
	}

	if(isset($_POST['rsavw_youtube'])){
		update_post_meta($post_id, 'rsavw_youtube', esc_url( $_POST['rsavw_youtube']));
	}
}

/**
 * register widget
 */
function rsavw_widget_init() {
    register_widget(rsavw_Widget);
}

/**
 * widget class
 */
class rsavw_Widget extends WP_Widget {
    function rsavw_Widget() {
        $widget_options = array(
            'classname' => 'rsavw_class', //CSS
            'description' => 'Show a YouTube Video from post metadata'
        );
        
        $this->WP_Widget('rsavw_id', 'RS YouTube Video', $widget_options);
    }
    
    /**
     * show widget form in Appearence / Widgets
     */
    function form($instance) {
        $defaults = array('title' => 'Video');
        $instance = wp_parse_args( (array) $instance, $defaults);
        
        $title = esc_attr($instance['title']);
        
        echo '<p>Title <input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'" /></p>';
    }
    
    /**
     * save widget form
     */
    function update($new_instance, $old_instance) {
        
        $instance = $old_instance;        
        $instance['title'] = strip_tags($new_instance['title']);        
        return $instance;
    }
    
    /**
     * show widget in post / page
     */
    function widget($args, $instance) {
        extract( $args );        
        $title = apply_filters('widget_title', $instance['title']);
        
        //show only if single post
        if(is_single()) {
            echo $before_widget;
            echo $before_title.$title.$after_title;
            
            //get post metadata
            $rsavw_youtube = esc_url(get_post_meta(get_the_ID(), 'rsavw_youtube', true));
            
            //print widget content
            echo '<div style="text-align:center"><iframe width="400" height="200" frameborder="0" allowfullscreen src="http://www.youtube.com/embed/'.get_yt_videoid($rsavw_youtube).'"></iframe></div>';       
            
            echo $after_widget;
        }
    }
}

/**
 * get youtube video id from link 
 * from: http://stackoverflow.com/questions/3392993/php-regex-to-get-youtube-video-id
 */
function get_yt_videoid($url) {
    parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
    return $my_array_of_vars['v']; 
}

?>
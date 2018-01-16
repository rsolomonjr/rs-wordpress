<?php
	 
	// Plugin Name: Rodney Happy Title
	// Plugin URI: http://rodneysolomonjr.net
	// Description: Learn how to use simple filters.
	// Version: 1.0
	// Author: Rodney Solomon Jr 
	// Author URI: http://rodneysolomonjr.net
 	// License: GPL2

add_filter('the_title', 'rstitle_title');
add_filter('the_content', 'rstitle_content'); 
add_filter('list_cats', 'rstitle_categories'); 

/* modify the title */ 
function rstitle_title($text){
	return '~Rodney~ '.$text; 
}

/* modify the content */ 

function rstitle_content($text){
	return strtoupper($text); 
}

/* modify categories */ 

function rstitle_categories($text){
	return strtolower($text); 
}
	 
?> 
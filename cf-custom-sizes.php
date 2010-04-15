<?php
/*
Plugin Name: CF Custom Image Sizes
Plugin URI: http://crowdfavorite.com
Description: Enables a filterable list of custom image sizes
Version: 1.1 (trunk)
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/

/**
 * cf_setup_custom_image_sizes()
 * Function to add all necessary filters to enable a custom image size
 * @return void
 */
function cf_setup_custom_image_sizes() {
	global $cf_custom_image_sizes; 

	$cf_custom_image_sizes = apply_filters('cf_custom_image_sizes', array());
	/* For each of all the custom_options options, add
	a filter to all the get_options */
	foreach($cf_custom_image_sizes as $option_name => $option_values) {
		foreach ($option_values as $option_detail_name => $option_detail_value) {
			add_filter('pre_option_'.$option_name.'_'.$option_detail_name, 'cf_custom_image_filterer');
		}
	}
}
add_action('init', 'cf_setup_custom_image_sizes');

/**
 * cf_add_image_sizes
 * Makes WordPress aware of the new sizes, and their specific 'handle'
 * @param array $sizes 
 * @return array
 */
function cf_add_image_sizes($sizes) {
	global $cf_custom_image_sizes;
	
	$size_handles = array_keys($cf_custom_image_sizes);
	foreach ($size_handles as $handle) {
		$sizes[] = $handle;
	}
    return $sizes;
}
add_filter('intermediate_image_sizes', 'cf_add_image_sizes');

/**
 * cf_custom_image_filterer()
 * This is the function that executes during each custom image size filter.  It parses the 
 * config array and returns the appropriate value for the specific filter WordPress is running.
 * @param mixed $val 
 * @return int/bool
 */
function cf_custom_image_filterer($val) {
	global $wp_current_filter, $cf_custom_image_sizes;
	
	/* Figure out which filter we're acutally running */
	$cur_filter = end($wp_current_filter);
	
	/* Grab the custom image sizes' handle */
	$custom_sizes = array_keys($cf_custom_image_sizes);
	
	/* Loop through each and see if we're doing that one */
	foreach ($custom_sizes as $size) {
		/* Look for the size handle in the current filter string */
		if (strpos($cur_filter, $size)) { 
			/* trim off all but the size detail ('size_w', etc...) */
			$sub_filter = str_replace('pre_option_'.$size.'_', '', $cur_filter);

			/* If we got something, go ahead and return it */
			if (isset($cf_custom_image_sizes[$size][$sub_filter])) {
				return $cf_custom_image_sizes[$size][$sub_filter];
			}
			break;
		}
	}
	return $val;
}
?>
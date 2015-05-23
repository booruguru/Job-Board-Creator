<?php

// This section is meant to automatically insert the relevant jobs template into a theme (that wasn't designed for use with jobs).

function jbc_add_post_content($content) {

    if ( 'jobs' == get_post_type() && is_single() ) {

	$content = jbc_get_template_part('forum' , 'post') . jbc_get_template_part('archive' , 'footer');
	
	return $content;

	} elseif( 'jobs' == get_post_type() && is_archive() ) {
	
	$content = jbc_get_template_part('archive' , 'header') . jbc_get_template_part('archive' , 'loop') . jbc_get_template_part('archive' , 'footer');

	return $content;

	}

}

add_filter('the_content', 'jbc_add_post_content');
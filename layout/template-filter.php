<?php

add_filter( 'template_include', 'jbc_template_filter', 99 );

function jbc_template_filter( $template ) {

	if( 'jobs' == get_post_type() && is_archive() ) {
		$new_template = locate_template( array( 'jobs.php' , 'generic.php' , 'page.php' , 'single.php' , 'index.php' ) );
		if ( '' != $new_template ) {
			return $new_template ;
		}
	}

	return $template;
}
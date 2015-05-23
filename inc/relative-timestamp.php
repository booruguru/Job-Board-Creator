<?php

// http://www.jasonbobich.com/wordpress/a-better-way-to-add-time-ago-to-your-wordpress-theme/

/**
 * Convert a UNIX timestamp to a diff time (eg: ' ago')
 */
function convert_date($date) {

	/**
	 * Where you see 'themeblvd' below, you'd
	 * want to replace those with whatever term
	 * you're using in your theme to provide
	 * support for localization.
	 */ 
 
	// Array of time period chunks
	$chunks = array(
		array( 60 * 60 * 24 * 365 , __( 'year', 'themeblvd' ), __( 'years', 'themeblvd' ) ),
		array( 60 * 60 * 24 * 30 , __( 'month', 'themeblvd' ), __( 'months', 'themeblvd' ) ),
		array( 60 * 60 * 24 * 7, __( 'week', 'themeblvd' ), __( 'weeks', 'themeblvd' ) ),
		array( 60 * 60 * 24 , __( 'day', 'themeblvd' ), __( 'days', 'themeblvd' ) ),
		array( 60 * 60 , __( 'hour', 'themeblvd' ), __( 'hours', 'themeblvd' ) ),
		array( 60 , __( 'minute', 'themeblvd' ), __( 'minutes', 'themeblvd' ) ),
		array( 1, __( 'second', 'themeblvd' ), __( 'seconds', 'themeblvd' ) )
	);

	if ( !is_numeric( $date ) ) {
		$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
		$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
		$date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
	}
 
	$current_time = current_time( 'mysql', $gmt );
	$newer_date = ( !$newer_date ) ? strtotime( $current_time ) : $newer_date;
 
	// Difference in seconds
	$since = $newer_date - $date;

	// Something went wrong with date calculation and we ended up with a negative date.
	if ( 0 > $since )
		return __( 'sometime', 'themeblvd' );
 
	/**
	 * We only want to output one chunks of time here, eg:
	 * x years
	 * xx months
	 * so there's only one bit of calculation below:
	 */
 
	//Step one: the first chunk
	for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];
 
		// Finding the biggest chunk (if the chunk fits, break)
		if ( ( $count = floor($since / $seconds) ) != 0 )
			break;
	}
 
	// Set output var
	$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
 
 
	if ( !(int)trim($output) ){
		$output = '0 ' . __( 'seconds', 'themeblvd' );
	}

	// $output .= __('ago', 'themeblvd');
	// I removed the suffix "ago" since I don't necessarily want that displayed all of the time. Especially on the message board index.
 
	$output .= __('', 'themeblvd');

	return $output;
}


/**
 * Our post time filter
 */
if(!function_exists('themeblvd_time_ago')) :
	function themeblvd_time_ago() {
		global $post;
		$date = get_post_time('G', true, $post);
		return convert_date($date);
	}

endif;
 

/**
 * Our comment time filter
 */
if(!function_exists('themeblvd_comment_time_ago')) :
	function themeblvd_comment_time_ago($d) {
		global $comment;
		return convert_date(strtotime($comment->comment_date));
	}
	add_filter('get_comment_time', 'themeblvd_comment_time_ago');
endif;

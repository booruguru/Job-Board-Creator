<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

function jbc_get_template_part( $slug, $name = null ) {

	$templates = array();
	$name = (string) $name;
	if ( '' !== $name )
		$templates[] = "{$slug}-{$name}.php";

	$templates[] = "{$slug}.php";

	// No file found yet
	$located            = '';
	$template_locations = array(
		get_stylesheet_directory() . '/jobs/',
		get_template_directory() . '/jobs/',
		JBC_DIR . 'templates/'
	);

	// Try to find a template file
	foreach ( (array) $templates as $template ) {

		if ( empty( $template ) ) {
			continue;
		}
		$template  = ltrim( $template, '/' );

		foreach ( (array) $template_locations as $template_location ) {

			if ( empty( $template_location ) ) {
				continue;
			}

			if ( file_exists( trailingslashit( $template_location ) . $template ) ) {
				$located = trailingslashit( $template_location ) . $template;
				break 2;
			}
		}
	}
	if ( !empty( $located ) ) {
		load_template( $located, true );
	}
} 

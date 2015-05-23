<?php

if ( ! function_exists('jobs_post_type') ) {

// Register Custom Post Type
function jobs_post_type() {

	$labels = array(
		'name'                => _x( 'Jobs', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Job', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Jobs', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Jobs:', 'text_domain' ),
		'all_items'           => __( 'All Jobs', 'text_domain' ),
		'view_item'           => __( 'View Job', 'text_domain' ),
		'add_new_item'        => __( 'Add New Job', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'edit_item'           => __( 'Edit Job', 'text_domain' ),
		'update_item'         => __( 'Update Job', 'text_domain' ),
		'search_items'        => __( 'Search Jobs', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$args = array(
		'label'               => __( 'job', 'text_domain' ),
		'description'         => __( 'jobs bank', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		'taxonomies'          => array( 'jobs_categories', 'jobs_tags', 'job_type' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'jobs', $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobs_post_type', 0 );

}



if ( ! function_exists('application_post_type') ) {

// Register Custom Post Type
function application_post_type() {

	$labels = array(
		'name'                => _x( 'Applications', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Application', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Applications', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Applications:', 'text_domain' ),
		'all_items'           => __( 'All Applications', 'text_domain' ),
		'view_item'           => __( 'View Application', 'text_domain' ),
		'add_new_item'        => __( 'Add New Application', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'edit_item'           => __( 'Edit Application', 'text_domain' ),
		'update_item'         => __( 'Update Application', 'text_domain' ),
		'search_items'        => __( 'Search Applications', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$args = array(
		'label'               => __( 'application', 'text_domain' ),
		'description'         => __( 'application bank', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'custom-fields', 'comments' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'application', $args );

}

// Hook into the 'init' action
add_action( 'init', 'application_post_type', 0 );

}



function custom_rewrite_tag() {
 	add_rewrite_tag('%job%', '([^&]+)');
 	add_rewrite_tag('%activity%', '([^&]+)');
}
add_action('init', 'custom_rewrite_tag', 10, 0);
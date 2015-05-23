<?php

if ( ! function_exists( 'job_type' ) ) {

// Register Custom Taxonomy
function job_type() {

	$labels = array(
		'name'                       => _x( 'Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Types', 'text_domain' ),
		'all_items'                  => __( 'All Types', 'text_domain' ),
		'parent_item'                => __( 'Parent Type', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Type:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Type', 'text_domain' ),
		'add_new_item'               => __( 'Add New Type', 'text_domain' ),
		'edit_item'                  => __( 'Edit Type', 'text_domain' ),
		'update_item'                => __( 'Update Type', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'text_domain' ),
		'search_items'               => __( 'Search Types', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove type', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used types', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                       => 'jobs/type',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'job_type', array( 'jobs' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'job_type', 0 );

}

/*      */

if ( ! function_exists( 'job_category' ) ) {

// Register Custom Taxonomy
function job_category() {

	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Categories', 'text_domain' ),
		'all_items'                  => __( 'All Categories', 'text_domain' ),
		'parent_item'                => __( 'Parent Category', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Category:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Category', 'text_domain' ),
		'add_new_item'               => __( 'Add New Category', 'text_domain' ),
		'edit_item'                  => __( 'Edit Category', 'text_domain' ),
		'update_item'                => __( 'Update Category', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'text_domain' ),
		'search_items'               => __( 'Search Categories', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove category', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used categories', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                       => 'jobs/category',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'job_category', array( 'jobs' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'job_category', 0 );

}

/*      */

if ( ! function_exists( 'job_view' ) ) {

// Register Custom Taxonomy
function job_view() {

	$labels = array(
		'name'                       => _x( 'Views', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'View', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Views', 'text_domain' ),
		'all_items'                  => __( 'All Views', 'text_domain' ),
		'parent_item'                => __( 'Parent View', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent View:', 'text_domain' ),
		'new_item_name'              => __( 'New Item View', 'text_domain' ),
		'add_new_item'               => __( 'Add New View', 'text_domain' ),
		'edit_item'                  => __( 'Edit View', 'text_domain' ),
		'update_item'                => __( 'Update View', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'text_domain' ),
		'search_items'               => __( 'Search Views', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove view', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used views', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                       => 'jobs/view',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'job_view', array( 'jobs' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'job_view', 0 );

}

/*      */

if ( ! function_exists( 'job_status' ) ) {

// Register Custom Taxonomy
function job_status() {

	$labels = array(
		'name'                       => _x( 'Statuses', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Status', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Statuses', 'text_domain' ),
		'all_items'                  => __( 'All Statuses', 'text_domain' ),
		'parent_item'                => __( 'Parent Status', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Status:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Status', 'text_domain' ),
		'add_new_item'               => __( 'Add New Status', 'text_domain' ),
		'edit_item'                  => __( 'Edit Status', 'text_domain' ),
		'update_item'                => __( 'Update Status', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'text_domain' ),
		'search_items'               => __( 'Search Statuses', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove status', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used statuses', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                       => 'jobs/status',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'job_status', array( 'jobs' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'job_status', 0 );

}

/*      */

if ( ! function_exists( 'jobs_tags' ) ) {

// Register Custom Taxonomy
function jobs_tags() {

	$labels = array(
		'name'                       => _x( 'Tags', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Tags', 'text_domain' ),
		'all_items'                  => __( 'All Tags', 'text_domain' ),
		'parent_item'                => __( 'Parent Tag', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Tag:', 'text_domain' ),
		'new_item_name'              => __( 'New Tag Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Tag', 'text_domain' ),
		'edit_item'                  => __( 'Edit Tag', 'text_domain' ),
		'update_item'                => __( 'Update Tag', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate tags with commas', 'text_domain' ),
		'search_items'               => __( 'Search Tags', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove tags', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used tags', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                       => '/jobs/tag',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'jobs_tags', array( 'jobs' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobs_tags', 0 );

}

if ( ! function_exists( 'job_company' ) ) {

// Register Custom Taxonomy
function job_company() {

	$labels = array(
		'name'                       => _x( 'Companies', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Company', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Companies', 'text_domain' ),
		'all_items'                  => __( 'All Companies', 'text_domain' ),
		'parent_item'                => __( 'Parent Company', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Company:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Company', 'text_domain' ),
		'add_new_item'               => __( 'Add New Company', 'text_domain' ),
		'edit_item'                  => __( 'Edit Company', 'text_domain' ),
		'update_item'                => __( 'Update Company', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'text_domain' ),
		'search_items'               => __( 'Search Companies', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove type', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used companies', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                       => 'jobs/company',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'job_company', array( 'jobs' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'job_company', 0 );

}





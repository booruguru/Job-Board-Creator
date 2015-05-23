<?php



add_action('after_switch_theme', 'default_pages');

function default_pages() {

if( get_page_by_title('Post') == false ) {

wp_insert_post( 
	array(
	  'post_title'    => 'Post',
	  'post_name'    => 'post',  
	  'post_type'    => 'page',
	  'post_status'   => 'publish',
	  'page_template' => 'job-form.php'
	)
);

}

if( get_page_by_title('Profile') == false ) {

wp_insert_post( 
	array(
	  'post_title'    => 'Profile',
	  'post_name'    => 'profile',  
	  'post_type'    => 'page',
	  'post_status'   => 'publish',
	  'page_template' => 'user-profile.php'
	)
);

}

if( get_page_by_title('Listings') == false ) {

wp_insert_post( 
	array(
	  'post_title'    => 'Listings',
	  'post_name'    => 'listings',  
	  'post_type'    => 'page',
	  'post_status'   => 'publish',
	  'page_template' => 'listings.php'
	)
);

}


}
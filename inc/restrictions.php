<?php
$owner = get_post_meta($post->ID, "employer-id", $single = true );

add_action( 'template_redirect', function() {

  if ( is_user_logged_in() || ! is_page() ) return;

  $restricted = array( 250, 253 ); // all your restricted pages

  if ( in_array( get_queried_object_id(), $restricted ) ) {
    wp_redirect( site_url( '/user-registration' ) ); 
    exit();
  }

});


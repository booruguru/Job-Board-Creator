<?php 

function setReadUnread($postID) {

	if(is_single()) {
	    $status_key = 'post_read_unread';
	    $status = get_post_meta($postID, $status_key, true);
    	if($status !=="Read"){
    	    add_post_meta($postID, $status_key, 'Read');
	    }
	}

}
// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); 



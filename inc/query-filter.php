<?php



// Applications
	if ($_GET['action'] == "applications") { 
	
        $query->set( 'post_type', 'application' );
        $query->set( 'meta_key', 'employer-id' );
        $query->set( 'meta_value', $current_user->ID); 

// Manage Jobs
        
	} elseif ($_GET['action'] == "manage") {
		'post_type' => 'jobs',
    	'post_status' => get_post_stati()
    	
    }
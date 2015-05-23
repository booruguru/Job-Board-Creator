<?php

if ( ! function_exists('transactions_post_type') ) {

	// Register Custom Post Type
	function transactions_post_type() {

		$labels = array(
			'name'                => _x( 'Transactions', 'Transactions Type General Name', 'text_domain' ),
			'singular_name'       => _x( 'Transaction', 'Transactions Type Singular Name', 'text_domain' ),
			'menu_name'           => __( 'Transactions', 'text_domain' ),
			'parent_item_colon'   => __( 'Parent Transactions:', 'text_domain' ),
			'all_items'           => __( 'Transactions', 'text_domain' ),
			'view_item'           => __( 'View Transaction', 'text_domain' ),
			'add_new_item'        => __( 'Add New Transaction', 'text_domain' ),
			'add_new'             => __( 'Add New', 'text_domain' ),
			'edit_item'           => __( 'Edit Transaction', 'text_domain' ),
			'update_item'         => __( 'Update Transaction', 'text_domain' ),
			'search_items'        => __( 'Search Transaction', 'text_domain' ),
			'not_found'           => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
		);
		$args = array(
			'label'               	=> __( 'transaction', 'text_domain' ),
			'description'         	=> __( 'transactions bank', 'text_domain' ),
			'labels'              	=> $labels,
			'supports'            	=> array( 'title', 'author', 'revisions', 'custom-fields' ),
			'hierarchical'        	=> false,
			'public'              	=> true,
			'show_ui'             	=> true,
			'show_in_menu'        	=> false,
			'show_in_nav_menus'   	=> true,
			'show_in_admin_bar'   	=> true,
			'menu_position'       	=> 100,
			'can_export'          	=> true,
			'has_archive'         	=> true,
			'exclude_from_search' 	=> true,
			'publicly_queryable'  	=> true,
			'map_meta_cap'		  	=> true,
			'capability_type'     	=> array('transaction', 'transactions'),
			'capabilities' 			=> array(
				'edit_posts' 			=> 'edit_transactions',
				'edit_post' 			=> 'edit_transaction',
				'edit_others_posts' 	=> 'edit_others_transactions',
				'read_private_posts' 	=> 'read_private_transactions',
				'delete_posts' 			=> 'delete_transactions',
				'delete_post' 			=> 'delete_transaction',
				'read_post' 			=> 'read_transaction',
				'read_posts' 			=> 'read_transactions',
				'publish_posts' 		=> 'publish_transactions',
				'publish_post' 			=> 'publish_transaction'
			)
		);
		register_post_type( 'transactions', $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'transactions_post_type', 0 );

}

//Set transactions roles
if (!function_exists('register_transactions_submenu')) {
	add_action('admin_init', 'transactions_add_role_caps', 999);
	function transactions_add_role_caps() {
	    $wp_roles = new WP_Roles();
	    //All roles
	    $wp_roles = $wp_roles->get_names();
		foreach($wp_roles as $name => $r) { 
			$role = get_role($name);
		    $role->add_cap('read');
		    $role->add_cap('read_transaction');
		    $role->add_cap('read_transactions');
		    $role->add_cap('read_private_transactions');
		    $role->add_cap('read_private_transaction');
		    $role->add_cap('publish_transactions');
		}
	}
}

//Register transactions submenu
if (!function_exists('register_transactions_submenu')) {

	//Save transactions submenu
	function register_transactions_submenu() {
		add_submenu_page('index.php', 'Transactions', 'Transactions', 'read_transactions', 'transactions', 'transactions_submenu');
	}
	add_action( 'admin_menu', 'register_transactions_submenu' );

	function transactions_submenu() {
			$args = array(
				'post_type' => 'transactions', 
				'numberposts' => -1, 
				'post_status' => null
			);
			$transactions = get_posts($args);
		?>
			<div class="wrap"><div id="icon-tools" class="icon32"></div>
				<h2><?php _e('Transactions', 'jbf') ?></h2>
				<table class="wp-list-table widefat fixed posts">
					<thead>
						<tr>
							<th scope="col" id="job" class="manage-column column-job" style=""><?php _e('Job', 'jbf') ?></th>
							<th scope="col" id="id" class="manage-column column-id" style=""><span><?php _e('Paypal ID', 'jbf') ?></span></th>
							<th scope="col" id="author" class="manage-column column-author" style=""><?php _e('Author', 'jbf') ?></th>
							<th scope="col" id="amount" class="manage-column column-amount" style=""><?php _e('Amount', 'jbf') ?></th>
							<th scope="col" id="payment_type" class="manage-column column-payment_type" style=""><?php _e('Payment type', 'jbf') ?></th>
							<th scope="col" id="payment_status" class="manage-column column-payment_status" style=""><?php _e('Payment status', 'jbf') ?></th>
							<th scope="col" id="pending_reason" class="manage-column column-pending_reason" style=""><?php _e('Pending reason', 'jbf') ?></th>
							<th scope="col" id="order_time" class="manage-column column-order_time" style=""><?php _e('Order time', 'jbf') ?></th>
						</tr>
					</thead>
					<tbody id="the-list">
						<?php if(!empty($transactions)) : ?>
							<?php foreach($transactions as $transaction) : ?>
								<?php $meta = get_post_meta($transaction->ID); ?>
								<?php $job = get_post($meta["post_parent"][0]); ?>
								<tr id="post-<?php echo $job->ID ?>" class="post-<?php echo $job->ID ?> type-jobs status-publish has-post-thumbnail hentry alternate iedit author-self level-0">
									<td class="job column-job">
										<a href="<?php echo admin_url() ?>post.php?post=<?php echo $job->ID ?>&amp;action=edit"><?php echo $job->post_title ?></a>
									</td>
									<td class="amount column-transaction_id"><?php echo $meta["transaction_id"][0] ?></td>
									<td class="author column-author">
										<a href="<?php echo admin_url() ?>user-edit.php?user_id=<?php echo $transaction->post_author ?>">
											<?php the_author_meta('user_nicename', $transaction->post_author) ?>
										</a>
									</td>
									<td class="amount column-amount"><?php echo $meta["currency_code"][0].' '.$meta["amount"][0] ?></td>
									<td class="payment_status column-payment_type"><?php echo $meta["payment_type"][0] ?></td>
									<td class="payment_status column-payment_status"><?php echo $meta["payment_status"][0] ?></td>
									<td class="pending_reason column-pending_reason"><?php echo $meta["pending_reason"][0] ?> (<?php echo $meta["reason_code"][0] ?>)</td>
									<td class="order_time column-order_time"><?php echo $meta["order_time"][0] ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		<?php
	}
}

//Add transaction job_id meta box
if (!function_exists('my_add_meta_boxes')) {
	function my_add_meta_boxes($post) {
	    add_meta_box(
	        'job-parent',
	        __( 'Job', 'jbf'),
	        'job_parent_meta_box',
	        $post->post_type,
	        'side',
	        'core'
	    );
	}
	add_action('add_meta_boxes_transactions', 'my_add_meta_boxes');
}

//Displays transaction job_id meta box
if (!function_exists('job_parent_meta_box')) {
	function job_parent_meta_box( $post ) {
	    $parents = get_posts(
	        array(
	            'post_type'   => 'jobs', 
	            'orderby'     => 'title', 
	            'order'       => 'ASC', 
	            'numberposts' => -1 
	        )
	    );

	    if (!empty($parents)) {
	        echo '<select name="parent_id" class="widefat">'; // !Important! Don't change the 'parent_id' name attribute.
	        foreach($parents as $parent) {
	            printf('<option value="%s"%s>%s</option>', esc_attr($parent->ID), selected($parent->ID, $post->post_parent, false), esc_html($parent->post_title));
	        }
	        echo '</select>';
	    }
	}
}
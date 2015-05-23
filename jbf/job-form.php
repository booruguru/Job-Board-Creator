<?php


session_start();



/*

Template Name: Job Form

*/



	//Rewrite rules

	//Don't forget to look in jobs-cpt.php for rewrite tag

	function custom_rewrite_rule($wp_rewrite) {

		global $post;

		$job_rules = array(

	        $post->post_name.'/(.+)'.'/(.+)'  	=>  'index.php?page_id='.get_the_ID().'&job='.$wp_rewrite->preg_index(1).'&activity='.$wp_rewrite->preg_index(2),

	        $post->post_name.'/(.+)'  			=>  'index.php?page_id='.get_the_ID().'&job='.$wp_rewrite->preg_index(1),

	    );

	    $wp_rewrite->rules = $job_rules + $wp_rewrite->rules;

	}

	add_filter('generate_rewrite_rules', 'custom_rewrite_rule');

	flush_rewrite_rules();



	//Get current taxo for this job

	//Return ID

	function get_current_terms($job_id, $taxonomy) {

		$current = array();

		$terms = wp_get_post_terms($job_id, $taxonomy);

    	if(count($terms) > 0) {

    		foreach($terms as $term) {

    			$current[] = $term->term_id;

    		}

    	}

    	return $current;

	}


	function count_taxonomy_select($taxonomy, $current) {
		$count = get_taxonomy_terms_children(0, 0, $taxonomy, $current, $select);
		return $count;
	}

	//Display taxo select

	function show_taxonomy_select($taxonomy, $current) {

		echo '<select multiple name="'.$taxonomy.'[]" class="postform">';

		echo get_taxonomy_terms_children(0, 0, $taxonomy, $current, $select);

		echo '</select>';

	}	


	//Recursive function to get children

	function get_taxonomy_terms_children($level = 0, $term_id = 0, $taxonomy, $current, &$select) {

		$args = array(

		    'orderby'           => 'name', 

		    'order'             => 'ASC',

		    'parent'            => $term_id,

		    'hide_empty'        => false, 

		    'fields'            => 'all', 

		    'hierarchical'      => true, 

		);

		$terms = get_terms($taxonomy, $args);

		$level = $term_id != 0 ? $level+=1 : 0;

		if (!empty($terms) && !is_wp_error($terms)) {

			foreach($terms as $term) {

				$selected_option = in_array($term->term_id, $current) ? 'selected="selected"' : '';

				$select .= '<option '.$selected_option.' value="'.$term->term_id.'">'.str_repeat('— ', $level).' '.$term->name.'</option>';

				get_taxonomy_terms_children($level, $term->term_id, $taxonomy, $current, $select);

			}

		}

		return $select;

	}



	function insert_attachment($file_handler, $post_id, $setthumb='false') {

	    // check to make sure its a successful upload

	    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

	 

	    require_once(ABSPATH . "wp-admin" . '/includes/image.php');

	    require_once(ABSPATH . "wp-admin" . '/includes/file.php');

	    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	 

	    $attach_id = media_handle_upload( $file_handler, $post_id );

	 

	    if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);

	    return $attach_id;

	}



	//Save a purchase post

	function save_transaction($data) {

		$current_user = wp_get_current_user();

		$default = array(

			'post_author'	=> $current_user->ID,

			'post_type'		=> 'transactions',

			'post_status'	=> 'publish'

		);

		$post_information = array_merge($data, $default);

		$post_id = wp_insert_post($post_information, true);



		if(isset($post_id) && !is_wp_error($post_id)) {

			foreach($data as $label => $value) {

				update_post_meta($post_id, $label, $value);

			}

			return $post_id;

		} else {

			return false;

		}

	}



	//Check if user is allowed to post / edit

	function is_user_allowed($job) {

		if(is_user_logged_in() && (intval($job->post_author) == get_current_user_id() || is_super_admin())) {

			return true;

		} else {

			return false;

		}

	}



	//Get job status ID by name

	function get_job_status($status) {

		$args = array(

		    'orderby'           => 'name', 

		    'order'             => 'ASC',

		    'hide_empty'        => false, 

		    'fields'            => 'all',

		    'name__like'        => $status

		); 

		$terms = get_terms('job_status', $args);

		if(!empty($terms)) {

			return $terms[0]->term_id;

		} else {

			return false;

		}

	}



	//Set a status

	function set_status($status, $job_id) {

		$status_id = get_job_status($status);

		if($status_id) {

			wp_set_post_terms($job_id, $status_id, 'job_status');

		}

	}



	$current_user = wp_get_current_user();

	$job = null; //Job object

	$meta = array(); //Job meta fields

	$can_display_form = true; //Can we display form ?

	$save_draft = false; //Are we saving a draft?

	$job_saved = false; //Job has just been saved

	$transaction_infos = false; //Transaction post type infos

	$is_renewal = false; //Is a subscription renewal

	$is_draft_publish = false; //Publishing a draft?

	$required_fields = array( //Required fields = key is name and value is message

		'post-title' => __('Job title', 'jbf'),

		'post-content' => __('Job content', 'jbf')

	);



	if(isset($wp_query->query_vars['activity']) && $wp_query->query_vars['activity'] == 'renew') {

		$is_renewal = true;

	}



	//can we display form ?

	if(!is_user_logged_in()) {

		$can_display_form = false;

		$errors["not_auth"] = __("You are not authorized to view this content", 'jbf');

	}



	//We have form submit

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		$job = null;

		$meta = null;

		//Data

		$post_title = '';
		if(isset($_POST['post-title'])) {
			$post_title     = sanitize_text_field($_POST['post-title']);
		}

		$post_content = '';
		if(isset($_POST['post-content'])) {
			$post_content	= esc_textarea($_POST['post-content']);
		}

		$tags = '';
		if(isset($_POST['tags'])) {
			$tags			= sanitize_text_field($_POST['tags']);
		}

		$job_type = '';
		if(isset($_POST['job-type'])) {
			$job_type		= sanitize_text_field($_POST['job-type']);
		}

		$company_email = '';
		if(isset($_POST['company-email'])) {
			$company_email  = sanitize_text_field($_POST['company-email']);
		}

		$job_salary = '';
		if(isset($_POST['job-salary'])) {
			$job_salary     = sanitize_text_field($_POST['job-salary']);
		}

		$unit_number = '';
		if(isset($_POST['unit-number'])) {
			$unit_number    = sanitize_text_field($_POST['unit-number']);
		}

		$street_number = '';
		if(isset($_POST['street-number'])) {
			$street_number  = sanitize_text_field($_POST['street-number']);
		}

		$street_name = '';
		if(isset($_POST['street-name'])) {
			$street_name    = sanitize_text_field($_POST['street-name']);
		}

		$zip_postal = '';
		if(isset($_POST['zip-postal'])) {
			$zip_postal     = sanitize_text_field($_POST['zip-postal']);
		}


		$location = '';
		if(isset($_POST['location'])) {
			$location           = sanitize_text_field($_POST['location']);
		}

		$company_name = '';
		if(isset($_POST['company-name'])) {
			$company_name   = sanitize_text_field($_POST['company-name']);
		}

		if(isset($_POST['payment-method'])) {

			$payment_method = sanitize_text_field($_POST['payment-method']);

		}



		//We only want to save post as draft, listing is not required

		if(isset($_POST['save_draft'])) {

			$save_draft = true;

		} else {

			$required_fields['job-view'] = __('Listing option', 'jbf');

		}



		//Required fields

		$missing_required = array();

		foreach($required_fields as $field => $value) {

			if(!isset($_POST[$field]) || sanitize_text_field($_POST[$field]) == '') {

				$missing_required[$field] = $value;

			}

		}



		//Cat

		$categories = array();

		if(isset($_POST['job_category']) && !empty($_POST['job_category'])) {

			foreach($_POST['job_category'] as $category) {

				$categories[] = sanitize_text_field($category);

			}

		}



		//Types
		$post_types = array();
		if(isset($_POST['job-type']) && sanitize_text_field($_POST['job-type']) != '') {
			$post_types[] = sanitize_text_field($_POST['job-type']);
		}

		//Views
		$job_views = array();
		if(isset($_POST['job-view'])) {
			$job_views[] = sanitize_text_field($_POST['job-view']);
		}

		//Expiration date
		$duration = 0;
		if(!empty($job_views)) {
			foreach(get_option("item_duration") as $id => $duration_meta) {
				if($id == $job_views[0]) {

					$duration = $duration_meta;

				}

			}
		}


		$expirationdate = date('m/d/Y H:i:s', strtotime(" +".$duration." days"));

		// echo 'Duration days: '.$duration.'<br/>';
		// echo 'Expiration date: '.$expirationdate.'<br/>';

		//Price

		$current_price = 0;
		if(!empty($job_views)) {
			foreach(get_option("item_price") as $id => $price) {

				if($id == $job_views[0]) {

					$current_price = $price;

				}

			}
		}



		$errors = array();

		//Validate email. Is it required?

		if($company_email != '') {

			if(!is_email($company_email)) {

				$errors["company_email"] = __("Email is invalid", 'jbf');

			}

		}



		//We are not missing required fields or have any errors

		if(empty($missing_required) && empty($errors)) {

			$post_information = array(

				'post_author'	=> $current_user->ID,

				'post_title'	=> $post_title,

				'post_content'	=> $post_content,

				'post_type'		=> 'jobs',							

				'tags_input'	=> $tags,

				'post_status'	=> of_get_option( 'job_post_status', 'pending' )

			);

			//We have job ID filled so it's an update

			if(isset($_POST["job-id"]) && $_POST["job-id"] != '') {

				$job_id = $_POST["job-id"];

				$job = get_post($job_id);

				//Check is everything is alright

				if($job && $job->post_status == 'publish' && is_user_allowed($job)) {

					$post_information["ID"] = $job->ID;

					$post_information["post_status"] = 'publish';

					$post_id = wp_update_post($post_information);

					$current_status = get_the_terms($job->ID, 'job_status');

					//Are we publishing a draft?

					if($current_status && !empty($current_status)) {

						$current_status = $current_status[0]->name;

						if($current_status == 'Outstanding' && !$save_draft) {

							$is_draft_publish = true;

						}

					}

					$is_new_post = false;

				} else {

					//Not auth or not authorized

					if(!is_user_allowed($job)) {

						$errors["not_auth"] = __("You are not authorized to edit this content", 'jbf');

					} else {

						$errors["no_exists"] = __("This job doesn't exist" , 'jbf');

					}

					$can_display_form = false;

				}

			} else {

				if(is_user_logged_in()) {

					//It's a new post

					$post_id = wp_insert_post($post_information, true);
					$job = get_post($post_id);

					$is_new_post =  true;

				} else {

					$errors["not_auth"] = __("You are not authorized to publish this content", 'jbf');

					$can_display_form = false;

				}

			}



			//Everything is ok we can add / update meta
			if(isset($post_id) && !is_wp_error($post_id)) {

				//If renewal, we should check if current expiration date + duration is > current date + duration

				if($is_renewal) {

					$temp_meta = get_post_meta($post_id);

					$current_expiration_duration = date('m/d/Y H:i:s', strtotime($temp_meta["expiration"][0]." +".$duration." days"));

					//it's more, change expiration

					if(strtotime($current_expiration_duration) > strtotime($expirationdate)) {

						$expirationdate = $current_expiration_duration;

					}

				}

				//We only save / update duration date if it's not a draft and is a new post or renewal

				if(($is_new_post || $is_renewal || $is_draft_publish) && !$save_draft) {

					update_post_meta($post_id, 'expiration',  $expirationdate);

				}



				update_post_meta($post_id, 'company-email',  $company_email);

				update_post_meta($post_id, 'job-salary',  $job_salary);

				update_post_meta($post_id, 'unit-number',  $unit_number);

				update_post_meta($post_id, 'street-number',  $street_number);

				update_post_meta($post_id, 'street-name',  $street_name);

				update_post_meta($post_id, 'zip-postal',  $zip_postal);

				update_post_meta($post_id, 'location',  $location);


				if(isset($listing_tier)) {

					update_post_meta($post_id, 'listing-tier',  $listing_tier);

				}



				if(isset($payment_method)) {

					update_post_meta($post_id, 'payment-method',  $payment_method);

				}



				//Taxos

				wp_set_post_terms($post_id, $categories, 'job_category');

				wp_set_post_terms($post_id, $post_types, 'job_type');

				wp_set_post_terms($post_id, $job_views, 'job_view');

				wp_set_post_terms($post_id, $company_name, 'job_company');



				//Set default status to new posts only

				if($is_new_post) {

					set_status('Outstanding', $post_id);

				}



				//File uploading
				if($_FILES) {
				    foreach($_FILES as $file => $array) {
				    	if($array["size"] != 0) {
					    	$newupload = insert_attachment($file, $post_id);
				    	}
				    }
				}



				//Handle payment if new job or job renewal and it's not a draft

				if(($is_new_post || $is_renewal || $is_draft_publish) && !$save_draft) {

					//Price is null

					if($current_price == '' || $current_price == 0) {

						//Change status to paid

						set_status('Paid', $job->ID);

						$job_saved = $post_id;

						$can_display_form = false;

						//Empty post data if success

						$_POST = array();

					} else {

						$ppParams = array(

						    'METHOD'         => 'SetExpressCheckout',

						    'PAYMENTACTION'  => 'Sale',

						    'AMT'            => urlencode($current_price),

						    'NOSHIPPING'     => '1',

						    'RETURNURL'      => get_permalink(),

						    'CANCELURL'      => get_permalink()

						);

						if(!$is_new_post) {

							$ppParams['CANCELURL'] = get_permalink().$job->ID;

						}

						if($is_renewal && isset($job->ID)) {

							$ppParams['CANCELURL'] .= '/renew/';

						}



						$response = PPHttpPost($ppParams);



						//We got token

						if(strtoupper($response["ACK"]) == 'SUCCESS') {

							// redirect to paypal

							$paypalurl ='https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$response["TOKEN"].'';

							//Job ID in session

							$_SESSION["job_id"] = $post_id;

							header('Location: '.$paypalurl);

							exit;

						} else {

							//Change status to failed

							set_status('Failed', $post_id);

							$errors["unknown"] = __("An unexpected error occurred during transaction", 'jbf');

						}

					}

				} else {

					//We are only updating or saving a draft

					$job_saved = $post_id;

					$can_display_form = false;

					//Empty post data if success

					$_POST = array();

				}

				$meta = get_post_meta($job->ID);

			} else {

				if(!is_wp_error($post_id)) {

					//Handle exception

					if(empty($errors)) {

						$errors["unknown"] = __("An unexpected error occurred", 'jbf');

					}

				}

			}

		}

	}



	//We fetch from an existing job offer with GET parameter

	if(isset($wp_query->query_vars['job'])) {

		$job = get_post($wp_query->query_vars['job']);

		if($job && $job->post_status == 'publish') {

			$meta = get_post_meta($job->ID);

		} else {

			if($is_renewal) {

				$errors["no_exists"] = __("A job must be specified", 'jbf');

			} else {

				$errors["no_exists"] = __("This job doesn't exist", 'jbf');

			}

			$can_display_form = false;

		}

	}



	//Paypal TOKEN if transaction is occurring

	if(

		isset($_GET["token"]) && 

		$_GET["token"] != '' && 

		isset($_GET["PayerID"]) && 

		$_GET["PayerID"] != ''

	) {

		//We have job_id saved in session

		if(isset($_SESSION["job_id"]) && $_SESSION["job_id"] != '') {

			$token = $_GET["token"];

			$payer_id = $_GET["PayerID"];

			$job = get_post($_SESSION["job_id"]);

			if($job && is_user_allowed($job)) {

				$meta = get_post_meta($job->ID);

				$current_taxo = get_current_terms($job->ID, 'job_view');

				foreach(get_option("item_price") as $id => $price) {

					if($id == $current_taxo[0]) {

						$current_price = $price;

					}

				}



				//Paypal request

				$ppParams = array(

				    'METHOD'         => 'DoExpressCheckoutPayment',

				    'TOKEN'          => urlencode($token),

				    'PAYERID'        => urlencode($payer_id),

				    'PAYMENTACTION'  => 'Sale',

				    'AMT'            => urlencode($current_price),

				    'NOSHIPPING'     => '1',

				);

				$response = PPHttpPost($ppParams);



				//Everything ok

				if(strtoupper($response["ACK"]) == 'SUCCESS') {

					$job_saved = $job->ID;

					$can_display_form = false;

					$transaction_infos = $response;

					//Change status to paid

					set_status('Paid', $job->ID);

					//Save transaction

					$transaction_data = array(

						'post_title'						=> $job->post_title,

						'post_parent'						=> $job->ID,

						'job_id'							=> $job->ID,

						'token' 							=> $transaction_infos["TOKEN"],

						'transaction_id' 					=> $transaction_infos["TRANSACTIONID"],

						'transaction_type' 					=> $transaction_infos["TRANSACTIONTYPE"],

						'payment_type' 						=> $transaction_infos["PAYMENTTYPE"],

						'order_time' 						=> date_format(date_create($transaction_infos["ORDERTIME"]), "Y-m-d H:i:s"),

						'amount' 							=> $transaction_infos["AMT"],

						'fee_amount' 						=> $transaction_infos["FEEAMT"],

						'tax_amount' 						=> $transaction_infos["TAXAMT"],

						'currency_code' 					=> $transaction_infos["CURRENCYCODE"],

						'payment_status' 					=> $transaction_infos["PAYMENTSTATUS"],

						'pending_reason' 					=> $transaction_infos["PENDINGREASON"],

						'reason_code' 						=> $transaction_infos["REASONCODE"],

						'protection_eligibility' 			=> $transaction_infos["PROTECTIONELIGIBILITY"],

						'insureance_option_selected' 		=> $transaction_infos["INSURANCEOPTIONSELECTED"],

						'shipping_option_is_default' 		=> $transaction_infos["SHIPPINGOPTIONISDEFAULT"],

					);

					$transaction_saved = save_transaction($transaction_data);

					if(!$transaction_saved) {

						$errors["unknown"] = __("An unexpected error occurred while saving transaction", 'jbf');

					}

				} else {

					$errors["unknown"] = __("An unexpected error occurred during payment", 'jbf');

				}

			} else {

				$errors["unknown"] = __("An unexpected error occurred during payment", 'jbf');

			}

			unset($_SESSION["job_id"]);

		} else {

			$errors["unknown"] = __("An unexpected error occurred during payment", 'jbf');

		}

	}



?>



<?php

	get_header();

?>

<div class="row title-header">
<div class="medium-12 columns">
		<?php if (isset($wp_query->query_vars['job'])) { ?>

		<h1>Edit Job</h1>		

		<?php } else { ?>

		<h1>Job Post Form</h1>

		<?php } ?>
</div>
</div>
	

<div class="row" id="wrapper">
<div class="medium-9 columns" id="main-content">


		<!-- Main Content -->

		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>

				<?php global $post; ?>

				<?php the_content(); ?>

			<?php endwhile; ?>

		<?php endif; ?>



		<!-- Form -->

		<?php if(!is_user_logged_in()) : ?>

			<strong><?php _e('Please login or create an account', 'jbf') ?></strong><br /><br />

			<?php 

				echo wp_login_form();

		//Job already exists but user is not allowed

		elseif($job && !is_user_allowed($job)) :

			?>

				<div data-alert class="alert-box alert round"><?php _e('You are not authorized to view this content', 'jbf') ?></div>

			<?php			

		else : ?>

			<?php

				//Permalink for job action

				$link = isset($job->ID) ? get_permalink().$job->ID : get_permalink();

				if($is_renewal && isset($job->ID)) {

					$link .= '/renew/';

				}



				//Handle required fields

				if(isset($missing_required) && !empty($missing_required)) {

					foreach($missing_required as $name => $value) {

						?>

							<div data-alert class="alert-box warning round"><?php echo $value.' '.__('is required') ?></div>

						<?php

					}

				}

				//Handle required fields

				if(isset($errors) && !empty($errors)) {

					foreach($errors as $name => $value) {

						?>

							<div data-alert class="alert-box alert round"><?php echo $value ?></div>

						<?php

					}

				}

				//We just saved a job

				if($job_saved) {

					//With transaction

					if($transaction_infos) {

						?>

							<div data-alert class="alert-box success radius"><?php _e('The purchase has been successfully completed', 'jbf') ?>

							<br/><br/>

							<?php echo _('Expiration date:').' '.$meta["expiration"][0] ?>

							</div>

						<?php



						//Display paypal transaction infos

						echo '<p>'.__('Job: ').esc_html($job->post_title).'</p>';

						echo '<p>'.__('Transaction date: ').date_format(date_create($transaction_infos["ORDERTIME"]), "Y-m-d H:i:s").'</p>';

						echo '<p>'.__('Payment type: ').$transaction_infos["PAYMENTTYPE"].'</p>';

						echo '<p>'.__('Amount: ').$transaction_infos["CURRENCYCODE"].' '.$transaction_infos["AMT"].'</p>';

						echo '<p>'.__('Payment status: ').$transaction_infos["PAYMENTSTATUS"].'</p>';

					} else {

						if($save_draft) {

							?>

								<div data-alert class="alert-box success radius"><?php _e('The job offer has been successfully saved as a draft', 'jbf') ?></div>

							<?php

						} else {
							if(isset($wp_query->query_vars['job'])) {
								?>

								<div data-alert class="alert-box success radius"><?php _e('The job offer has been successfully updated', 'jbf') ?></div>
								
								<?php

							} else {
								?>

								<div data-alert class="alert-box success radius"><?php _e('The job offer has been successfully saved', 'jbf') ?>
								<br/><br/><?php echo _('Expiration date:').' '.$meta["expiration"][0] ?>
								<br/><br/>
								<?php $s = $duration > 1 ? 's' : ''; ?>
								Your job offer will expire in <?php echo $duration ?> day<?php echo $s ?>.
								</div>

								<?php
							}
						}	

					}

					if(isset($wp_query->query_vars['job'])) {

						//Reset job object if it's only a POST

						$job = null;

					}

				}

				if($can_display_form) :

			?>

					<form id="fep-new-post" name="new_post" method="post" action="<?php echo $link ?>" enctype="multipart/form-data">

						<input type="hidden" name="action" value="post" />

						<input type="hidden" name="empty-description" id="empty-description" value="1"/>

						<?php if(isset($job->ID) || isset($_POST["job-id"])) : ?>

							<?php $id = isset($job->ID) ? $job->ID : $_POST["job-id"]; ?>

							<input type="hidden" name="job-id" value="<?php echo $id ?>"/>

						<?php endif; ?>

						<p>

							<label><?php _e('Job Title/Headline', 'jbf') ?></label>
							<?php
								if(isset($job->post_title)) {
									$value = esc_html($job->post_title);
								} elseif(isset($_POST["post-title"])) {
									$value = esc_html($_POST["post-title"]);
								} else {
									$value = '';
								}
							?>
							<input type="text" id="fep-post-title" required value="<?php echo $value ?>" name="post-title" />

						</p>

						<p>

							<label><?php _e('Company', 'jbf') ?></label>

							<?php 

								$value = '';

							    if(isset($job->ID) || isset($_POST["company-name"])) {

							    	if(isset($job->ID)) {

								    	$terms = get_the_terms($job->ID, 'job_company');

								    	if ($terms && !is_wp_error($terms)) :

								    		$value = esc_html($terms[0]->name);

							    		endif;

							    	} else {

							    		$value = esc_html($_POST["company-name"]);

							    	}

							    }

							?>

							<input type="text" id="fep-post-company" value="<?php echo $value ?>" name="company-name" />

						</p>

						<p><label><?php _e('Content', 'jbf') ?></label>

						 <?php 				 

							 $settings = array('textarea_name' => 'post-content', 'media_buttons' => false);

							 if(isset($job->post_content)) {
							 	$content = esc_html($job->post_content);
							 } else if(isset($_POST["post-content"])) {
							 	$content = esc_html($_POST["post-content"]);
							 } else {
							 	$content = '';
							 }

							 wp_editor($content, 'post-content', $settings); 

						 ?> 

						</p>

						<div class="row">

							<div class="medium-4 columns" >
								<?php
									if(isset($meta["job-salary"][0])) {
									 	$value = esc_html($meta["job-salary"][0]);
									 } else if(isset($_POST["job-salary"])) {
									 	$value = esc_html($_POST["job-salary"]);
									 } else {
									 	$value = '';
									 }
								?>
								<p><label><?php _e('Salary/Wage', 'jbf') ?></label><input type="text" id="fep-post-salary" value="<?php echo $value ?>" name="job-salary" /></p>

							</div>

							<?php

							$current = array();

						    if(isset($job)) {

						    	$current = get_current_terms($job->ID, 'job_category');

						    } else if(isset($_POST["job_category"])) {

						    	foreach($_POST["job_category"] as $cat) {

						    		$current[] = $cat;

						    	}

						    }

						    $count_categories = count_taxonomy_select('job_category', $current);

						    if($count_categories) :

						    ?>

							<div class="medium-4 columns" >

								<p><label><?php _e('Category', 'jbf') ?></label>

									<?php 

										show_taxonomy_select('job_category', $current);

									?>

								</p>

							</div>

							<?php endif; ?>

							<div class="medium-4 columns" >

								<p><label><?php _e('Type', 'jbf') ?></label>

									<?php

										$args = array(

											'show_option_all'    => '',

											'show_option_none'   => '',

											'orderby'            => 'ID', 

											'order'              => 'ASC',

											'hide_empty'         => false, 

											'child_of'           => 0,

											'exclude'            => '',

											'echo'               => 1,

											'depth'              => 3,

											'taxonomy'           => 'job_type',

											'name'               => 'job-type',

										);

										$current = '';

									    if(isset($job) || isset($_POST["job-type"])) {

									    	if(isset($job)) {
									    		$types = get_current_terms($job->ID, 'job_type');
									    		if(!empty($a)) {
									    			$current = $types[0];
									    		}
									    	} else {

									    		$current = $_POST["job-type"];

									    	}

									    	$args["selected"] = esc_html($current);

									    }

										wp_dropdown_categories($args);

									?>

								</p>

							</div>

						</div>

						<div class="row">

							<div class="medium-12 columns" >

								<p><label><?php _e('Upload Logo', 'jbf') ?></label>

								<input id="fep-tags" name="post-logo" type="file" tabindex="2" autocomplete="on"></p>

								<?php

									if(isset($job->ID)) {

										$args = array(

											'post_type' => 'attachment', 

											'numberposts' => -1, 

											'post_status' => null, 

											'post_parent' => $job->ID 

										);

										$attachments = get_posts($args);

										if ($attachments) {

											the_attachment_link($attachments[0]->ID , false);

										}

									}

								?>

							</div>

						</div>


						<div class="row">

							<div class="medium-12 columns" >
								<?php
									if(isset($meta["location"][0])) {
									 	$value = esc_html($meta["location"][0]);
									 } else if(isset($_POST["location"])) {
									 	$value = esc_html($_POST["location"]);
									 } else {
									 	$value = '';
									 }
								?>
								<p><label><?php _e('Location', 'jbf') ?></label><input type="text" id ="fep-post-location" placeholder="e.g. New York City (Manhattan), New York" value="<?php echo $value ?>" name="location" /></p>

							</div>


						</div>

						<?php
							if(isset($meta["company-email"][0])) {
							 	$value = esc_html($meta["company-email"][0]);
							 } else if(isset($_POST["company-email"])) {
							 	$value = esc_html($_POST["company-email"]);
							 } else {
							 	$value = '';
							 }
						?>
						<p><label><?php _e('E-Mail Address', 'jbf') ?> <small><?php _e('(Applications will be sent to this address.)', 'jbf') ?></small></label><input type="text" id ="fep-post-email" value="<?php echo $value ?>" name="company-email" /></p>
			
									

						<label><?php _e('Listing', 'jbf') ?></label>

						<?php

							$current = '';

						    if(isset($job) || isset($_POST["job-view"])) {

						    	if(isset($job)) {
						    		$views = get_current_terms($job->ID, 'job_view');
						    		if(!empty($views)) {
						    			$current = $views[0];
						    		}
						    	} else {

						    		$current = esc_html($_POST["job-view"]);

						    	}

						    	$current = $current;

						    }



							$args = array(

							    'orderby'           => 'name', 

							    'order'             => 'ASC',

							    'hide_empty'        => false, 

							    'fields'            => 'all'

							); 

							$terms = get_terms('job_view', $args);


							$prices = get_option("item_price");

							foreach($terms as $term) :

								$checked = $current == $term->term_id ? 'checked' : '';

								$price = isset($prices[$term->term_id]) ? $prices[$term->term_id] : 0;

								?>

									<p><label><input type="radio" name="job-view" <?php echo $checked ?> value="<?php echo $term->term_id ?>"> <?php echo $term->name.' ($'.$price.')'; ?><span class="field-description"> <?php if ($term->description) { echo $term->description; } ?></span></label> </p>
									
								<?php
	
							endforeach;



						$display_payment_method = true;

						//Check if plugin is active
						include_once(ABSPATH.'wp-admin/includes/plugin.php');
						if(!is_plugin_active('job-payment/job-payment.php')) {
							$display_payment_method = false;
						}

						//We already have a paid status for this job

						if(isset($job)) {

							$statuses = get_the_terms($job->ID, 'job_status');

							if($statuses && !empty($statuses)) {

								foreach($statuses as $status) {

									if($status->name == 'Paid' && !$is_renewal) {

										$display_payment_method = false;

									}

								}

							}

						}

						if($display_payment_method) : ?>

							<label><?php _e('Payment Method', 'jbf') ?></label>

							<div class="row">

								<?php

									if(isset($meta["payment-method"][0])) {
										$value = $meta["payment-method"][0];
									} else if(isset($_POST["payment-method"])) {
										$value = $_POST["payment-method"];
									} else {
										$value = '';
									}
								?>

								<div class="medium-6 columns" >

									<p><label><input type="radio" name="payment-method" <?php if($value == 'paypal') echo 'checked' ?> value="paypal"> <?php _e('Paypal Listing', 'jbf') ?></label> </p>

								</div>

							</div>

						<?php endif; ?>

						<?php 

							if($is_renewal) {

								$label = 'Renew';

							} else if(isset($job->ID) || isset($_POST["job-id"])) {

								$current_status = get_the_terms($job->ID, 'job_status');

								if($current_status && !empty($current_status)) {

									$current_status = $current_status[0]->name;

									if($current_status == 'Outstanding') {

										$label = 'Publish';	

									} else {

										$label = 'Update';

									}

								} else {
									$label = 'Publish';	
								}

							} else {

								$label = 'Publish';

							}


						?>

						<input id="submit" class="button small right" type="submit" tabindex="3" value="<?php esc_attr_e( $label, 'simple-fep' ); ?>" />
						<?php if((!isset($current_status) || $current_status != 'Paid') && !$is_renewal) : ?>

							<input id="submit" name="save_draft" class="button small right" type="submit" tabindex="3" value="<?php esc_attr_e( 'Save Draft', 'simple-fep' ); ?>" />

						<?php endif; ?>

					</form>

				<?php endif; ?>

		<?php endif; ?>

	</div>

	<!-- SIDEBAR -->

	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>

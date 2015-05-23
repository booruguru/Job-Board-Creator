<?php
$my_posts = array();
$current_user = wp_get_current_user();

if(is_user_logged_in()) {
    $args = array(
    	'post_author'	 => $current_user->ID,
    	'post_type'      => 'application',
    	'post_status'    => 'publish',
    	'meta_query'     => array(
            array(
                'key'    => 'job-id',
                'value'  => get_the_ID(),
        	)	
    	)	
    );
    $my_posts = get_posts($args);
}

if(!$my_posts || is_super_admin()) {
    if(!empty($_POST)) {
        $errors = array();
        $success = '';

        //Handle uploaded file
        function upload_file($file) {
            $errors = array();

            require_once(ABSPATH."wp-admin".'/includes/file.php');

            $upload_overrides = array(
                'test_form' => false,
                'unique_filename_callback' => 'rename_md5_file',
                'mimes' => get_allowed_mime_types()
            );

            $movefile = wp_handle_upload($file, $upload_overrides);
            if($movefile && !isset($movefile['error'])) {
                $_POST["file-attachment"] = $movefile["url"];
            } else {
                if(isset($movefile['error'])) {
                    $errors[] = $movefile['error'];
                }
            }
            return $errors;
        }

        //Standard File uploading
        if($_FILES) {
            foreach($_FILES as $file => $array) {
                if($array["size"] != 0 && $array["error"] == 0) {
                    $errors = upload_file($array);
                }
            }
        }

        //Required fields
        //Key is name and value is error message
        $required_fields = array(
            'app-firstname'     => __('Firstname is required.'),
            'app-lastname'      => __('Lastname is required.'),
            'app-email'         => __('Email is required.'),
            'app-content'       => __('Content is required.')
        );

        foreach($required_fields as $key => $field) {
            if(!isset($_POST[$key]) || sanitize_text_field($_POST[$key]) == '') {
                $errors[] = $field;
            }
            if($key == 'app-email') {
               if(!is_email($_POST[$key])) {
                    $errors[] = __('Email is invalid');   

               }
            }
        }

        if(empty($errors)) {
            function wpse128767_add_meta($pid) {
                $metas = array('app-firstname', 'app-lastname', 'app-email', 
                'app-phone', 'app-url', 'app-address', 'file-attachment');
                foreach($metas as $meta) {
                    if(isset($_POST[$meta]) && $_POST[$meta] != '') {
                        add_post_meta($pid, $meta, sanitize_text_field($_POST[$meta]), true);
                    }
                }
                add_post_meta($pid, 'job-id', get_the_ID(), true);
                add_post_meta($pid, 'employer-id', get_the_author_meta('ID'), true);
            }
            add_action('wp_insert_post', 'wpse128767_add_meta', 1);

            // Add the content of the form to $post as an array
            $new_post = array(
                'post_content'  => $_POST['app-content'],
                'post_status'   => 'publish',
                'post_type'     => 'application'
            );
            //Save the new post and return its ID
            $pid = wp_insert_post($new_post); 
            $flag = get_term_by( 'slug', 'pending', 'application_flag' );
			wp_set_post_terms($pid, $flag->term_id, 'application_flag');
			
            //Email notif

            include(JBC_DIR . '/mail/apply-notification.php');

            //Success message
            $success = __('Your application has been successfully saved');

            unset($_POST);
        }
    }
?>
    <h3 id="apply" >Apply For Job</h3>
    <p>(You can attach your resume as a file.)</p>
    <?php
        if(isset($errors) && !empty($errors)) {
            foreach($errors as $error) {
                ?><div data-alert class="alert-box alert round"><?php echo $error ?></div><?php
            }
        }
    if(isset($success) && $success != '') {
        ?><div data-alert class="alert-box success radius"><?php echo $success ?></div><?php
    } else {
        ?>
            <form id="new-app-form" name="new_app" method="post" action="<?php the_permalink(); ?>" enctype="multipart/form-data">
                <!-- Consider forcing applicant to complete their profile (phone number, email, etc.) before submitting -->
                <div class="row">
                    <div class="medium-6 columns"> 
                        <?php 
                            $value = is_user_logged_in() ? get_the_author_meta('first_name', $current_user->ID) : '';
                            $value = isset($_POST["app-firstname"]) ? $_POST["app-firstname"] : $value;
                        ?>
                        <label>First Name</label>
                            <input required type="text" id="job-app-firstname" name="app-firstname"  value="<?php echo $value ?>">
                        
                    </div>
                    <div class="medium-6 columns"> 
                        <?php 
                            $value = is_user_logged_in() ? get_the_author_meta('last_name', $current_user->ID) : '';
                            $value = isset($_POST["app-lastname"]) ? $_POST["app-lastname"] : $value;
                        ?>
                        <label>Last Name</label>
                            <input required type="text" id="job-app-lastname" name="app-lastname"  value="<?php echo $value ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="medium-6 columns"> 
                        <?php 
                            $value = is_user_logged_in() ? get_the_author_meta('email', $current_user->ID) : '';
                            $value = isset($_POST["app-email"]) ? $_POST["app-email"] : $value;
                        ?>
                        <label>E-Mail</label>
                            <input required type="text" id="job-app-email" name="app-email" value="<?php echo $value; ?>">
                    </div>
                    <div class="medium-6 columns"> 
                        <?php 
                            $value = is_user_logged_in() ? get_the_author_meta('phone', $current_user->ID) : '';
                            $value = isset($_POST["app-phone"]) ? $_POST["app-phone"] : $value;
                        ?>
                        <label>Phone Number</label>
                            <input type="text" id="job-app-phone" name="app-phone" value="<?php echo $value; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="medium-12 columns"> 
                        <?php 
                            $value = is_user_logged_in() ? get_the_author_meta('url', $current_user->ID) : '';
                            $value = isset($_POST["app-url"]) ? $_POST["app-url"] : $value;
                        ?>
                        <label>Website URL</label>
                            <input type="text" id="job-app-url" name="app-url" value="<?php echo $value ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="medium-12 columns"> 
                        <?php 
                            $value = is_user_logged_in() && get_the_author_meta('address', $current_user->ID) != '' ? get_the_author_meta('address', $current_user->ID).' ' : '';
                            $value .= is_user_logged_in() && get_the_author_meta('postcode', $current_user->ID) != '' ? get_the_author_meta('postcode', $current_user->ID).' ' : '';
                            $value .= is_user_logged_in() && get_the_author_meta('city', $current_user->ID) != '' ? get_the_author_meta('city', $current_user->ID).' ' : '';
                            $value .= is_user_logged_in() && get_the_author_meta('state', $current_user->ID) != '' ? get_the_author_meta('state', $current_user->ID).' ' : '';
                            $value = isset($_POST["app-address"]) ? $_POST["app-address"] : $value;
                        ?>
                        <label>Home Address</label>
                            <input type="text" id="job-app-address" name="app-address" value="<?php echo $value ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="medium-12 columns">
                        <?php $value = isset($_POST["app-content"]) ? $_POST["app-content"] : ''; ?>
                        <label>Content</label>
                            <textarea required id="job-app-content" name="app-content"><?php echo $value ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="medium-12 columns"> 
                        <?php if(is_user_logged_in()) : ?>
                            <div class="uploader">
                                <p class="file_name"></p>
                                <input id="_unique_name" class="file_attachment_field" name="file-attachment" type="hidden" />
                                <input id="_unique_name_button" class="button button_upload" name="" type="text" value="Attach File" />
                            </div>
                            <?php 
                                wp_enqueue_media();
                                wp_handle_front_media_upload('.uploader .button_upload', '.file_attachment_field'); 
                            ?>
                        <?php else: ?>
                            <input name="file-attachment" type="file" tabindex="2" autocomplete="on">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="medium-12 columns text-right"> 
                        <input id="submit" value="submit" type="submit" class="button small" onsubmit="document.getElementById('Submit').disabled = 1;">
                    </div>
                </div>
            </form>
        <?php
    }
} else {
    echo "<strong>You have applied to this job.</strong>";
}

<?php

/**
 * Full function for handling front-end media upload
 * @param: string: button selector
 * @param: string: field return
 */
function wp_handle_front_media_upload($button, $return) {
	//JS for button
	echo '
		<style type="text/css">
			/* Hide visually but not from screen readers */
			.screen-reader-text,
			.screen-reader-text span,
			.ui-helper-hidden-accessible {
				position: absolute;
				margin: -1px;
				padding: 0;
				height: 1px;
				width: 1px;
				overflow: hidden;
				clip: rect(0 0 0 0);
				border: 0;
			}

			.screen-reader-shortcut {
				position: absolute;
				top: -1000em;
			}

			.screen-reader-shortcut:focus {
				right: 6px;
				top: -25px;
				height: auto;
				width: auto;
				display: block;
				font-size: 14px;
				font-weight: 600;
				padding: 15px 23px 14px;
				background: #f1f1f1;
				color: #21759b;
				z-index: 100000;
				line-height: normal;
				-webkit-box-shadow: 0 0 2px 2px rgba(0,0,0,.6);
				box-shadow: 0 0 2px 2px rgba(0,0,0,.6);
				text-decoration: none;
				outline: none;
			}
			.attachments-browser .media-toolbar {
				display: none;
			}
		</style>
	';
	echo '
	<script type="text/javascript">
		jQuery(document).ready(function($){
			var image_custom_uploader;
	        $("'.$button.'").click(function(e) {
	            e.preventDefault();
	            var $thisItem = $(this);

	            //If the uploader object has already been created, reopen the dialog
	            if(image_custom_uploader) {
	                image_custom_uploader.open();
	                return;
	            }

	            //Extend the wp.media object
	            image_custom_uploader = wp.media.frames.file_frame = wp.media({
	                title: "Choose Document",
	                button: {
	                    text: "Choose Document"
	                },
	                multiple: false
	            });

	            //When a file is selected, grab the URL and set it as the text field\'s value
	            image_custom_uploader.on("select", function() {
	                attachment = image_custom_uploader.state().get("selection").first().toJSON();
	                $("'.$return.'").val(attachment.url);
                    var img = \'<img width="20px" src="\'+attachment.icon+\'"/>&nbsp;&nbsp;&nbsp;\';
                    var delete_button = \'&nbsp;&nbsp;<a class="delete_attachment" href="javascript:void(0)"><i class="fi-x"></i></a>\';
                    $(".file_name").html(img+attachment.title+delete_button);
	            });

	            //Open the uploader dialog
	            image_custom_uploader.open();
	        });

			//delete button
     		$(document).on("click", ".delete_attachment", function(){
     			$(".file_name").html("");
     			$("'.$return.'").val("");
     			return false;
     		});
		});
	</script>';
}

//Check if user is applicant
if (!function_exists('is_applicant')) :
	function is_applicant() {
		$user = wp_get_current_user();
		$roles = array();
		if (!empty($user->roles) && is_array($user->roles)) {
			foreach ($user->roles as $role) {
				$roles[] = $role;
			}
		}
		if(!is_user_logged_in() || (!is_super_admin() && in_array('subscriber', $roles))) {
			return true;
		} else {
			return false;
		}
	}
endif;

//Rename file with hash
if (!function_exists('rename_md5_file')) :
	function rename_md5_file($dir, $name, $ext) {
	    if(is_applicant()) {
	    	return md5($name).$ext;
	    } else {
	    	return $name.$ext;
	    }
	}
endif;

//Create a hash from filename on upload
if (!function_exists('create_filename_hash')) :
	function create_filename_hash($filename) {
		$user = wp_get_current_user();
		$info = pathinfo($filename);
	    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
	    $name = basename($filename, $ext);
	    $name = rename_md5_file('', $name, $ext);
	    return $name;
	}
	add_filter('sanitize_file_name', 'create_filename_hash', 10);
endif;

//Restrict file viewing to owners only
if (!function_exists('restrict_files_viewing')) :
	function restrict_files_viewing($where) {
		if(isset($_POST['action']) && ($_POST['action'] == 'query-attachments') && is_applicant()) {
            $user = wp_get_current_user();
            $where .= ' AND post_author='.$user->ID;
        }
	    return $where;
	}
	add_filter('posts_where', 'restrict_files_viewing');
endif;

//Adjust mime types
if (!function_exists('my_myme_types')) :
	function my_myme_types($mime_types) {
		if(is_applicant()) {
			$mime_types = array(
				'pdf' 					=> 'application/pdf',
				'doc' 					=> 'application/msword',
				'docx' 					=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'odf' 					=> 'application/vnd.oasis.opendocument.formula',
				'rtf' 					=> 'application/rtf',
			 	'txt|asc|c|cc|h|srt' 	=> 'text/plain'
			);
		}
	    return $mime_types;
	}
	add_filter('upload_mimes', 'my_myme_types', 1, 1);
endif;

//Add capability to subscriber to upload files
if (!function_exists('add_subscriber_caps')) :
	function add_subscriber_caps() {
	    $role = get_role('subscriber');
	    $role->add_cap('upload_files'); 
	}
	add_action('admin_init', 'add_subscriber_caps');
endif;

<?php

/**
 * Functions
 *
 * Core functionality and initial theme setup
 *
 * @package WordPress
 * @subpackage JobHunter, for WordPress

 */

/**
 * Initiate JobHunter, for WordPress
 */
 
 
 
 
define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/of/' );
require_once dirname( __FILE__ ) . '/inc/of/options-framework.php';

// Loads options.php from child or parent theme
$optionsfile = locate_template( 'options.php' );
load_template( $optionsfile );

/*
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 *
 * You can delete it if you not using that option
 */
add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#example_showhidden').click(function() {
  		jQuery('#section-example_text_hidden').fadeToggle(400);
	});

	if (jQuery('#example_showhidden:checked').val() !== undefined) {
		jQuery('#section-example_text_hidden').show();
	}

});
</script>

<?php
}

/*
 * This is an example of filtering menu parameters
 */

/*
function prefix_options_menu_filter( $menu ) {
	$menu['mode'] = 'menu';
	$menu['page_title'] = __( 'Hello Options', 'textdomain');
	$menu['menu_title'] = __( 'Hello Options', 'textdomain');
	$menu['menu_slug'] = 'hello-options';
	return $menu;
}

add_filter( 'optionsframework_menu', 'prefix_options_menu_filter' );
*/


function wpsites_home_page_cpt_filter($query) {
if ( !is_admin() && $query->is_main_query() && is_home() ) {
		//$query->set('post_type', array( 'jobs' ) );
    }
  }

add_action('pre_get_posts','wpsites_home_page_cpt_filter');

require_once('inc/theme-installation.php');
require_once('inc/activation/default-pages.php');


 
/**
 * For whatever WordPress does not easily allow author roles to be displayed. So this hack/function is required. 
 * http://wordpress.stackexchange.com/a/58921/13504
 * Get user roles by user ID.
 *
 * @param  int $id
 * @return array
 */
function wpse_58916_user_roles_by_id( $id )
{
    $user = new WP_User( $id );

    if ( empty ( $user->roles ) or ! is_array( $user->roles ) )
        return array ();

    $wp_roles = new WP_Roles;
    $names    = $wp_roles->get_names();
    $out      = array ();

    foreach ( $user->roles as $role )
    {
        if ( isset ( $names[ $role ] ) )
            $out[ $role ] = $names[ $role ];
    }

    return $out;
}











// Disable BuddyPress bar
add_filter('show_admin_bar', '__return_false');

/**
 * Get the highest needed priority for a filter or action.
 *
 * If the highest existing priority for filter is already PHP_INT_MAX, this
 * function cannot return something higher.
 *
 * @param  string $filter
 * @return number|string
 */

// Repsonsive Wrapper for oembed video
add_filter('embed_oembed_html', 'up546E_my_embed_oembed_html', 99, 4);
function up546E_my_embed_oembed_html($html, $url, $attr, $post_id) {
  return '<div class="flex-video widescreen">' . $html . '</div>';
}






/**

LOGIN FORM HACK
wp_login_form

Okay. So why are we doing all of this? 

WordPress offers a default login form function, but it has several problems. First, it doesn't contain a password recovery link. Secondly, if there is an error, it will send the user to the wp-login.php page as opposed to the page they were using to log in. Thirdly, if WordPress is hacked to send the user to the referring page upon an error, the default wp_login_form function has no way of display an error message.

I know this code looks convoluted, but I wanted to extend the functionality of the wp_login_form function while leaving its extensive features in tact so that others can make additional modifications.

As always, if you think that you can improve upon our code, please do so and share it with us. http://userpress.org



**/

add_action( 'login_form_top', 'up546E_add_error_message' );
function up546E_add_error_message() {
	if (isset($_GET['login']) && $_GET['login'] == 'failed') echo "<p class='error'><strong>Error. Please try again<strong></p>" ;
}


add_action( 'login_form_middle', 'up546E_add_lost_password_link' );
function up546E_add_lost_password_link() {
    return '<p><a href="/wp-login.php?action=lostpassword">Lost Password?</a></p>';
}


add_action( 'wp_login_failed', 'up546E_my_front_end_login_fail' ); // hook failed login

function up546E_my_front_end_login_fail( $username ) {
$referrer = $_SERVER['HTTP_REFERER']; // where did the post submission come from?
// if there's a valid referrer, and it's not the default log-in screen
if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
if ( !strstr($referrer,'?login=failed') ) { // make sure we don't append twice
wp_redirect( $referrer . '?login=failed' ); // let's append some information (login=failed) to the URL for the theme to use
} else {
wp_redirect( $referrer );
}
exit;
}
}




function up546E_get_latest_priority( $filter )
{
    if ( empty ( $GLOBALS['wp_filter'][ $filter ] ) )
        return PHP_INT_MAX;

    $priorities = array_keys( $GLOBALS['wp_filter'][ $filter ] );
    $last       = end( $priorities );

    if ( is_numeric( $last ) )
        return PHP_INT_MAX;

    return "$last-z";
}


	// Featured Image Caption Functionality (by Andy Warren http://stackoverflow.com/a/13850898/1289267)
	function the_post_thumbnail_caption() {
	global $post;
	
	$thumbnail_id    = get_post_thumbnail_id($post->ID);
	$thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));
	
	if ($thumbnail_image && isset($thumbnail_image[0])) {
    echo '<span>'.$thumbnail_image[0]->post_excerpt.'</span>';
  }
}	

if ( ! function_exists( 'up546E_jobhunter_setup' ) ) :

function up546E_jobhunter_setup() {

	// Content Width
	if ( ! isset( $content_width ) ) $content_width = 900;

	// Language Translations
	load_theme_textdomain( 'foundation', get_template_directory() . '/languages' );

	// Custom Editor Style Support
	add_editor_style();

	// Support for Featured Images
	add_theme_support( 'post-thumbnails' ); 
	add_image_size( 'featured-image', 300, 300, true ); // custom size
	add_image_size( 'featured-thumbnail', 50, 50, false ); // custom size

	// Automatic Feed Links & Post Formats
	add_theme_support( 'automatic-feed-links' );

}

add_action( 'after_setup_theme', 'up546E_jobhunter_setup' );

endif;

/**
 * Enqueue Scripts and Styles for Front-End
 */

if ( ! function_exists( 'up546E_jobhunter_assets' ) ) :

function up546E_jobhunter_assets() {

	// if (!is_admin()) {


 		wp_deregister_style( 'userpress_foundation_css' );

 		wp_deregister_style( 'userpress_normalize_css' );

 		wp_deregister_style( 'userpress_foundation_icons' );

		// wp_enqueue_script("jquery");
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js', false, '1.11.2', false);
		wp_enqueue_script('jquery');

		
		wp_enqueue_script( 'slimscroll', get_template_directory_uri().'/js/jquery.slimscroll.min.js', true, '1.3', true);
		
		wp_enqueue_script('jobhunter_foundation_js', get_template_directory_uri().'/js/foundation.min.js', array('jquery'), '5.5.1', true);
		wp_enqueue_script('jobhunter_foundation_topbar_js', get_template_directory_uri().'/js/foundation.topbar.js', array('jquery'), '5.5.1', true);
		
		if ( is_singular() ) wp_enqueue_script( "comment-reply" );

		// Load Stylesheets
		wp_enqueue_style( 'normalize', get_template_directory_uri().'/css/normalize.css' );
		wp_enqueue_style( 'foundation', get_template_directory_uri().'/css/foundation.min.css' );
		wp_enqueue_style( 'icons', get_template_directory_uri().'/icons/foundation-icons.css' );
		wp_enqueue_style( 'app', get_stylesheet_uri(), array('foundation') );

	// }

}

add_action( 'wp_enqueue_scripts', 'up546E_jobhunter_assets' );

endif;




/**
* Register Navigation Menus
*/

if ( ! function_exists( 'up546E_foundation_menus' ) ) :

// Register wp_nav_menus
function up546E_foundation_menus() {

        register_nav_menus(
                array(
                        'header-menu' => __( 'Header Menu', 'jobhunter' )
                )
        );
        
}

add_action( 'init', 'up546E_foundation_menus' );

endif;

if ( ! function_exists( 'up546E_foundation_page_menu' ) ) :

function up546E_foundation_page_menu() {

        $args = array(
        'sort_column' => 'menu_order, post_title',
        'menu_class' => 'large-12 columns',
        'include' => '',
        'exclude' => '',
        'echo' => true,
        'show_home' => false,
        'link_before' => '',
        'link_after' => ''
        );

        wp_page_menu($args);

}

endif;

/**
* Navigation Menu Adjustments
*/

// Add class to navigation sub-menu
/*class foundation_navigation extends Walker_Nav_Menu {

function start_lvl(&$output, $depth) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown\">\n";
}

function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $id_field = $this->db_fields['id'];
        if ( !empty( $children_elements[ $element->$id_field ] ) ) {
                $element->classes[] = 'sub-menu dropdown';
        }
                Walker_Nav_Menu::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
        }
}*/
class foundation_navigation extends Walker_Nav_Menu {
  /**
    * @see Walker_Nav_Menu::start_lvl()
   * @since 1.0.0
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param int $depth Depth of page. Used for padding.
  */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= "\n<ul class=\"sub-menu dropdown\">\n";
    }

    /**
     * @see Walker_Nav_Menu::start_el()
     * @since 1.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     */

    function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
        $item_html = '';
        parent::start_el( $item_html, $object, $depth, $args );  

        //$output .= ( $depth == 0 ) ? '<li class="divider"></li>' : '';

        $classes = empty( $object->classes ) ? array() : ( array ) $object->classes;  

        if ( in_array('label', $classes) ) {
            $item_html = preg_replace( '/<a[^>]*>( .* )<\/a>/iU', '<label>$1</label>', $item_html );
        }

    if ( in_array('divider', $classes) ) {
      $item_html = preg_replace( '/<a[^>]*>( .* )<\/a>/iU', '', $item_html );
    }

        $output .= $item_html;
    }

  /**
     * @see Walker::display_element()
     * @since 1.0.0
   * 
   * @param object $element Data object
   * @param array $children_elements List of elements to continue traversing.
   * @param int $max_depth Max depth to traverse.
   * @param int $depth Depth of current element.
   * @param array $args
   * @param string $output Passed by reference. Used to append additional content.
   * @return null Null on failure with no changes to parameters.
   */
    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $element->has_children = !empty( $children_elements[$element->ID] );
        $element->classes[] = ( $element->current || $element->current_item_ancestor ) ? 'active' : '';
        $element->classes[] = ( $element->has_children ) ? 'has-dropdown' : '';

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

}



/**
 * Create pagination
 */

if ( ! function_exists( 'up546E_jobhunter_pagination' ) ) :

function up546E_jobhunter_pagination() {

global $wp_query;

$big = 999999999;

$links = paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'prev_next' => true,
	'prev_text' => '&laquo;',
	'next_text' => '&raquo;',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $wp_query->max_num_pages,
	'type' => 'list'
)
);

$pagination = str_replace('page-numbers','pagination',$links);

echo $pagination;

}

endif;

/**
 * Register Sidebars
 */

if ( ! function_exists( 'up546E_jobhunter_widgets' ) ) :

function up546E_jobhunter_widgets() {

	// Sidebar Right
	register_sidebar( array(
			'id' => 'jobhunter_standard_sidebar',
			'name' => __( 'Standard Sidebar', 'jobhunter' ),
			'description' => __( 'This sidebar is located on the right-hand side of each page.', 'foundation' ),
			'before_widget' => '<div class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h5 class="widget_title">',
			'after_title' => '</h5>',
		) );

	}

add_action( 'widgets_init', 'up546E_jobhunter_widgets' );

endif;

/** 
 * Comments Template
 */

if ( ! function_exists( 'up546E_jobhunter_comment' ) ) :

function up546E_jobhunter_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'jobhunter' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'jobhunter' ), '<span>', '</span>' ); ?></p>
	<?php
		break;
		default :
		global $post;
	?>
	
<!-- COMENTS -->
<div class="medium-12 columns">

<?php $special_class = 'row li-parent-'.$comment->comment_parent.' li-comment-'.get_comment_ID(); ?>
<?php $style = $depth > 1 ? 'style="display:none"' : ''; ?>
<div <?php comment_class($special_class); ?> id="comment-<?php comment_ID(); ?>" <?php echo $style ?>>
<div class="meta-bar" data-equalizer>
<?php echo get_avatar( $comment, 50 ); ?>





</div>


<div class="comment-right" data-equalizer-watch style="float:left;">



<div class="meta-header">



<span class="byline"><?php comment_author(); ?></span> <span class="dateline"><?php comment_time(); ?> ago</span>

<?php if(is_array(wpse_58916_user_roles_by_id($comment->user_id))) : ?>
	<div class="user-role">
		<?php // for some reason this isn't work on new installs -- echo array_values(wpse_58916_user_roles_by_id($comment->user_id))[0]; ?>
	</div>
<?php endif; ?>

</div>



			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p><?php _e( 'Your comment is awaiting moderation.', 'jobhunter' ); ?></p>
			<?php endif; ?>
<article >
				<?php comment_text(); ?>
		
</article>

			<div class="right button tiny secondary reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'jobhunter' ), 'after' => ' &darr;', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>	

		</div>
<hr>
</div>
</div>
	
	<?php
		break;
	endswitch;
}
endif;

//We need to override Onclick event to make it work with tinyMCE iframe
if (!function_exists('up546E_jobhunter_comment_reply_link')) :
	function up546E_jobhunter_comment_reply_link($link) {
	    return str_replace('onclick=', 'data-onclick=', $link);
	}
	add_filter('comment_reply_link', 'up546E_jobhunter_comment_reply_link' );
endif;

//Custom JS for comments
if (!function_exists('up546E_jobhunter_wp_head')) :
	function up546E_jobhunter_wp_head() {
		echo '<script type="text/javascript" src="'.get_template_directory_uri().'/js/comment.js"></script>';
	}
	add_action('wp_head', 'up546E_jobhunter_wp_head');
endif;

/**
 * Remove Class from Sticky Post
 */

if ( ! function_exists( 'up546E_jobhunter_remove_sticky' ) ) :

function up546E_jobhunter_remove_sticky($classes) {
  $classes = array_diff($classes, array("sticky"));
  return $classes;
}

add_filter('post_class','up546E_jobhunter_remove_sticky');

endif;

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function up546E_jobhunter_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'jobhunter' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'up546E_jobhunter_wp_title', 10, 2 );

/**
 * Retrieve Shortcodes
 * @see: http://fwp.drewsymo.com/shortcodes/
 */

$foundation_shortcodes = trailingslashit( get_template_directory() ) . 'inc/shortcodes.php';

if (file_exists($foundation_shortcodes)) {
	require( $foundation_shortcodes );
}



// Post Meta Widget
class uppm_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'uppm_widget', 

// Widget name will appear in UI
__('Post Meta Widget', 'uppm_widget_domain'), 

// Widget description
array( 'description' => __( 'Add post meta data in your sidebar', 'uppm_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
// before and after widget arguments are defined by themes
echo $args['before_widget'];
 ?>



<h5>Share</h5>
<h3>

<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="share-item"><i class="fi-social-facebook"></i> </a>

<a href="https://twitter.com/share?url=<?php the_permalink(); ?>" target="_blank" class="share-item"><i class="fi-social-twitter"></i> </a>
 
<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="share-item"><i class="fi-social-google-plus"></i>  </a>
  
</h3>


<?php echo $args['after_widget'];
} 
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'uppm_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'jbf' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class uppm_widget ends here

// Register and load the widget
function up546E_wpb_load_widget() {
	register_widget( 'uppm_widget' );
}
add_action( 'widgets_init', 'up546E_wpb_load_widget' );








/* UserPress oEmbed Custom Meta Box Setup */
add_action( 'load-post.php', 'userpress_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'userpress_post_meta_boxes_setup' );

/* Meta box setup function. */
function userpress_post_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'userpress_add_post_meta_boxes' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'userpress_save_post_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function userpress_add_post_meta_boxes() {

	add_meta_box(
		'featured-oembed',			// Unique ID
		esc_html__( 'oEmbed Featured Media', 'example' ),		// Title
		'userpress_post_meta_box',		// Callback function
		'post',					// Admin page (or post type)
		'side',					// Context
		'high'					// Priority
	);
}


/* Display the post meta box. */
function userpress_post_meta_box( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'userpress_post_nonce' ); ?>

	<p>
		<label for="featured-oembed"><?php _e( "(e.g. YouTube, Flickr, Funny or Die, etc.)", 'example' ); ?></label>
		<br />
		<input class="widefat" type="text" name="featured-oembed" id="featured-oembed" value="<?php echo esc_attr( get_post_meta( $object->ID, 'userpress_oembed', true ) ); ?>" size="30" />
	</p>
<?php }


/* Save the meta box's post metadata. */
function userpress_save_post_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['userpress_post_nonce'] ) || !wp_verify_nonce( $_POST['userpress_post_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( $_POST['featured-oembed'] );

	/* Get the meta key. */
	$meta_key = 'userpress_oembed';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
}


function comment_reform ($arg) {
$arg['title_reply'] = __('Post a reply', 'jbf');
return $arg;
}
add_filter('comment_form_defaults','comment_reform');

function disable_comment_url($fields) { 
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields','disable_comment_url');


function custom_comment_form_defaults($defaults){
	$defaults['comment_notes_before'] = '<p class="comment-notes">' . sprintf( __('Required fields are marked %s' , 'jbf'), '<span class="required">*</span>' ) . '</p>';
	return $defaults;
}
add_filter( 'comment_form_defaults', 'custom_comment_form_defaults' );

// http://wordpress.stackexchange.com/a/36052
function send_comment_email_notification( $comment_ID, $commentdata ) {
    $comment = get_comment( $comment_ID );
    $postid = $comment->comment_post_ID;
    $master_email =  get_comment_author_email( $comment_ID );
    if( isset( $master_email ) && is_email( $master_email ) ) {
        $message = 'New comment on <a href="' . get_permalink( $postid ) . '">' .  get_the_title( $postid ) . '</a>';
        add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
        wp_mail( $master_email, 'New Comment', $message );
    }
}
add_action( 'comment_post', 'send_comment_email_notification', 11, 2 );


// Get the timestamp for the next event.
$timestamp = wp_next_scheduled('prefix_do_this_hourly');
// If this event was created with any special arguments, you need to get those too.
$original_args = array();
wp_unschedule_event( $timestamp, 'prefix_do_this_hourly', $original_args);

/**
 * Run a WP Cron task to trash expired jobs offer
 */

add_action('wp', 'prefix_setup_trashoffers');
function prefix_setup_trashoffers() {
	if (!wp_next_scheduled('trashoffers')) {
		wp_schedule_event(current_time('timestamp'), 'hourly', 'trashoffers');
	}
}

add_action('trashoffers', 'trash_this_offers');
function trash_this_offers() {
	$args = array(
		'post_type'      => 'jobs',
		'post_status'    => 'publish',
		'numberposts' => -1
	);
	$offers = get_posts($args);
	if($offers) {
		$trashed = array();
		foreach($offers as $offer) {
			$temp_meta = get_post_meta($offer->ID);
			$expiration_date = strtotime($temp_meta["expiration"][0]);
			$current_date = time();
			if($current_date > $expiration_date) {
				wp_trash_post($offer->ID);
				$trashed[] = $offer;
			}
		}
		if(!empty($trashed)) {
			$message = 'Current date: '.date('m/d/Y H:i:s', $current_date).'<br/>';
			$message .= 'Jobs trashed:<br/><ul>';
			foreach($trashed as $date => $tr) {
				$temp_meta = get_post_meta($tr->ID);
				$message .= '<li>';
				$message .= 'Offer expiration date: '.$temp_meta["expiration"][0].'<br/>';
				$message .= 'Job ID: '.$tr->ID.'<br/>';
				$message .= 'Job name: '.$tr->post_title.'<br/><br/>';
				$message .= '</li>';
			}
			$message .= '</ul>';
			$headers = array('Content-Type: text/html; charset=UTF-8');
			wp_mail(get_option('admin_email'), 'Some job offers were trashed', $message, $headers);
		}
	}
}


/**
 * Disable the "application" custom post type feed
 */
function ja_disable_cpt_feed( $query ) {
	if ( $query->is_feed() && in_array( 'application', (array) $query->get( 'post_type' ) ) ) {
		die( 'Feed disabled' );
	}
}
add_action( 'pre_get_posts', 'ja_disable_cpt_feed' );





/**
 * Disable  "flag" taxonomy archive
 */
 
 
 add_action(
  'pre_get_posts',
  function($qry) {

    if (is_admin()) return;

    $kill = 'application_flag'; // kill this taxonomy

    $tax_query = $qry->get('tax_query');
    if (empty($tax_query)) return;

    $relation = false;
    if (isset($tax_query['relation'])) {
      $relation = $tax_query['relation'];
      unset($tax_query['relation']);
    }

    foreach ($tax_query as $k => &$tax) {
      if (isset($tax['taxonomy']) && 'application_flag' === $tax['taxonomy']) {
        unset($tax_query[$k]);
      }
    }

    if (1 < count($tax_query)) {
      $tax_query['relation'] = $relation;
    }

    $qry->set('tax_query',$tax_query);

  }
);


// In some/most situations WordPress wont allow mail to be sent without a default server email address
add_filter( 'wp_mail_from', 'my_mail_from' );
function my_mail_from( $email )
{
    if(preg_match('/wordpress/', $email)) {
      //Default address
      return "noreply@example.com";
    } else {
      //Keep header
      return $email;
    }
}

// Alter the Query object for application_flag taxonomy archive page
function custom_application_flag_archive($query) {
    if (($query->is_main_query()) && (is_tax('application_flag'))) {
    	$query->set('meta_key', 'employer-id');
		$query->set('meta_value', get_current_user_id());
    }
}	
add_action('pre_get_posts', 'custom_application_flag_archive');

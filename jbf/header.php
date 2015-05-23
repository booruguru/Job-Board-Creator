<?php 

/**

 * Header

 *

 * Setup the header for our theme

 *

 * @package WordPress

 * @subpackage JobHunter, for WordPress



 */

?>



<!DOCTYPE html>

<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->

<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->



<head>



<meta charset="<?php bloginfo( 'charset' ); ?>" />



<link rel="profile" href="http://gmpg.org/xfn/11" />

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- Set the viewport width to device width for mobile -->

<meta name="viewport" content="width=device-width" />



<title><?php wp_title('|', true, 'right'); ?></title>



<?php 



/* We add some JavaScript to pages with the comment form

 * to support sites with threaded comments (when in use).

 */

if (is_singular() && comments_open() && get_option('thread_comments'))

	wp_enqueue_script('comment-reply');



/*Don't remove this. */



wp_head(); 



?>

  <script type="text/javascript">
  	jQuery(document).ready(function($) {
		$(document).foundation();
	});
  </script>

</head>



<body <?php body_class(); ?>>

<div class="row header" style="margin:auto;">

<nav class="top-bar" data-topbar role="navigation" data-options="mobile_show_parent_link: true">

		<ul class="title-area">
				<li class="name">
				<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
				</li>
				<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
		</ul>

		<section class="top-bar-section">


	

			<!-- Right Nav Section --> 

			

			<ul class="right"> 

				<?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'menu_class' => 'left', 'container' => '', 'fallback_cb' => 'false', 'walker' => new foundation_navigation() ) ); ?>

			

			<li class="job-submit-button"> 
			<a href="<?php $fpid = of_get_option( 'job_form_page' ); echo get_permalink( $fpid ); ?>">  <i class="step fi-page"></i> Post A Job</a>
			</li>

			<?php if ( is_user_logged_in() ) { ?>

			<li class="has-dropdown"> 

			<a href="#">  <i class="step fi-torso"></i> 

			<?php

			global $current_user;

				  get_currentuserinfo();

			

			if ($current_user->display_name !== NULL) {

			echo $current_user->display_name;

			} elseif ($current_user->user_firstname !== NULL) {

			echo $current_user->user_firstname;

			} else  {

			echo $current_user->user_login;

			}

			?>

			<?php if ( class_exists( 'BuddyPress' ) AND bp_is_active( 'messages' ) ) { ?> 

			<span class="unread-message-count">(<?php echo messages_get_unread_count(); ?>)</span>

			<?php } ?>

			</a> 

			

			

			

			<ul class="dropdown" > 

			<?php if (class_exists( 'BuddyPress' ) ) { ?>

			

			<?php if ( bp_is_active( 'messages' ) ) { ?> 

			<li><a href="<?php echo bp_loggedin_user_domain().'messages/'; ?>">Inbox <span class="unread-message-count">(<?php echo messages_get_unread_count(); ?>)</span></a> </li>

			<?php } ?>

			

			<?php if (function_exists( 'up546E_bps_scripts' ) ) { ?>

			<li><a href="<?php echo bp_loggedin_user_domain().'subscriptions/'; ?>">Subscriptions</a> </li>

			<?php } ?>

			

			<?php if ( bp_is_active( 'friends' ) ) { ?>

			<li class="current"><a href="<?php echo bp_loggedin_user_domain().'friends/'; ?>">Friends</a></li>

			<?php } ?>

			

			<?php if ( bp_is_active( 'activity' ) ) { ?>

			<li class="current"><a href="<?php echo bp_loggedin_user_domain().'activity/'; ?>">Activity</a></li>

			<?php } ?>

			

			<?php if ( bp_is_active( 'settings' ) ) { ?>

			<li><a href="<?php echo bp_loggedin_user_domain().'settings/'; ?>">Settings</a> </li>

			<?php } } ?>

			
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>jobs/?action=manage">Postings</a></li>
			<li><a href="<?php $fpid = of_get_option( 'profile_edit_page' ); echo get_permalink( $fpid ); ?>">Profile</a></li>
			<li><a href="<?php echo wp_logout_url( $_SERVER['REQUEST_URI'] ); ?>" title="Logout">Logout</a></li>

			

			</ul>

			

			</li>

			

			

			

			<?php } else  { ?>

			<?php if ( get_option('users_can_register') ) { ?>

			<li><a href="<?php echo wp_registration_url(); ?>">Register</a></li>

			

			<li><a href="<?php echo wp_login_url( get_permalink() ); ?>">Login</a></li>

			

			

			<?php } } ?>



			</ul> 

		</section>                

</nav>
</div>





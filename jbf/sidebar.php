<?php
/**
 * Sidebar
 *
 * Content for our sidebar, provides prompt for logged in users to create widgets
 *
 * @package WordPress
 * @subpackage JobHunter, for WordPress

 */
?>

<!-- Sidebar -->
<aside class="medium-3 columns sidebar">
<?php if ('application' == get_post_type()) { ?> 
<?php the_widget( 'application_flags_widget','title=Flags', 'before_title=<h5>&after_title=</h5>' ); ?> 
<?php } ?>

<?php if ( (is_single()) && ('jobs' == get_post_type()) ) { ?> 


<div class="widget widget_meta options-box">
<?php if ( has_post_thumbnail() ) { ?> 

<div class='text-center'><a href='<?php the_permalink() ?>'><?php the_post_thumbnail( 'featured-image' ); ?></a></div>
<h4 class="text-center"><strong><?php the_terms( get_the_ID(), 'job_company' ); ?></strong></h4>
<?php } ?>
<ul>
<li><?php echo getPostViews(get_the_ID()); ?></li>
<?php $current_user = wp_get_current_user(); $post_author_id = get_post_field( 'post_author', $post_id ); if ($current_user->ID  == $post_author_id) { ?>
<li><a href="<?php $fpid = of_get_option( 'job_form_page' ); echo get_permalink( $fpid ); ?>?job=<?php echo $post->ID; ?>">edit this page</a></li>
<?php } ?>

<li><a href="#" onclick="myFunction()">print this page</li>
<script>
function myFunction() {
    window.print();
}
</script>

</ul>
</div>

<a href="#apply" class="button expand disabled text-center">Apply For Job</a>

<?php } ?>


<?php /* php if (class_exists( 'BuddyPress' ) ) { 


			if ( bp_is_page( BP_GROUPS_SLUG ) ) { 

			bp_directory_groups_search_form();

			} elseif ( bp_is_user_messages() )  { bp_message_search_form();  

			} elseif ( bp_is_directory() )  { bp_directory_members_search_form(); 

			} else { get_search_form();  }
			
		} 
			
else { get_search_form();  }

*/?>

<?php if ( dynamic_sidebar('Standard Sidebar') ) : elseif( current_user_can( 'edit_theme_options' ) ) : ?>

<!-- PLACEHOLDER -->

<?php endif; ?>

</aside>
<!-- End Sidebar -->

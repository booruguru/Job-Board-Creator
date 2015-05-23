<?php get_header(); ?>
<div class="row title-header">
<div class="medium-12 columns">
<h1><?php the_title(); ?></h1>
<p><span class="company-name"><?php the_terms( get_the_ID(), 'job_company' ); ?></span> &middot;
<?php echo get_post_meta($post->ID, "location", $single = true ) ?></p>
</div>
</div>


<div class="row" id="wrapper">
<div class="medium-8 columns" id="main-content">


<p>Date Posted: <?php echo get_the_date(); ?></p>

<!-- Main Content -->
<?php if ( have_posts() ) :

			while ( have_posts() ) : the_post();

			$user = get_user_by( 'id', $post->post_author );


			setPostViews(get_the_ID());

			echo esc_html(get_the_content());

			wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jbf' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );

			$visibility = get_the_author_meta( 'email-visibility', $user->ID ); 

	if ($visibility == 'visible') { ?>
	<p>
		<a href="mailto:<?php echo get_post_meta($post->ID, 'company-email', $single = true ); ?>">
			<?php echo get_post_meta($post->ID, 'company-email', $single = true ); ?>
		</a>
	</p>

<?php } ?>


<?php //must be placed within the loop to fetch post id

 get_template_part( 'jobs/forms/application', 'form' ); ?>

			<?php endwhile; ?>

			
		<?php endif; ?>






	
</div>

<?php get_sidebar(); ?>



</div>





<!-- End Page -->


<?php get_footer(); ?>
<?php get_header(); ?>
<div class="row title-header">
<div class="medium-12 columns">
<h1><?php the_title(); ?> </h1>
</div>
</div>

<div class="row" id="wrapper">
<div class="medium-8 columns" id="main-content">
<p>Published: <?php echo themeblvd_time_ago(); ?> ago</p>

<!-- Main Content -->
<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

<?php get_template_part('featured' , 'media'); ?>

<article><?php the_content(); ?></article>
		<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jbf' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>

			<?php endwhile; ?>

<?php comments_template(); ?>
			
		<?php endif; ?>

	


		

				</div>


<?php get_sidebar(); ?>


		
</div>	






<?php get_footer(); ?>

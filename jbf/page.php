<?php get_header(); ?>


<div class="row title-header">
<div class="medium-12 columns">
<h1><?php the_title(); ?> </h1>
</div>
</div>


<div class="row" id="wrapper">
<div class="medium-8 columns" id="main-content">
<!-- Main Content -->
<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
<?php global $post; ?>


<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'jbf' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>



			<?php endwhile; ?>
			
		<?php endif; ?>






</div>


<!-- SIDEBAR -->

<?php get_sidebar(); ?>
</div>
<!-- End Page -->


<?php get_footer(); ?>



<?php get_header(); ?>
<div class="row title-header">
<div class="medium-12 columns">
<h1>"<?php the_search_query() ?>"</h1>

<h2 class="skinny-heading">Displaying  <?php echo $wp_query->post_count ?> result(s) found </h2>
		
		</div>
</div>


<div class="row" id="wrapper">
<div class="medium-8 columns" id="main-content">





<div class="the-body" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

<?php get_template_part( 'loop', 'list' ); ?>


<?php endwhile; ?>
			
		<?php endif; ?>

		<?php up546E_jobhunter_pagination(); ?>
</div>



</div><!-- END LEFT COLUMN -->

<?php get_sidebar(); ?>


</div><!-- END ROW / WRAPPER -->

<?php get_footer(); ?>



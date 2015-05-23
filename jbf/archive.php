<?php get_header(); ?>


<div class="row title-header">
<div class="medium-12 columns">
		<h1>
					<?php if (is_category()) { ?>
							 <?php single_cat_title(); ?>
					<?php } elseif (is_tag()) { ?> 
							 <?php single_tag_title(); ?>
					<?php } elseif (is_author()) { ?>
							<?php the_author_meta( 'display_name' ); ?>
					<?php } elseif (is_day()) { ?>
							<?php the_time('l, F j, Y'); ?>
					<?php } elseif (is_month()) { ?>
					    	<?php the_time('F Y'); ?>
					<?php } elseif (is_year()) { ?>
					    	<?php the_time('Y'); ?>
					<?php } elseif (is_tax()) { ?>					    	
							<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $term->name; ?>					    	
					<?php } ?>	
		</h1>
<h2 class="skinny-heading">Displaying  <?php echo $wp_query->post_count ?> of <?php echo $wp_query->found_posts ?> result(s) found </h2>
		
		</div>
</div>



<div class="row" id="wrapper">
<div class="medium-9 columns" id="main-content">



		

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

<?php if ( 'jobs' == get_post_type() ) {  

	get_template_part( 'jobs/loop', 'table' ); 
	} else {
	get_template_part( 'loop', 'list' ); 

	}
?>


<?php endwhile; ?>
			
		<?php endif; ?>

		<?php up546E_jobhunter_pagination(); ?>



</div>

<?php get_sidebar(); ?>


</div><!-- END ROW / WRAPPER -->

<?php get_footer(); ?>


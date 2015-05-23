<?php get_header(); ?>
<div class="row" id="wrapper">
<div class="medium-8 columns" id="main-content">

<!-- Main Content -->
<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>



<h2 class="page-title">
<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
 </h2>

<?php echo themeblvd_time_ago(); ?> ago
<br /><br />

<?php get_template_part('featured' , 'media'); ?>

<article><?php the_content(); ?></article>
<br />
<br />
<br />
<br />
			<?php endwhile; ?>

			
		<?php endif; ?>

	


		

				</div>


<?php get_sidebar(); ?>


		
</div>	






<?php get_footer(); ?>

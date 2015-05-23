		

<?php
				if ( have_posts() ): 

				while ( have_posts() ): the_post(); ?>

<!-- TABLE -->
<div class="row  table-row">
<div class="medium-7 columns topic" >
<?php if ( has_post_thumbnail() ) { ?> 

<div class='featured-thumbnail-wrapper'><a href='<?php the_permalink() ?>'><?php the_post_thumbnail( 'featured-thumbnail' ); ?></a></div>

<?php } else { ?>

<div class='thumbnail-placeholder'><a href='<?php the_permalink() ?>' class='fill-div' ></a></div>
<?php } ?>


<div class="job-link"><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a> </div>
<span class="company"><?php the_terms( get_the_ID(), 'job_company' ); ?></span>
 &middot; 
<?php the_terms( get_the_ID(), 'job_type' ); ?>

</div>





<div class="medium-5 columns date text-right" >
<?php echo get_post_meta($post->ID, "location", $single = true ) ?>

<span class="dateline"><a href='<?php the_permalink() ?>'><?php the_time('M d'); ?></a></span>
</div>

</div><!--END TABLE-->




				<?php
				endwhile; 

			$my_search_object->pagination();

			else :

				echo 'Sorry, no posts matched your criteria.';

			endif;
			
			$wp_query = $temp_query;
			wp_reset_query();
		?>


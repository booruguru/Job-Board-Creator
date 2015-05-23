<?php $query1 = new WP_Query( array(
			'post_type' => 'jobs',
    		'post_status' => get_post_stati()
			) ); ?>
<?php if ( $query1->have_posts() ) : ?>

			<?php while ( $query1->have_posts() ) : $query1->the_post(); ?>

<!-- TABLE -->
<div class="row table-row">

<div class="medium-9 columns topic" >
<div class="job-link"><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a> <?php if (comments_open()==0) {?><sup><i class="fi-lock"></i> </sup> <?php } ?> <?php if (is_sticky()) {?><sup><i class="fi-anchor"></i> </sup> <?php } ?></div>
<p style="font-size:12px; margin-top:5px;"><?php the_terms( get_the_ID(), 'job_company' ); ?>
 &middot; <?php echo get_post_meta($post->ID, "city", $single = true ) ?>, <?php echo get_post_meta($post->ID, "state-province", $single = true ) ?>
 </p>
</div>


<div class="medium-2 columns text-right" >
<?php  echo get_post_status(); ?><br />
<span class="pageviews"><?php echo getPostViews(get_the_ID()); ?></span>

</div>

<div class="medium-1 columns text-right" >
<a href="<?php $fpid = of_get_option( 'job_form_page' ); echo get_permalink( $fpid ); ?>?job=<?php echo $post->ID; ?>">Edit</a>
</div>



</div>





<?php endwhile; ?>
			
		<?php endif; ?>


		<?php up546E_jobhunter_pagination(); ?>

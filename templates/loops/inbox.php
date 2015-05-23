
<?php $query1 = new WP_Query( array(
			'post_type' => 'application',
		        'meta_key' => 'employer-id',
        		'meta_value'   => $current_user->ID
			) ); ?>
<?php if ( $query1->have_posts() ) : ?>

			<?php while ( $query1->have_posts() ) : $query1->the_post(); ?>






<!-- TABLE -->
<div class="row  table-row">

<div class="medium-8 columns topic" >
<p class="job-link">
<?php if (get_post_meta($post->ID, 'post_read_unread', true) !== "Read" ) { ?>
<strong><a href="<?php the_permalink(); ?>"><?php echo get_post_meta($post->ID, "app-firstname", $single = true ); ?> <?php echo get_post_meta($post->ID, "app-lastname", $single = true ); ?></a> <span class="read">*</span></strong>

<?php } else { ?>

<a href="<?php the_permalink(); ?>"><?php echo get_post_meta($post->ID, "app-firstname", $single = true ); ?> <?php echo get_post_meta($post->ID, "app-lastname", $single = true ); ?></a>
<?php } ?>
</p>


<a href="<?php echo get_the_permalink(get_post_meta($post->ID, "job-id", $single = true )); ?>"><?php echo get_the_title(get_post_meta($post->ID, "job-id", $single = true )) ?></a>
</div>

<div class="medium-2 columns" >
<?php if (get_the_terms($post->ID, 'application_flag') == true ) {

the_terms($post->ID, 'application_flag'); 

} else {

echo "Pending";

}
?>
</div>

<div class="medium-2 columns date text-right" >
<a href="<?php the_permalink(); ?>"><?php echo themeblvd_time_ago(); ?> ago</a> 
</div>

</div><!--END TABLE-->

			<?php endwhile; ?>

			
		<?php endif; ?>
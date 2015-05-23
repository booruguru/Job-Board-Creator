<?php get_header(); ?>

		<?php 

			//get current taxo term for post
			function get_current_terms($job_id, $taxonomy) {
				$current = array();
				$terms = wp_get_post_terms($job_id, $taxonomy);
		    	if(count($terms) > 0) {
		    		foreach($terms as $term) {
		    			$current[] = $term->term_id;
		    		}
		    	}
		    	return $current;
			}
		
			$args = array();

			$args['wp_query'] = array('post_type' => 'jobs',
			                                'posts_per_page' => 10,
			                                'order' => 'DESC',
			                                'orderby' => 'date');

			$args['fields'][] = array('type' => 'search',
			                                'label' => 'Search',
			                                'value' => '');
	

			$args['fields'][] = array('type' => 'taxonomy',
				                                'label' => 'Status',
				                                'taxonomy' => 'job_type',
				                                'format' => 'multi-select',
				                                'operator' => 'AND');

			$args['fields'][] = array('type' => 'meta_key',
				                                'label' => 'Location',
				                                'meta_key' => 'location',
				                                'format' => 'text',				                                
				                                'compare' => 'LIKE');
				                                
			$args['fields'][] = array('type' => 'orderby',
                            				'label' => 'Order By',
                            				'values' => array('' => '', 'ID' => 'ID', 'title' => 'Title', 'date' => 'Date'),
                            				'format' => 'select');

			$args['fields'][] = array('type' => 'order',
                            				'label' => 'Order',
                            				'values' => array('' => '', 'ASC' => 'ASC', 'DESC' => 'DESC'),
                            				'format' => 'select');

			$args['fields'][] = array('type' => 'submit',
			                                'value' => 'Search');

			$my_search_object = new WP_Advanced_Search($args);

			$temp_query = $wp_query;
			$wp_query = $my_search_object->query();

			//Order by sticky posts
			$regular_posts_array = array();
			$sticky_posts_array = array();
			$cat_style = get_option('item_style');
			foreach($wp_query->posts as $p) {
				$views = get_current_terms($p->ID, 'job_view');
				foreach($views as $view) {
					foreach($cat_style as $id => $style) {
						if($id == $view) {
							if($style == 'sticky') {
								$sticky_posts_array[] = $p;
							} else {
								$ordered_posts_array[] = $p;
							}
						}
					}
				}
			}

			$wp_query->posts = array_merge($sticky_posts_array, $ordered_posts_array);

				?>
				

<div class="row title-header">
<div class="medium-9 columns">
<h1>Job Listings</h1>
<h2 class="skinny-heading"><?php echo 'Displaying ' . $my_search_object->results_range() . ' of ' . $wp_query->found_posts; ?> jobs found </h2>
</div>

<div class="medium-3 columns">

<a href="<?php $fpid = of_get_option( 'job_form_page' ); echo get_permalink( $fpid ); ?>"class="button expand disabled text-center">Post A Job</a>
</div>

</div>


<div class="row" id="wrapper">
<div class="medium-9 columns" id="main-content">

<?php get_template_part('search' , 'bar'); ?>



<?php
				if ( have_posts() ): 

				while ( have_posts() ): the_post(); ?>

<?php
	$views = get_current_terms($post->ID, 'job_view');
	foreach($views as $view) {
		foreach($cat_style as $id => $style) {
			if($id == $view) {
				switch($style) {
					case 'sticky' :
						$class = "stcky";
						break;
					case 'featured' : 
						$class = "featured";
						break;
					default :
						$class = "";
						break;
				}
			}
		}
	}
?>

<!-- TABLE -->
<div <?php post_class($class.' row  table-row'); ?>>
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

	
</div>

<?php get_sidebar(); ?>



</div>





<?php get_footer(); ?>

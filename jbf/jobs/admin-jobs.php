<?php get_header(); ?>

<div class="row title-header">
<div class="medium-12 columns">
<h2 class="page-title">Manage Job Postings</h2>
<?php get_template_part( 'search', 'bar' ); ?>
</div>
</div>

<!-- -->

<div class="row" style="background:#fff; padding-top:10px; margin-top:-20px;">
<div class="medium-12 columns" >


<div style="border:1px solid #eee; padding:0 10px 0 10px; ">
		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

<!-- TABLE -->
<div class="row table-row">

<div class="medium-6 columns topic" >
<div class="job-link"><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a> <?php if (comments_open()==0) {?><sup><i class="fi-lock"></i> </sup> <?php } ?> <?php if (is_sticky()) {?><sup><i class="fi-anchor"></i> </sup> <?php } ?></div>
<p style="font-size:12px; margin-top:5px;">MICROSOFT &middot; Vancouver, British Columbia </p>
</div>

<div class="medium-2 columns category text-right" >
<?php 
$taxonomies = get_object_taxonomies('jobs', 'object');

$customtaxheaders = array(
	'jobs_status' => '');


foreach ($taxonomies as $tax) {
	if ($tax->public == 0) continue;
	$terms = get_the_terms( $post->ID , $tax->name );
	if ( !empty($terms)) { ?>
		<ul>
		<?php
		foreach ( $terms as $term ) {
			$term_link = get_term_link( $term, $tax->name );
			if( is_wp_error( $term_link ) )
			continue;
			echo '<a href="' . $term_link . '">' . $term->name . '</a>';
		} ?>
		</ul>
	<?php } 
	
	
}	
?> .
</div>

<div class="medium-2 columns text-right" >
<a href="#" class="button tiny secondary split">Pending <span data-dropdown="drop"></span></a><br> 
<ul id="drop" class="f-dropdown" data-dropdown-content> 
<li><a href="#">Approve</a></li> 
<li><a href="#">Reject</a></li> 
</ul>
</div>

<div class="medium-1 columns text-right" >
Edit
</div>

<div class="medium-1 columns text-right" >
 <i class="fi-trash"></i> </div>


</div>





<?php endwhile; ?>
			
		<?php endif; ?>
</div>

<p class="text-right"><a href="feed://userhost.net/jobs/feed/"><i class="fi-rss"></i> RSS</a></p>

		<?php up546E_jobhunter_pagination(); ?>

</div>




</div>
<!-- END ROW / WRAPPER -->

<?php get_footer(); ?>


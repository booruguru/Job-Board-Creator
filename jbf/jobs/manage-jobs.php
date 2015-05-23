<?php get_header(); ?>

<div class="row title-header">
<div class="medium-12 columns">
<h1>Manage Listings</h1>
</div>
</div>

<div class="row" id="wrapper">
<div class="medium-9 columns" id="main-content">


<?php get_template_part('jobs/loops/manage' , 'listings'); ?>




	
</div>

<?php get_sidebar(); ?>



</div>





<?php get_footer(); ?>


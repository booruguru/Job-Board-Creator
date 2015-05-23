<form method="GET" action="<?php $lid = of_get_option( 'job_listings_page' ); echo get_permalink( $lid ); ?>">

<div class="row job-search-bar">


<div class="medium-5 columns text-left">
            <input type="text" name="search_query" value="<?php echo $_GET['search_query']; ?>" placeholder="Title, Keywords">

</div>

<div class="medium-5 columns text-left">
            <input type="text" name="meta_location" value="<?php echo $_GET['meta_location']; ?>" placeholder="City, State/Province">
</div>

<div class="medium-2 columns">
<input type="submit" value="Search"> 
</div>

</div>
	

</form>
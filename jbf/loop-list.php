<!-- BEGIN LOOP -->

<div class="page-result row" >


<div class="medium-2 columns">
<span class="time-ago"><?php echo themeblvd_time_ago() ?> ago</span>
</div>

<div class="medium-10 columns">
<?php if ( has_category() ) { ?>
<h6 class="the-category"><?php the_category(', '); ?></h6>
<?php } ?>
<h4 class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
<?php the_tags('<span class="radius secondary label">','</span><span class="radius secondary label">','</span>'); ?>
</div>



</div>

<!-- END LOOP -->
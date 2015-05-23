<?php 

add_action('after_switch_theme', 'my_theme_activation');

function my_theme_activation () {
         

// job_type


wp_insert_term(

        'Full-Time', // the term 

        'job_type', // the taxonomy

        array(

            'slug' => 'full-time',

            ));



wp_insert_term(

        'Part-Time', // the term 

        'job_type', // the taxonomy

        array(

            'slug' => 'part-time',

            ));
            
            
wp_insert_term(

        'Freelance', // the term 

        'job_type', // the taxonomy

        array(

            'slug' => 'freelance',

            ));            


wp_insert_term(

        'Temporary', // the term 

        'job_type', // the taxonomy

        array(

            'slug' => 'temporary',

            ));
            
            
// Views

wp_insert_term(

        'Standard', // the term 

        'job_view', // the taxonomy

        array(

            'slug' => 'standard',

            ));
            
}
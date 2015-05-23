<?php


/*************************
JOB TYPES WIDGET
**************************/

// Creating the widget 
class job_types_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'job_types_widget', 

// Widget name will appear in UI
__('Job Types Widget', 'job_types_widget_domain'), 

// Widget description
array( 'description' => __( 'Display job types (linked to relevant archive pages).', 'job_types_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
$args = array(
    'orderby'           => 'name', 
    'order'             => 'ASC',
    'hide_empty'        => false, 
    'fields'            => 'all', 
    'hierarchical'      => true, 
); 



 $terms = get_terms( 'job_type', $args );
 if ( ! is_wp_error( $terms ) ){
     echo '<ul>';
     foreach ( $terms as $term ) {
       echo "<li><span class='right'>". $term->count ."</span><a href='" . get_term_link( $term ) . "'>" . $term->name . "</a></li>";
        
     }
     echo '</ul></div>';
 }
 

echo $args['after_widget'];
}
        
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Job Types', 'job_types_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'jbf' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
    
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class job_types_widget ends here





/*************************
 APPLICATION FLAGS WIDGET
**************************/



// Creating the widget 
class application_flags_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'application_flags_widget', 

// Widget name will appear in UI
__('Application Flags Widget', 'application_flags_widget_domain'), 

// Widget description
array( 'description' => __( 'Display application "flags" (linked to relevant archive pages).', 'application_flags_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
$fargs = array(
    'orderby'           => 'name', 
    'order'             => 'ASC',
    'hide_empty'        => false, 
    'fields'            => 'all', 
    'hierarchical'      => true, 
); 

 $fterms = get_terms( 'application_flag', $fargs );
 if ( ! is_wp_error( $fterms ) ){
     echo '<ul>';
     foreach ( $fterms as $term ) {

        //Get applications
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'application',
            'application_flag' => $term->slug,
            'meta_key'         => 'employer-id',
            'meta_value'       => get_current_user_id(),
            'post_status'      => 'publish',
            'suppress_filters' => true 
        );
        $apps = get_posts($args);

       echo "<li><span class='right'>". count($apps) ."</span><a href='" . get_term_link( $term ) . "'>" . $term->name . "</a></li>";
        
     }
     echo '</ul>';
 }
 

echo $args['after_widget'];
}
        
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Flags', 'application_flags_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'jbf' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
    
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class application_flags_widget ends here




/*************************
 REGISTER AND LOAD 
 THE WIDGET
**************************/
function wpb_load_widget() {
    register_widget( 'application_flags_widget' );
    register_widget( 'job_types_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
<?php 

if ($_GET['action'] == "manage") { 

get_template_part( 'jobs/manage', 'jobs' ); 


} else {

get_template_part( 'jobs/list', 'jobs' ); 

}
?>

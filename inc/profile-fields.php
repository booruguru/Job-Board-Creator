<?php
// http://wordpress.stackexchange.com/a/4756

// add anything else
function my_new_contactmethods( $contactmethods ) {
    //add Address
    $contactmethods['address'] = 'Address';
    //add City
    $contactmethods['city'] = 'City';
    //add State
    $contactmethods['state'] = 'State';
    //add Postcode
    $contactmethods['postcode'] = 'Postcode';
    //add Phone
    $contactmethods['phone'] = 'Phone';
    //add Mobilphone
    $contactmethods['mphone'] = 'Mobilphone';

    return $contactmethods;
}
add_filter('user_contactmethods','my_new_contactmethods',10,1);





/* Save selected data */
add_action( 'personal_options_update', 'save_user_fields' );
add_action( 'edit_user_profile_update', 'save_user_fields' );

function save_user_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) )
    return false;
// if(isset($_POST['country'])) {
//     update_user_meta( $user_id, 'app-preference', $_POST['country'] );    
// }

if(isset($_POST["email-visibility"]) && $_POST["email-visibility"] != '') {
    update_user_meta( $user_id, 'email-visibility', $_POST['email-visibility'] );
}

if(isset($_POST["app-preference"]) && $_POST["app-preference"] != '') {
    update_user_meta( $user_id, 'app-preference', $_POST['app-preference'] );
}

}

add_action( 'show_user_profile', 'Add_user_fields' );
add_action( 'edit_user_profile', 'Add_user_fields' );

function Add_user_fields( $user ) {

?>

<h3>E-Mail Preferences</h3>
<table class="form-table">       

	

    <tr>
        <th><label for="dropdown"><?php _e('E-Mail Visibility', 'jbf') ?></label></th>
        <td>
        
        <p>Would you like your e-mail address to publicly viewable? (Either way, we will display a contact form for your job postings.)</p>
            <?php 
            //get dropdown saved value
            $checked = get_the_author_meta( 'email-visibility', $user->ID ); 
            ?>
			 					
						<p><label><input type="radio" id="visible" name="email-visibility" value="visible" <?php echo ($checked == "visible")?  'checked' : ''; ?>> Yes</label>

						<label><input type="radio" id="hidden"  name="email-visibility" value="hidden" <?php echo ($checked == "hidden")?  'checked' : ''; ?>> No</label></p>

        </td>
    </tr>




    <tr>
        <th><label for="dropdown"><?php _e('Application Notifications', 'jbf') ?></label></th>
        <td>
            <?php 
            //get dropdown saved value
            $selected = get_the_author_meta( 'app-preference', $user->ID ); 
            ?>
            <select name="app-preference" id="app-preference">
                <option value="individual" <?php echo ($selected == "individual")?  'selected="selected"' : ''; ?>>Individual Emails</option>
                <option value="none" <?php echo ($selected == "none")?  'selected="selected"' : ''; ?>>No Emails</option>
            </select>
            <span class="description">This setting determines how you will be notified when responds to your job posting via contact form.</span>
        </td>
    </tr>
</table>
<?php 
}
?>
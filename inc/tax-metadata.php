<?php

add_action ( 'job_view_edit_form_fields', 'tme_cat_price');
add_action ( 'job_view_add_form_fields', 'tme_cat_price');

function tme_cat_price( $tag ) {

	//check for existing price ID
	$cat_price = get_option('item_price');
	$price_id = '';
	if ( is_array( $cat_price ) && isset($tag->term_id) && array_key_exists( $tag->term_id, $cat_price ) ) {
		$price_id = $cat_price[$tag->term_id];
	}
?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="item_price"><?php _e('Price', 'jbf') ?></label></th>
        <td>
        	$<input type="text" name="item_price" id="item_price" placeholder="0.00" size="8" style="width:150px;" value="<?php echo $price_id; ?>"><br />
            <span class="description">Numbers only.</span> <br/><br/>
        
        </td>
    </tr>
 
<?php

	//check for existing duration
	$cat_duration = get_option('item_duration');
	$duration_id = '7';
	if ( is_array( $cat_duration ) && isset($tag->term_id) && array_key_exists( $tag->term_id, $cat_duration ) ) {
		$duration_id = $cat_duration[$tag->term_id];
	}

	?>

	<tr class="form-field">
        <th scope="row" valign="top"><label for="item_duration"><?php _e('Duration', 'jbf') ?></label></th>
        <td>
        	<input type="text" name="item_duration" id="item_duration" placeholder="0" size="8" style="width:150px;" value="<?php echo $duration_id; ?>"> days<br />
            <span class="description">Round numbers only.</span> <br/><br/>
        
        </td>
    </tr>

	<?php

	//check for existing style
	$cat_style = get_option('item_style');
	if ( is_array( $cat_style ) && isset($tag->term_id) && array_key_exists( $tag->term_id, $cat_style ) ) {
		$style_id = $cat_style[$tag->term_id];
	}
	?>

	<tr class="form-field">
        <th scope="row" valign="top"><label for="item_style"><?php _e('Style', 'jbf') ?></label></th>
        <td>
            <select name="item_style">
            	<?php $selected = $style_id == "standard" ? 'selected="selected"' : ''; ?>
            	<option value="standard" <?php echo $selected ?>><?php _e('Standard', 'jbf') ?></option>
            	<?php $selected = $style_id == "sticky" ? 'selected="selected"' : ''; ?>
            	<option value="sticky" <?php echo $selected ?>><?php _e('Sticky', 'jbf') ?></option>
            	<?php $selected = $style_id == "featured" ? 'selected="selected"' : ''; ?>
            	<option value="featured" <?php echo $selected ?>><?php _e('Featured', 'jbf') ?></option>
            </select>
        </td>
    </tr>

    <?php

}

add_action('create_job_view', 'tme_save_price');
add_action('edited_job_view', 'tme_save_price');

function tme_save_price( $term_id ) {
	if ( isset( $_POST['item_price'] ) ) {

		
		//load existing category price option
		$current_price = get_option( 'item_price' );

		//set price to proper category ID in options array		
		$current_price[$term_id] = round( sanitize_text_field($_POST['item_price'] ), 2);
		
		//save the option array
		update_option( 'item_price', $current_price );
	}

	if ( isset( $_POST['item_duration'] ) ) {
		
		//load existing category duration option
		$current_duration = get_option( 'item_duration' );

		//set duration to proper category ID in options array		
		$current_duration[$term_id] = intval( sanitize_text_field($_POST['item_duration'] ));
		
		//save the option array
		update_option( 'item_duration', $current_duration );
	}

	if ( isset( $_POST['item_style'] ) ) {

		
		//load existing category style option
		$current_style = get_option( 'item_style' );

		//set style to proper category ID in options array		
		$current_style[$term_id] = sanitize_text_field($_POST['item_style']);
		
		//save the option array
		update_option( 'item_style', $current_style );
	}

}
?>
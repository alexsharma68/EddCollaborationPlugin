<div class="wrap">
<h2>Collaboration Settings</h2>
<?php
if (!empty($_POST['submit']) && wp_verify_nonce ( $_POST ['ps_nonce'], plugin_basename ( __FILE__ ) )) {
		update_option('max_upload_size',$_POST['max_upload_size']);
        update_option('allowed_files_type',$_POST['allowed_files_type']);
		 update_option('maxtime_to_accept_invitation',$_POST['maxtime_to_accept_invitation']);
?>
	<div class="updated settings-error" id="setting-error-settings_updated"> 
		<p><strong>Settings saved.</strong></p>
    </div>
<?php } ?>

<form method="post" action="">
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Max upload size</th>
        <td><input type="text" name="max_upload_size" value="<?php echo esc_attr( get_option('max_upload_size') ); ?>" class="regular-text" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Allowed File's type</th>
        <td><input type="text" name="allowed_files_type" value="<?php echo esc_attr( get_option('allowed_files_type') ); ?>" class="regular-text" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Max time to Accept Invitation</th>
        <td><input type="text" name="maxtime_to_accept_invitation" value="<?php echo esc_attr( get_option('maxtime_to_accept_invitation') ); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php wp_nonce_field ( plugin_basename ( __FILE__ ), 'ps_nonce' ); ?>
    <?php submit_button(); ?>

</form>
</div>
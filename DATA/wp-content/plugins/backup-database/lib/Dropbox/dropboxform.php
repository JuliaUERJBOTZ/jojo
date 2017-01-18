<?php
      
      if ( isset( $_POST['submit'] ) ) {

         $options = get_option( 'backup_database_dropbox_settings' );

         $options['enabled'] = $_POST['dropbox-enabled'];

         update_option( 'backup_database_dropbox_settings', $options );
      }
      
      $options = get_option( 'backup_database_dropbox_settings' );

      $auth_url = admin_url( 'admin-ajax.php?action=backup_database_dropbox_start_auth' );

      $unlink_nonce = wp_create_nonce( '_backup_database_unlink_dropbox' );

      $unlink_url = admin_url( 'admin-ajax.php?action=backup_database_unlink_dropbox&nonce='. $unlink_nonce );

?>

<?php if ( isset( $_POST['submit'] ) ): ?>
   <div class="updated message fade"><p><?php _e( 'Settings Saved.', 'backup_database' ) ?></p></div>
<?php endif; ?>
<form method="post" action="<?php admin_url( '?page=backup_database_addons' ) ?>">

	<table class="form-table">

		 <tr>
            <th><?php _e( 'Enabled', 'backup_database' ) ?></th>
            <td>
            	<input <?php checked( $options['enabled'], 'yes' ) ?> type="radio" name="dropbox-enabled" value="yes" /> Yes 
            	<input <?php checked( $options['enabled'], 'no' ) ?> type="radio" name="dropbox-enabled" value="no" /> No
            </td>
         </tr>

         <tr>

         <tr>
         	<th><?php _e( 'Connect', 'backup_database' ) ?></th>
         	<td>
               <?php if ( function_exists( 'curl_version' ) ): ?>
               <?php if ( $options['access_token'] ):  ?>
                  <a class="button action" href="<?php echo $unlink_url ?>"><?php _e( 'Unlink Dropbox', 'backup_database' ) ?></a>
               <?php else: ?>
                  <a class="button action" href="<?php echo $auth_url ?>"><?php _e( 'Connect to Dropbox', 'backup_database' ) ?></a>
               <?php endif; ?>

         		<p class="description"><?php _e( 'Will only work if Backup Database plugin is connected to your Dropbox account.', 'backup_database' ) ?></p>
               <?php else: ?>
               <?php _e( 'PHP extension curl is not installed or not enabled. Please enable it', 'backup_database' ) ?>
               <?php endif; ?>
         	</td>
         </tr>

 <!--        <tr>

            <th><?php _e( 'Account Info', 'backup_database' ) ?></th>
            <td>
               <?php do_action( 'backup_database_dropbox_account_info' ) ?>
            </td>
         </tr>-->

	</table>
   <?php submit_button( 'Save Settings' )  ?>
</form>

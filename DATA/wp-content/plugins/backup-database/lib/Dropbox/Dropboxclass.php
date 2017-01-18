<?php

/**
 * Store backups to the Dropbox
 *
 * @version 1.0
 */
class Wpdb_Dropbox {

	private $key = "3og51oeat9ojrpz";

	private $secret = "cbjp5zzu3d989vu";

	function __construct() {


		if ( ! class_exists( 'B_Dropbox_Wrapper' ) )
			require_once( backup_db_path . 'lib/Dropbox/dropbox.php' );

		$options = array(
				'enabled' => 'no',
				'access_token' => NULL
			);

		if ( ! get_option( 'backup_database_dropbox_settings' ) )
			update_option( 'backup_database_dropbox_settings', $options );

		add_action( 'wp_ajax_backup_database_dropbox_start_auth', array( $this, 'start_auth') );

		add_action( 'wp_ajax_backup_database_connect_dropbox', array( $this, 'finish_auth' ) );

		add_action( 'wp_ajax_backup_database_unlink_dropbox', array( $this, 'unlink_account' ) );

		add_action( 'backupdb_cloud_store', array( $this, 'upload') );

		add_action( 'backup_database_dropbox_account_info', array( $this, 'show_account_info' ) );

	}

	function start_auth() {

		$dropbox = new B_Dropbox_Wrapper( $this->key, $this->secret );

		try {
			$token = $dropbox->oAuthRequestToken();	
		} catch (Exception $e) {
			wp_die( $e->getMessage() );
		}

		$nonce = wp_create_nonce( '_backup_database_connect_dropbox' );

		$redirect_url = admin_url( 'admin-ajax.php?action=backup_database_connect_dropbox&secret='.$token['oauth_token_secret'].'&nonce='. $nonce );
		
		$dropbox->oAuthAuthorize( $token['oauth_token'], $redirect_url );
		
	}

	function finish_auth() {

		if ( ! wp_verify_nonce( $_GET['nonce'], '_backup_database_connect_dropbox' ) )
			wp_die( 'Invalid Nonce' );

		if ( isset( $_GET['not_approved'] ) )
			wp_safe_redirect( admin_url( 'admin.php?page=backup_db&tab=integration' ) );

		$options = get_option( 'backup_database_dropbox_settings' );

		$dropbox = new B_Dropbox_Wrapper( $this->key, $this->secret );

		$dropbox->setOAuthToken( $_GET['oauth_token'] );
		$dropbox->setOAuthTokenSecret( $_GET['secret'] );

		try {

			$token = $dropbox->oAuthAccessToken( $_GET['oauth_token'] );
		
		} catch (Exception $e) {
			
			wp_die( $e->getMessage() );

		}

		
		$options['access_token']['token'] = $token['oauth_token'];
		$options['access_token']['secret'] = $token['oauth_token_secret'];
		$options['access_token']['uid'] = $token['uid'];

		update_option( 'backup_database_dropbox_settings', $options );

		wp_safe_redirect( admin_url( 'admin.php?page=backup_db&tab=integration' ) );

		exit;

	}

	function unlink_account() {


		if ( ! wp_verify_nonce( $_GET['nonce'], '_backup_database_unlink_dropbox' ) )
			wp_die( 'Invalid Nonce' );

		$options = get_option( 'backup_database_dropbox_settings' );

		$options['access_token'] = NULL;

		update_option( 'backup_database_dropbox_settings', $options );

		wp_safe_redirect( admin_url( 'admin.php?page=backup_db&tab=integration' ) );

		exit;

	}

	public function upload( $pageid ) {
			$filepath=get_post_meta( $pageid, 'backup_location',true);

		$options = get_option( 'backup_database_dropbox_settings' );

		if ( $options['enabled'] !== 'yes' )
			return;

		$dropbox = new B_Dropbox_Wrapper( $this->key, $this->secret );

		$dropbox->setOAuthToken( $options['access_token']['token'] );

		$dropbox->setOAuthTokenSecret( $options['access_token']['secret'] );

		$upload_path = date("20y/F/").basename($filepath);

		$response = $dropbox->chunked_upload( $filepath, $upload_path, true, null, null );
		$backupsource=get_post_meta( $pageid, 'backupsource',true);
		$backupsource['DropBox']='DropBox';
   		update_post_meta( $pageid, 'backupsource', $backupsource);

	}

	function download( $id ) {


	}

	function show_account_info() {

		$options = get_option( 'backup_database_dropbox_settings' );

		if ( ! $options['access_token'] )
			return;

		$dropbox = new B_Dropbox_Wrapper( $this->key, $this->secret );

		$dropbox->setOAuthToken( $options['access_token']['token'] );

		$dropbox->setOAuthTokenSecret( $options['access_token']['secret'] );

		if ( ! get_transient( 'backup_database_dropbox_account_info'  ) ) {
			$response = $dropbox->accountInfo();

			set_transient( 'backup_database_dropbox_account_info', $response, 60 * 60 * 5 );
		} else {

			$response = get_transient( 'backup_database_dropbox_account_info'  );

		}

		printf( '<code>Name: <strong>%s</strong> & Email: <strong>%s</strong></code>', $response['display_name'], $response['email'] );

	}

}

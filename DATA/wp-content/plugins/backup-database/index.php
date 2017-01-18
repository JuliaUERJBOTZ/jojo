<?php
/*
Plugin Name:Backup Database
Plugin URI:http://www.wpproking.com
Description:One Click WordPress Database Backup Plugin
Version:3.1
Author:WPProKing 
*/
define('backup_db_path',plugin_dir_path(__FILE__ ));
define('backup_db_url',plugin_dir_url(__FILE__ ));
define( 'backup_downloader', backup_db_url . 'downloader.php' );


if ( !class_exists( "backup_db" ) ) {
	class backup_db {
	
		var $core_table_names = array();
       function __construct() {

			add_action( is_multisite() ? 'network_admin_notices' : 'admin_notices', array( $this, 'admin_notice' ) );
       	global $table_prefix, $wpdb;
       	
	$this->install();
		$possible_names = array(
			'categories',
			'commentmeta',
			'comments',
			'link2cat',
			'linkcategories',
			'links',
			'options',
			'post2cat',
			'postmeta',
			'posts',
			'terms',
			'term_taxonomy',
			'term_relationships',
			'users',
			'usermeta',
		);

		foreach( $possible_names as $name ) {
			if ( isset( $wpdb->{$name} ) ) {
				$this->core_table_names[] = $wpdb->{$name};
			}
		}




	}
		function install() {
			
		if (is_admin()){


		require_once( backup_db_path . 'lib/Dropbox/Dropboxclass.php');
				$dropbox_restore = new Wpdb_Dropbox();
		foreach (glob(backup_db_path . 'admin/*.php') as $filename) { include $filename; }
		}
		foreach (glob(backup_db_path . 'functions/*.php') as $filename) { require_once $filename; }

			
		}

		


 function admin_notice() {
		global $current_user;

		$screen = get_current_screen();
		$user_id = $current_user->ID;

		$closable = 'backup_database' !== $screen->parent_base;

		if ( ( is_multisite() && is_network_admin() ) || ( !is_multisite() && current_user_can( 'manage_options' ) ) ) {

				echo '<div class="updated notice-info my-wp-backup-notice" id="mywpb-notice" style="position:relative;">';
				printf(__('<p>Liked Backup Database? You will definitely love the ​<strong>Pro</strong> version. <a href="http://wpproking.com/pricing" target="_blank" class="notice-button"><strong>Get it now</strong>!</a></p>' . ( $closable ? '<a class="notice-dismiss" href="%1$s"></a>' : '' )), '?mwpb_notice_close=1');
				echo "</div>";

		}
	}


	}



}

$backup_db = new backup_db();





?>

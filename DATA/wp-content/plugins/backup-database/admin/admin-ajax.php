<?php

add_action('wp_ajax_backup_database_delete', 'backup_database_delete');
function backup_database_delete(){
	
	$params = $_POST;
	
	if(empty($params['backupid']))
		return;

	$backup_location = get_post_meta($params['backupid'], 'backup_location', true);	
	@unlink( $backup_location ); 
	wp_delete_post( $params['backupid'] );

	print '1';
	exit;
}

?>

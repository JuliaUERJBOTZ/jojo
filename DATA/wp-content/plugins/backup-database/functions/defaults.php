<?php
	/* get a global option */
	function backup_db_get_option( $option ) {
		$backup_db_default_options = backup_db_default_options();
		$settings = get_option('backup_db');
		switch($option){
		
			default:
				if (isset($settings[$option])){
					return $settings[$option];
				} else {
					return $backup_db_default_options[$option];
				}
				break;
	
		}
	}
	
	
	/* default options */
	function backup_db_default_options(){
		
		$array = array();
		return apply_filters('backup_db_default_options', $array);
		

			
		
	}
?>

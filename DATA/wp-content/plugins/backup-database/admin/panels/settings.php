<p><center><a href="http://www.wpproking.com/pricing/"><img src="<?php echo backup_db_url .'admin/img/30.png' ?>">	</a></center></p>
<h3><?php _e('Create Database Backup', 'backup_db'); ?></h3>
<form class="dbtable" method="post" action="">
<table >
 <div id="content">
<?php

	global $backup_db,$wpdb;
	
	$other_tables = array();
		$also_backup = array();
	
		// Get complete db table list	
		$all_tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
		$all_tables = array_map(create_function('$a', 'return $a[0];'), $all_tables);
		// Get list of WP tables that actually exist in this DB (for 1.6 compat!)

		//error_log(print_r($backup_db->core_table_names,true));
		$wp_backup_default_tables = array_intersect($all_tables, $backup_db->core_table_names);
		// Get list of non-WP tables
		$other_tables = array_diff($all_tables, $wp_backup_default_tables);
		//error_log(print_r($wp_backup_default_tables,true));
                    
		
		foreach ($wp_backup_default_tables as $table) {

		?>
			
 			 <div class="left">
  			  <?php echo $table; ?>
 			 </div>
		<?php 

		}	
		foreach ($other_tables as $table) {

		?>
 		 <div class="right">
     	<input type="checkbox" name="other_tables[]" value="<?php echo $table; ?>" /> <?php echo $table; ?>
  		</div>
		
				
		<?php 

		}?>		

</div>
		

</table>
<div style="clear:both;"></div>

<br>


	<input type="submit" class="dbbutton" name="backupdb" id="submit"  value="<?php _e('Create Backup','backup_db'); ?>"  />
	
</a>





	


</form>

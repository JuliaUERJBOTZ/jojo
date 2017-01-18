<?php 
global $wpdb;
$sqlversion = $wpdb->get_var("SELECT VERSION() AS version");?>

<h1><?php _e('Database Information', 'backup_db'); ?></h1>
	<br style="clear" />
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e('Setting', 'backup_db'); ?></th>
				<th><?php _e('Value', 'backup_db'); ?></th>
			</tr>
		</thead>
		<tr>
			<td><?php _e('Database Host', 'backup_db'); ?></td>
			<td><?php echo DB_HOST; ?></td>
		</tr>
		<tr class="alternate">
			<td><?php _e('Database Name', 'backup_db'); ?></td>
			<td><?php echo DB_NAME; ?></td>
		</tr>
		<tr>
			<td><?php _e('Database User', 'backup_db'); ?></td>
			<td><?php echo DB_USER; ?></td>
		</tr>
		<tr class="alternate">
			<td><?php _e('Database Type', 'backup_db'); ?></td>
			<td>MYSQL</td>
		</tr>
		<tr>
			<td><?php _e('Database Version', 'backup_db'); ?></td>
			<td>v<?php echo $sqlversion; ?></td>
		</tr>
	</table>

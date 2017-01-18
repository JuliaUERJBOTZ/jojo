<?php global $wpdb; 

 if(!function_exists('format_size')) {
	function format_size($rawSize) {
		if($rawSize / 1073741824 > 1)
			return number_format_i18n($rawSize/1048576, 1) . ' '.__('GiB', 'wp-dbmanager');
		else if ($rawSize / 1048576 > 1)
			return number_format_i18n($rawSize/1048576, 1) . ' '.__('MiB', 'wp-dbmanager');
		else if ($rawSize / 1024 > 1)
			return number_format_i18n($rawSize/1024, 1) . ' '.__('KiB', 'wp-dbmanager');
		else
			return number_format_i18n($rawSize, 0) . ' '.__('bytes', 'wp-dbmanager');
	}
}


?>
<h3><?php _e('Tables Information', 'backup_db'); ?></h3>
	<br style="clear" />
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e('No.', 'backup_db'); ?></th>
				<th><?php _e('Tables', 'backup_db'); ?></th>
				<th><?php _e('Records', 'backup_db'); ?></th>
				<th><?php _e('Data Usage', 'backup_db'); ?></th>
				<th><?php _e('Index Usage', 'backup_db'); ?></th>
				<th><?php _e('Overhead', 'backup_db'); ?></th>
			</tr>
		</thead>
		<?php
			$no = 0;
			$row_usage = 0;
			$data_usage = 0;
			$index_usage = 0;
			$overhead_usage = 0;
			$tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
			foreach($tablesstatus as  $tablestatus) {
				if($no%2 == 0) {
					$style = '';
				} else {
					$style = ' class="alternate"';
				}
				$no++;
				echo "<tr$style>\n";
				echo '<td>'.number_format_i18n($no).'</td>'."\n";
				echo "<td>$tablestatus->Name</td>\n";
				echo '<td>'.number_format_i18n($tablestatus->Rows).'</td>'."\n";
				echo '<td>'.format_size($tablestatus->Data_length).'</td>'."\n";
				echo '<td>'.format_size($tablestatus->Index_length).'</td>'."\n";;
				echo '<td>'.format_size($tablestatus->Data_free).'</td>'."\n";
				$row_usage += $tablestatus->Rows;
				$data_usage += $tablestatus->Data_length;
				$index_usage +=  $tablestatus->Index_length;
				$overhead_usage += $tablestatus->Data_free;
				echo '</tr>'."\n";
			}
			echo '<tr class="thead">'."\n";
			echo '<th>'.__('Total:', 'backup_db').'</th>'."\n";
			echo '<th>'.sprintf(_n('%s Table', '%s Tables', $no, 'backup_db'), number_format_i18n($no)).'</th>'."\n";
			echo '<th>'.sprintf(_n('%s Record', '%s Records', $row_usage, 'backup_db'), number_format_i18n($row_usage)).'</th>'."\n";
			echo '<th>'.format_size($data_usage).'</th>'."\n";
			echo '<th>'.format_size($index_usage).'</th>'."\n";
			echo '<th>'.format_size($overhead_usage).'</th>'."\n";
			echo '</tr>';
		?>
	</table>

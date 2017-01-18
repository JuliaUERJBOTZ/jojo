<p><center><a href="http://www.wpproking.com/pricing/"><img src="<?php echo backup_db_url .'admin/img/30.png' ?>">	</a></center></p>
<div class="wrapper">

			
												  	
						<table class="widefat">
							<thead>
							    <tr>
							        <th>ID</th>
							        <th>Date</th> 
							        <th>Type</th>
							        <th>Status</th>       
							        <th>Size</th>
							    </tr>
							</thead>
							<tfoot>
							    <tr>
							    	<th>ID</th>
							    	<th>Date</th>
							    	<th>Type</th>
							    	<th>Status</th>
							    	<th>Size</th>
							    </tr>
							</tfoot>
							<tbody>
								<?php



					$args = array( 'post_type' => 'backup-database', 'posts_per_page' => 100 );
									$loop = new WP_Query( $args );
									if($loop->have_posts()): while ( $loop->have_posts() ) : $loop->the_post();
									$backup_status =  get_post_meta( $loop->post->ID, 'backup_status', true);
								?>

								<tr id="backup-db-<?= $loop->post->ID; ?>">
							     <td><?php print $loop->post->ID; ?></td>
							     <td><?php the_time('F jS, Y  @ H: i: s'); ?>
							     	<b></b>

							     
							     	<div class="row-actions">
							     		<span class="download"><a class="download-backup" title="Download this backup to your local computer" href="<?= backup_downloader . '?download=' . $loop->post->ID; ?>">Download</a> | </span>
							     		<span class="delete"><a class="delete-backup" onclick="database_backup_remove(<?php echo $loop->post->ID;?> , this);" id="delete-backup" href="#" title="Delete the backup from the the server" data-id="<?= $loop->post->ID; ?>">Remove</a> </span>
							     		
							     	</div>
									
							     </td>
							     <td><?php print get_post_meta($loop->post->ID, 'backup_type', true); ?></td>
							    <td><b><?php print get_post_meta($loop->post->ID, 'backup_status', true); ?></b></td>
							     <td><b><?php print get_post_meta($loop->post->ID, 'backup_size', true); ?></b></td>
							    </tr>
								<?php endwhile; else: ?>
								<tr id="no-backups-found">
									<td id="no-backups-found"> There are no backups found. </td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<?php endif; ?>
							</tbody>
							</table>

							<br>
							<div class="doing-backup" style="margin-bottom: 20px; line-height: 30px; height: 30px; position: relative;">
								<span class="spinner" style="width: 40px; height: 40px; display: inline; position: relative; top: 3px;"></span>
							</div>
							
					</div>

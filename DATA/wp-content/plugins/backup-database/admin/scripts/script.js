
function database_backup_remove(backupID,e)
{
	//e.preventDefault();
		
		var hook = jQuery('#backup-db-'+backupID);
		var str = 'action=backup_database_delete&backupid='+backupID;
		if(confirm('Are you sure that you want to DELETE this backup?')){
		
		jQuery(hook).animate({
					opacity: 0.5
				});
		
		jQuery.ajax({
		url: ajaxurl,
		data: str,
		type: 'POST',
		success:function(data){
			jQuery(hook).fadeOut(500, function(){
			jQuery(this).remove();
			});
		},
		error:function(data){alert(data);
			alert(data.error);
		}		
	});



		}

	
}

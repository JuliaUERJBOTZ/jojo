<?php

class backup_db_admin {

	var $options;

	function __construct() {
	
		/* Plugin slug and version */
		$this->slug = 'backup_db';
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$this->plugin_data = get_plugin_data(backup_db_path . 'index.php', false, false);
		
		
		/* Priority actions */
		add_action('admin_menu', array(&$this, 'add_menu'), 9);
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 9);
		
		add_action('admin_init', array(&$this, 'admin_init'), 9);
		
	}
	

 public function get_file_size($file, $type, $ID){
       switch($type){
          case "KB":
            $filesize = filesize($file) * .0009765625; // bytes to KB
          	break;
          case "MB":
            $filesize = (filesize($file) * .0009765625) * .0009765625; // bytes to MB
          	break;
          case "GB":
            $filesize = ((filesize($file) * .0009765625) * .0009765625) * .0009765625; // bytes to GB
          	break;
       }
       if($filesize <= 0){
	
       	update_post_meta( $ID, 'backup_status', 'Failed');
        return $filesize = 'Unknown';
       }else{

        update_post_meta( $ID, 'backup_status', 'Completed');
        return round($filesize, 2).' '.$type;
        }
    }

function backup_db(){


 global $backup_db,$wpdb;
	 $return='';
	$other_tables='';	
        $WPDBFileName=date('M-d-Y-Gis').'-bak';       
        $SQLfilename=$WPDBFileName.'.sql';
	$filename=$WPDBFileName.'.zip';	
     $path_info = wp_upload_dir();
	$page['post_type']    = 'backup-database';
		$page['post_content'] = 'backup-database - ' .  date('M-d-Y-Gis').'-bak';
		$page['post_parent']  = 0;
		$page['post_author']  = get_current_user_id();
		$page['post_status']  = 'private';
		$page['post_title']   = 'backup-database - ' . date('M-d-Y-Gis').'-bak';
		$pageid = wp_insert_post ($page);
		if ($pageid != 0) {
			add_post_meta( $pageid, 'backup_status', 'In Progress');
			add_post_meta( $pageid, 'backup_size', '---');
			add_post_meta( $pageid, 'backup_location', $path_info['basedir'].'/backup-database/'.$filename);
			add_post_meta( $pageid, 'backup_type', 'Database');
		}
	 	    
	


  
	$all_tables = $wpdb->get_col('SHOW TABLES');
	
	$wp_backup_default_tables = array_intersect($all_tables, $backup_db->core_table_names);
	wp_mkdir_p($path_info['basedir'].'/backup-database');
         if(isset($_POST['other_tables']))
	{
	$othertable=$_POST['other_tables'];
	$tables =array_merge($wp_backup_default_tables,$othertable);
	}
	else
	$tables=$wp_backup_default_tables;



		
	foreach($tables as $table)
	{


		  $result = $wpdb->get_results("SELECT * FROM {$table}", ARRAY_N);
                $row2 = $wpdb->get_row('SHOW CREATE TABLE ' . $table, ARRAY_N);
			$return.= 'DROP TABLE '.$table.';';
                $return .= "\n\n" . $row2[1] . ";\n\n";
                for ($i = 0; $i < count($result); $i++) {
                    $row = $result[$i];
                    $return .= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < count($result[0]); $j++) {
                        $row[$j] = $wpdb->_real_escape($row[$j]);
                        $return .= (isset($row[$j])) ? '"' . $row[$j] . '"' : '""';
                        if ($j < (count($result[0]) - 1)) {
                            $return .= ',';
                        }
                    }
                    $return .= ");\n";
                }
                $return .= "\n";
            

		
	}
	
	//save file
	$handle = fopen($path_info['basedir'] .'/backup-database/'.$SQLfilename,'w+');
	fwrite($handle,$return);
	fclose($handle);
	
		$upload_path = array(
		'filename' => ($filename),
		'dir' => ($path_info['basedir'].'/backup-database/'.$SQLfilename),
		'url' => ($path_info['baseurl'].'/backup-database/'.$SQLfilename),
		'size' => 0
	);
		
	
			 require_once( backup_db_path. 'lib/class-pclzip.php' );	
           	 $arcname = $path_info['basedir'].'/backup-database/'.$filename;
		  $archive = new PclZip($arcname);
		  $v_dir = $path_info['basedir'].'/backup-database/'.$SQLfilename;

     
		 $v_list = $archive->add($v_dir, PCLZIP_OPT_REMOVE_PATH, $path_info['basedir'].'/backup-database/');
			@unlink($v_dir);
		$backup_size = $this->get_file_size( $path_info['basedir'].'/backup-database/'.$filename, 'MB',$pageid );
		update_post_meta( $pageid, 'backup_size', $backup_size);
                
  
		echo '<div class="updated fade"><p><strong>'.sprintf(__('Download Database Backup file <a href="%s">here</a>.','userpro'),$path_info['baseurl'] .'/backup-database/'.$filename).'</strong></p></div>';

	do_action("backupdb_cloud_store",$pageid);
	
}


	function admin_init() {
	
		$this->tabs = array(
			'settings' => __('Create Backup','backup_db'),
			'manage' => __('Manage Backup','backup_db'),
			'integration' => __('Store Backup','backup_db'),
			'dbinfo' => __('DB Info','backup_db'),
			'tableinfo' => __('Table Info','backup_db'),	
			'pro' => __('ProFeature','backup_db'),				
			'help' => __('Help','backup_db'),
			
			
						
		);
		$this->default_tab = 'settings';
		
		$this->options = get_option('backup_db');
		if (!get_option('backup_db')) {
			update_option('backup_db', backup_db_default_options() );
		}
		
	}
	
	
	

	function add_styles(){
	
		wp_register_style('backup_db_admin', backup_db_url.'admin/css/admin.css');
		wp_enqueue_style('backup_db_admin');
		wp_enqueue_script('jquery');
		wp_enqueue_script('backup_db_script', backup_db_url.'admin/scripts/script.js');
		
		
		
		
	}
	
	function add_menu() {
	
		
		add_management_page( __('Backup Database','backup_db'),  __('Backup Database','backup_db'), 'manage_options', $this->slug, array(&$this, 'admin_page'), '');
		
		
	
	}

	function admin_tabs( $current = null ) {
			$tabs = $this->tabs;
			$links = array();
			if ( isset ( $_GET['tab'] ) ) {
				$current = $_GET['tab'];
			} else {
				$current = $this->default_tab;
			}
			foreach( $tabs as $tab => $name ) :
				if ( $tab == $current ) :
					$links[] = "<a class='nav-tab nav-tab-active' href='?page=".$this->slug."&tab=$tab'>$name</a>";
				else :
					$links[] = "<a class='nav-tab' href='?page=".$this->slug."&tab=$tab'>$name</a>";
				endif;
			endforeach;
			foreach ( $links as $link )
				echo $link;
	}

	function get_tab_content() {
		$screen = get_current_screen();
		if( strstr($screen->id, $this->slug ) ) {
			if ( isset ( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			} else {
				$tab = $this->default_tab;
			}
			require_once backup_db_path.'admin/panels/'.$tab.'.php';
		}
	}
	
	
	
	function save() {
	
		foreach($_POST as $key => $value) {
	
			if ($key != 'submit') {
				if (!is_array($_POST[$key])) {
				
					$this->options[$key] = stripslashes( esc_attr($_POST[$key]) );
				} else {
					
				
					$this->options[$key] = $_POST[$key];
					
				}
			}
		}
		
		update_option('backup_db', $this->options);
		
	
	}

	
	
	
	
	function admin_page() {
	
		
		
		if (isset($_POST['backupdb']) ) {
			
			$this->backup_db();
		}
		
		
		
		
	?>
	
		<div class="wrap <?php echo $this->slug; ?>-admin">
		
			<h2 class="nav-tab-wrapper"><?php $this->admin_tabs(); ?></h2>

			<div class="<?php echo $this->slug; ?>-admin-contain">
				
				<?php $this->get_tab_content(); ?>
				
				<div class="clear"></div>
				
			</div>
			
		</div>

	<?php }

}
$backup_db_admin = new backup_db_admin();
?>

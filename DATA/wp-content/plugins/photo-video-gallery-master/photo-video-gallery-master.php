<?php
/**
 * Plugin Name: Photo Video Gallery Master
 * Version: 1.4.2
 * Description: Display your WordPress content like Photo, Video, Link, Image etc in Gallery format With CSS3 Hover Animation & Display With Lightbox.
 * Author: WebHunt Infotech
 * Author URI: http://www.webhuntinfotech.com/
 * Plugin URI: http://demo.webhuntinfotech.com/demo/#pvgmp
 */

/**
 * Constant Variable
 */
define("PVGM_PLUGIN_URL", plugin_dir_url(__FILE__));

add_action('plugins_loaded', 'PVGM_GetReadyTranslation');
function PVGM_GetReadyTranslation() {
	load_plugin_textdomain('PVGM_TEXT_DOMAIN', FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

/**
* Crop Images In Desire Format
*/
add_image_size( 'PVGM_gallery_admin_thumb', 300, 300, array( 'top', 'center' ) );
add_image_size( 'PVGM_gallery_admin_circle', 400, 400, array( 'top', 'center' ) );
add_image_size( 'PVGM_gallery_admin_medium', 400,9999,array( 'top', 'center' ) );

// Function To Remove Feature Image
function PVGM_remove_image_box() {
	remove_meta_box('postimagediv','pvgm_gallery','side');
}
add_action('do_meta_boxes', 'PVGM_remove_image_box');

/**
* Short Code Detach Function To UpLoad JS And CSS
*/
function PVGM_ShortCodeDetect() {
    global $wp_query;
    $Posts = $wp_query->posts;
    $Pattern = get_shortcode_regex();

		/** js scripts */
            wp_enqueue_script('jquery');

		/**
         * Load Light Box Swipebox JS CSS
        */
			wp_enqueue_style('PVGM-swipe-css', PVGM_PLUGIN_URL.'lightbox/swipebox/swipebox.css');
			wp_enqueue_script('PVGM-swipe-js', PVGM_PLUGIN_URL.'lightbox/swipebox/jquery.swipebox.min.js', array('jquery'));

		/**   css scripts  */
           wp_enqueue_style('PVGM-boot-strap-css', PVGM_PLUGIN_URL.'css/bootstrap.css');

}
add_action( 'wp_enqueue_scripts', 'PVGM_ShortCodeDetect' );
add_filter( 'widget_text', 'do_shortcode' );

class PVGM {
    private static $instance;
    private $admin_thumbnail_size = 150;
    private $thumbnail_size_w = 150;
    private $thumbnail_size_h = 150;
	var $counter;

    public static function forge() {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

	private function __construct() {
		$this->counter = 0;
        add_action('admin_print_scripts-post.php', array(&$this, 'pvgm_admin_print_scripts'));
        add_action('admin_print_scripts-post-new.php', array(&$this, 'pvgm_admin_print_scripts'));
        add_image_size('pvgm_gallery_admin_thumb', $this->admin_thumbnail_size, $this->admin_thumbnail_size, true);
        add_image_size('pvgm_gallery_thumb', $this->thumbnail_size_w, $this->thumbnail_size_h, true);
        add_shortcode('pvgmgallery', array(&$this, 'shortcode'));
        if (is_admin()) {
			add_action('init', array(&$this, 'PhotoGalleryPluginPro'), 1);
			add_action('add_meta_boxes', array(&$this, 'add_all_pvgm_meta_boxes'));
			add_action('admin_init', array(&$this, 'add_all_pvgm_meta_boxes'), 1);

			add_action('save_post', array(&$this, 'PVGM_image_meta_box_save'), 9, 1);
			add_action('save_post', array(&$this, 'PVGM_settings_meta_save'), 9, 1);

			add_action('wp_ajax_PVGMgallery_get_thumbnail', array(&$this, 'ajax_get_thumbnail'));
		}
    }

	//Required JS & CSS
	public function pvgm_admin_print_scripts() {
		if ( 'pvgm_gallery' == $GLOBALS['post_type'] ) {
			wp_enqueue_script('media-upload');
			wp_enqueue_script('media-uploader-js', PVGM_PLUGIN_URL . 'js/multiple-media-uploader.js', array('jquery'));

			wp_enqueue_media();
			//custom add image box css
			wp_enqueue_style('meta-css', PVGM_PLUGIN_URL.'css/meta.css');

			wp_enqueue_style('smart-forms.css', PVGM_PLUGIN_URL.'css/smart-forms.css');

			//font awesome css
			wp_enqueue_style('pvgm-font-awesome', PVGM_PLUGIN_URL.'css/font-awesome.min.css');
		}
	}

	// Register Custom Post Type
	public function PhotoGalleryPluginPro() {
		$labels = array(
			'name' => __('Photo Video Gallery Master','PVGM_TEXT_DOMAIN' ),
			'singular_name' => __('Photo Video Gallery Master','PVGM_TEXT_DOMAIN' ),
			'add_new' => __('Add New Gallery', 'PVGM_TEXT_DOMAIN' ),
			'add_new_item' => __('Add New Gallery', 'PVGM_TEXT_DOMAIN' ),
			'edit_item' => __('Edit Gallery', 'PVGM_TEXT_DOMAIN' ),
			'new_item' => __('New Gallery', 'PVGM_TEXT_DOMAIN' ),
			'view_item' => __('View Gallery', 'PVGM_TEXT_DOMAIN' ),
			'search_items' => __('Search Gallery', 'PVGM_TEXT_DOMAIN' ),
			'not_found' => __('No Gallery found', 'PVGM_TEXT_DOMAIN' ),
			'not_found_in_trash' => __('No Gallery found in Trash', 'PVGM_TEXT_DOMAIN' ),
			'parent_item_colon' => __('Parent Gallery:', 'PVGM_TEXT_DOMAIN' ),
			'all_items' => __('All Galleries', 'PVGM_TEXT_DOMAIN' ),
			'menu_name' => __('Photo Video Gallery Master', 'PVGM_TEXT_DOMAIN' ),
		);

		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'supports' => array( 'title','thumbnail'),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 10,
			'menu_icon' => 'dashicons-format-gallery',
			'show_in_nav_menus' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => false,
			'capability_type' => 'post'
		);

        register_post_type( 'pvgm_gallery', $args );
        add_filter( 'manage_edit-pvgm_gallery_columns', array(&$this, 'pvgm_gallery_columns' )) ;
        add_action( 'manage_pvgm_gallery_posts_custom_column', array(&$this, 'pvgm_gallery_manage_columns' ), 10, 2 );
	}

	function pvgm_gallery_columns( $columns ){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Portfolio Galleries','PVGM_TEXT_DOMAIN' ),
            'shortcode' => __( 'Galleries Shortcode','PVGM_TEXT_DOMAIN' ),
            'date' => __( 'Date','PVGM_TEXT_DOMAIN' )
        );
        return $columns;
    }

    function pvgm_gallery_manage_columns( $column, $post_id ){
        global $post;
        switch( $column ) {
          case 'shortcode' :
            echo '<input type="text" value="[PVGM id='.$post_id.']" readonly="readonly" />';
            break;
          default :
            break;
        }
    }

	public function add_all_pvgm_meta_boxes() {
		add_meta_box( __('Add Images', 'PVGM_TEXT_DOMAIN'), __('Add Images', 'PVGM_TEXT_DOMAIN'), array(&$this, 'PVGM_generate_add_image_meta_box_function'), 'pvgm_gallery', 'normal', 'low' );
		add_meta_box( __('Apply Setting On Gallery', 'PVGM_TEXT_DOMAIN'), __('Apply Setting On Gallery', 'PVGM_TEXT_DOMAIN'), array(&$this, 'PVGM_settings_meta_box_function'), 'pvgm_gallery', 'normal', 'low');
		add_meta_box ( __('Gallery Shortcode', 'PVGM_TEXT_DOMAIN'), __('Gallery Shortcode', 'PVGM_TEXT_DOMAIN'), array(&$this, 'PVGM_shotcode_meta_box_function'), 'pvgm_gallery', 'side', 'low');

		// Rate Us Meta Box
		add_meta_box(__('We need your reviews in order to improve our services', 'PVGM_TEXT_DOMAIN') , __('We need your reviews in order to improve our services', 'PVGM_TEXT_DOMAIN'), array(&$this,'Rate_us_meta_box_pvgm'), 'pvgm_gallery', 'side', 'low');

		// Pro Features Meta Box
		add_meta_box(__('PRO Features List', 'PVGM_TEXT_DOMAIN') , __('PRO Features List', 'PVGM_TEXT_DOMAIN'), array(&$this,'pvgm_pro_features'), 'pvgm_gallery', 'side', 'low');
	}

	// Rate Us Meta Box Function
	function Rate_us_meta_box_pvgm() { ?>
		<style>
			div.stars {
			  margin-top:10px;
			  text :center;
			  display: inline-block;
			}

			input.star { display: none; }

			label.star {
			  float: right;
			  padding: 8px;
			  font-size: 36px;
			  color: #DF7514;
			  transition: all .2s;
			}

			input.star:checked ~ label.star:before {
			  content: '\f005';
			  color: #FD4;
			  transition: all .25s;
			}

			input.star-5:checked ~ label.star:before {
			  color: #FE7;
			  text-shadow: 0 0 20px #952;
			}

			input.star-1:checked ~ label.star:before { color: #F62; }

			label.star:hover { transform: rotate(-15deg) scale(1.3); }

			label.star:before {
			  content: '\f006';
			  font-family: FontAwesome;
			}
		</style>
		<script>
		jQuery(function() {
			jQuery("input[name$='star']").change(function() {
				window.open('https://wordpress.org/plugins/photo-video-gallery-master/', '_blank');
			});
		});
		</script>
		<div class="stars">
			<input class="star star-5" id="star-5" type="radio" name="star"/>
			<label class="star star-5" for="star-5"></label>
			<input class="star star-4" id="star-4" type="radio" name="star"/>
			<label class="star star-4" for="star-4"></label>
			<input class="star star-3" id="star-3" type="radio" name="star"/>
			<label class="star star-3" for="star-3"></label>
			<input class="star star-2" id="star-2" type="radio" name="star"/>
			<label class="star star-2" for="star-2"></label>
			<input class="star star-1" id="star-1" type="radio" name="star"/>
			<label class="star star-1" for="star-1"></label>
		</div>
		<div class="" style="text-align:center;margin-bottom:15px;margin-top:25px;">
			<a href="https://wordpress.org/plugins/photo-video-gallery-master/" target="_blank" class="btn-web button-3"><?php _e('RATE US','PVGM_TEXT_DOMAIN'); ?></a>
		</div>
		<?php
	}

	function pvgm_pro_features(){
	?>
		<ul style="">
			<li class="plan-feature">(1) <?php _e('100% Responsive Design.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(2) <?php _e('10 Beautiful Transition Effects.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(3) <?php _e('Rectangular/Circular Image Design Layout.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(4) <?php _e('Isotope/Masonry Effects.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(5) <?php _e('Two, Three and Four Column Gallery Layout.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(6) <?php _e('Unlimited Hover Color.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(7) <?php _e('Youtube/Vimeo Video.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(8) <?php _e('Extrenal Link.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(9) <?php _e('Multiple Image uploader.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(10) <?php _e('Drag and Drop image Position.','PVGM_TEXT_DOMAIN'); ?></li>
			
			<li class="plan-feature">(11) <?php _e('All Gallery Shortcode.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(12) <?php _e('Each Gallery has Unique Shortcode.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(13) <?php _e('Shortcode Button on post or page.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(14) <?php _e('2 Types Of Lightbox.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(15) <?php _e('Hover Color Opacity.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(16) <?php _e('Hide/Show gallery Title and label.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(17) <?php _e('Hide/Show gallery Discription and View More Button.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(18) <?php _e('Google Fonts.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(19) <?php _e('500+ of Font Style.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(20) <?php _e('Manage Font Size.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(21) <?php _e('Custom CSS Option.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(22) <?php _e('Translation Ready.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(23) <?php _e('Updates and Support.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(24) <?php _e('Extensive Documentation.','PVGM_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(25) <?php _e('And many more..','PVGM_TEXT_DOMAIN'); ?></li>
		</ul>
	<?php
	}

	/**
	 * This function display Add New Image interface
	 * Also loads all saved gallery photos into gallery
	 */
    public function PVGM_generate_add_image_meta_box_function($post) { ?>
			<div class="" style="padding:20px;text-align: center;">
			  <a  href="http://demo.webhuntinfotech.com/documentation/photo-video-gallery-master-pro/" target="_blank" class="btn-web button-1"><?php _e('Documention','PVGM_TEXT_DOMAIN'); ?></a>
			  <a href="http://demo.webhuntinfotech.com/demo/#pvgmp" target="_blank" class="btn-web button-2"><?php _e('View Live Demo (PRO)','PVGM_TEXT_DOMAIN'); ?></a>
			  <a href="http://webhuntinfotech.com/amember/signup/pvgmp" target="_blank" class="btn-web button-3"><?php _e('Upgrade to PRO','PVGM_TEXT_DOMAIN'); ?></a>
			</div>
		<div >
			<div class="pvgm-tips-div">
				<p><strong><?php _e('Tips','PVGM_TEXT_DOMAIN'); ?>:</strong> <?php _e('Upload all gallery images using Add New Image button. Do not use/add pre-uploaded images which are uploaded previously using Media/Post/Page. Minimum Dimensions for Upload Image is 400*400.','PVGM_TEXT_DOMAIN'); ?></p>
			</div>
			<input id="PVGM_delete_all_button" class="button" type="button" value="Delete All" rel="">
			<input type="hidden" id="PVGM_wl_action" name="PVGM_wl_action" value="PVGM-save-settings">

			<!--Add New Image Button-->
			<div class="pvgm-image-entry add_pvgm_new_image" id="pvgm_gallery_upload_button" data-uploader_title="Upload Image" data-uploader_button_text="Select" style="margin:10px;">
				<div class="dashicons dashicons-plus"></div>
				<p>
					<?php _e('Add New Media', 'PVGM_TEXT_DOMAIN'); ?>
				</p>
			</div>

            <ul id="pvgm_gallery_thumbs" class="clearfix">
				<?php
				/* Load saved photos */
				$WPGP_AllPhotosDetails = unserialize(get_post_meta( $post->ID, 'PVGM_all_photos_details', true));
				$TotalImages =  get_post_meta( $post->ID, 'PVGM_total_images_count', true );
				if($TotalImages) {
					foreach($WPGP_AllPhotosDetails as $WPGP_SinglePhotoDetails) {
						$name = $WPGP_SinglePhotoDetails['PVGM_image_label'];
						$UniqueString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
						$url = $WPGP_SinglePhotoDetails['PVGM_image_url'];
						$url1 = $WPGP_SinglePhotoDetails['PVGM_gallery_admin_thumb'];
						$url2 = $WPGP_SinglePhotoDetails['PVGM_gallery_admin_medium'];
						$circle = $WPGP_SinglePhotoDetails['PVGM_gallery_admin_circle'];
						$video = $WPGP_SinglePhotoDetails['PVGM_video_link'];
						$link = $WPGP_SinglePhotoDetails['PVGM_external_link'];
						$type = $WPGP_SinglePhotoDetails['PVGM_portfolio_type'];
						$description = $WPGP_SinglePhotoDetails['PVGM_image_descp'];
						?>
						<li class="pvgm-image-entry" id="pvgm_img">
							<a class="image_gallery_remove pvgmgallery_remove" href="#gallery_remove" id="pvgm_remove_bt" ><img src="<?php echo  esc_url(PVGM_PLUGIN_URL.'images/image-close-icon.png'); ?>" /></a>
							<div class="pvgm-admin-inner-div1" >

								<img src="<?php echo esc_url($url1); ?>" class="pvgm-meta-image" alt=""  style="">
								<input type="hidden" id="unique_string[]" name="unique_string[]" value="<?php echo esc_attr($UniqueString); ?>" />

							<p>
								<label><?php _e('Display Type','PVGM_TEXT_DOMAIN')?></label>
								<select name="PVGM_portfolio_type[]" id="PVGM_portfolio_type[]" style="width:100%; margin-top:5px;">
									<optgroup label="Select Type">
										<option value="image" <?php if($type == 'image') echo "selected=selected"; ?>><i class="fa fa-image"></i> <?php _e('Image','PVGM_TEXT_DOMAIN')?></option>
										<option value="video" <?php if($type == 'video') echo "selected=selected"; ?>><i class="fa fa-youtube-play"></i> <?php _e('Video','PVGM_TEXT_DOMAIN')?></option>
										<option value="link" <?php if($type == 'link') echo "selected=selected"; ?>><i class="fa fa-link"></i> <?php _e('Link','PVGM_TEXT_DOMAIN')?></option>
									</optgroup>
								</select>
							</p>
							</div>
							<div class="pvgm-admin-inner-div2" >

								<input type="text" id="PVGM_image_url[]" name="PVGM_image_url[]" class="pvgm_label_text"  value="<?php echo esc_url($url); ?>"  readonly="readonly" style="display:none;" />
								<input type="text" id="PVGM_gallery_admin_thumb[]" name="PVGM_gallery_admin_thumb[]" class="pvgm_label_text"  value="<?php echo esc_url($url1); ?>"  readonly="readonly" style="display:none;" />
								<input type="text" id="PVGM_gallery_admin_medium[]" name="PVGM_gallery_admin_medium[]" class="pvgm_label_text"  value="<?php echo esc_url($url2); ?>"  readonly="readonly" style="display:none;" />
								<input type="text" id="PVGM_gallery_admin_circle[]" name="PVGM_gallery_admin_circle[]" class="pvgm_label_text"  value="<?php echo esc_url($circle); ?>"  readonly="readonly" style="display:none;" />

								<p>
									<div class="pvgm_label"><label ><?php _e('Label','PVGM_TEXT_DOMAIN')?></label></div>
									<input type="text" id="PVGM_image_label[]" name="PVGM_image_label[]" value="<?php echo esc_attr($name); ?>" placeholder="Enter Label Here" class="pvgm_label_text">
								</p>
								<p>
									<label class="pvgm_label"><?php _e('Video URL','PVGM_TEXT_DOMAIN')?></label>
									<input type="text" id="PVGM_video_link[]" name="PVGM_video_link[]" value="<?php echo esc_url($video); ?>" placeholder="Enter Youtube/Vimeo Video URL" class="pvgm_label_text">
								</p>
								<p>
									<label class="pvgm_label"><?php _e('Link','PVGM_TEXT_DOMAIN')?></label>
									<input type="text" id="PVGM_external_link[]" name="PVGM_external_link[]" value="<?php echo esc_url($link); ?>" placeholder="Enter Link URL" class="pvgm_label_text">
								</p>
								<p>
									<label class="pvgm_label" style="vertical-align:top;"><?php _e('Description','PVGM_TEXT_DOMAIN')?></label>
									<textarea id="PVGM_image_descp[]" name="PVGM_image_descp[]" class="pvgm_textarea" placeholder="Enter Image Description"><?php echo $description; ?></textarea>
								</p>
							</div>
						</li>
						<?php
					} // end of foreach
				} else {
					$TotalImages = 0;
				}
				?>
            </ul>
        </div>

		<div style="clear:left;"></div>
        <?php
    }

	/**
	 * Gallery settings metabox funtion
	 */
    public function PVGM_settings_meta_box_function($post) {

		require_once('gallery-setting-metabox.php');
	}

	public function PVGM_shotcode_meta_box_function() { ?>
		<p><?php _e("Use below shortcode in any Page/Post to publish your gallery", 'PVGM_TEXT_DOMAIN');?></p>
		<input readonly="readonly" type="text" value="<?php echo "[PVGM id=".get_the_ID()."]"; ?>">
		<?php
	}


	public function admin_thumb($id) {
		$image  = wp_get_attachment_image_src($id, 'PVGM_gallery_admin_original', true);
		$image1 = wp_get_attachment_image_src($id, 'PVGM_gallery_admin_thumb', true);
		$image2 = wp_get_attachment_image_src($id, 'PVGM_gallery_admin_medium', true);
		$circle = wp_get_attachment_image_src($id, 'PVGM_gallery_admin_circle', true);
		$UniqueString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        ?>
		<li class="pvgm-image-entry" id="pvgm_img">
			<a class="image_gallery_remove pvgmgallery_remove" href="#gallery_remove" id="pvgm_remove_bt" ><img src="<?php echo  esc_url(PVGM_PLUGIN_URL.'images/image-close-icon.png'); ?>" /></a>
			<div class="pvgm-admin-inner-div1" >

				<img src="<?php echo esc_url($image1[0]); ?>" class="pvgm-meta-image" alt=""  style="">
				<label><?php _e('Display Type','PVGM_TEXT_DOMAIN')?></label>
				<select name="PVGM_portfolio_type[]" id="PVGM_portfolio_type[]" style="width:100%;">
					<optgroup label="Select Type">
						<option value="image" selected="selected"><i class="fa fa-image"></i> <?php _e('Image','PVGM_TEXT_DOMAIN')?></option>
						<option value="video"><i class="fa fa-youtube-play"></i> <?php _e('Video','PVGM_TEXT_DOMAIN')?></option>
						<option value="link"><i class="fa fa-link"></i> <?php _e('Link','PVGM_TEXT_DOMAIN')?></option>
					</optgroup>
				</select>
				</div>
			<div class="pvgm-admin-inner-div2" >
				<input type="text" id="PVGM_image_url[]" name="PVGM_image_url[]" class="pvgm_label_text"  value="<?php echo esc_url($image[0]); ?>"  readonly="readonly" style="display:none;" />
				<input type="text" id="PVGM_gallery_admin_thumb[]" name="PVGM_gallery_admin_thumb[]" class="pvgm_label_text"  value="<?php echo esc_url($image1[0]); ?>"  readonly="readonly" style="display:none;" />
				<input type="text" id="PVGM_gallery_admin_medium[]" name="PVGM_gallery_admin_medium[]" class="pvgm_label_text"  value="<?php echo esc_url($image2[0]); ?>"  readonly="readonly" style="display:none;" />
				<input type="text" id="PVGM_gallery_admin_circle[]" name="PVGM_gallery_admin_circle[]" class="pvgm_label_text"  value="<?php echo esc_url($circle[0]); ?>"  readonly="readonly" style="display:none;" />
				<p>
					<label class="pvgm_label"><?php _e('Label','PVGM_TEXT_DOMAIN')?></label>
					<input type="text" id="PVGM_image_label[]" name="PVGM_image_label[]" placeholder="Enter Label Here" class="pvgm_label_text">
				</p>
				<p>
					<label class="pvgm_label"><?php _e('Video URL','PVGM_TEXT_DOMAIN')?> </label>
					<input type="text" id="PVGM_video_link[]" name="PVGM_video_link[]" placeholder="Enter Youtube/Vimeo Video URL" class="pvgm_label_text">
				</p>
				<p>
					<label class="pvgm_label"><?php _e('Link','PVGM_TEXT_DOMAIN')?></label>
					<input type="text" id="PVGM_external_link[]" name="PVGM_external_link[]" placeholder="Enter Link URL" class="pvgm_label_text">
				</p>

				<p>
					<label class="pvgm_label" style="vertical-align:top;"><?php _e('Description','PVGM_TEXT_DOMAIN')?></label>
					<textarea id="PVGM_image_descp[]" name="PVGM_image_descp[]" class="pvgm_textarea" placeholder="Enter Image Description"></textarea>
				</p>
			</div>
		</li>
        <?php
    }

    public function ajax_get_thumbnail() {
        echo $this->admin_thumb($_POST['imageid']);
        die;
    }

    public function PVGM_image_meta_box_save($PostID) {
		if(isset($PostID) && isset($_POST['PVGM_wl_action'])) {
			if(isset($_POST['PVGM_image_url'])){
				$TotalImages = count($_POST['PVGM_image_url']);
				$ImagesArray = array();
				if($TotalImages) {
					
					for($i=0; $i < $TotalImages; $i++) {
						$image_label = stripslashes($_POST['PVGM_image_label'][$i]);
						$url = $_POST['PVGM_image_url'][$i];
						$url1 = $_POST['PVGM_gallery_admin_thumb'][$i];
						$url2 = $_POST['PVGM_gallery_admin_medium'][$i];
						$circle = $_POST['PVGM_gallery_admin_circle'][$i];
						$video = $_POST['PVGM_video_link'][$i];
						$link = $_POST['PVGM_external_link'][$i];
						$type = $_POST['PVGM_portfolio_type'][$i];
						$description = $_POST['PVGM_image_descp'][$i];
						$ImagesArray[] = array(
							'PVGM_image_label' => $image_label,
							'PVGM_image_url' => esc_url_raw( $url ),
							'PVGM_gallery_admin_thumb' => esc_url_raw( $url1 ),
							'PVGM_gallery_admin_medium' => esc_url_raw( $url2 ),
							'PVGM_gallery_admin_circle' => esc_url_raw( $circle ),
							'PVGM_video_link' => esc_url_raw( $video ),
							'PVGM_external_link' => esc_url_raw( $link ),
							'PVGM_portfolio_type' => sanitize_text_field( $type ),
							'PVGM_image_descp' => stripslashes(esc_attr($description))
						);
					}
					
					update_post_meta($PostID, 'PVGM_all_photos_details', serialize($ImagesArray));
					update_post_meta($PostID, 'PVGM_total_images_count', $TotalImages);
				}

			}else {
				$TotalImages = 0;
				update_post_meta($PostID, 'PVGM_total_images_count', $TotalImages);
				$ImagesArray = array();
				update_post_meta($PostID, 'PVGM_all_photos_details', serialize($ImagesArray));
			}
		}
    }

	//save settings meta box values
	public function PVGM_settings_meta_save($PostID) {
	  if(isset($PostID) && isset($_POST['PVGM_Show_Gallery_Title'])){
		$PVGM_Effect  				= sanitize_text_field( $_POST['PVGM_Effect'] );
		$PVGM_Color 				= sanitize_text_field( $_POST['PVGM_Color'] );
		$PVGM_Label_Bg_Color 		= sanitize_text_field( $_POST['PVGM_Label_Bg_Color'] );
		$PVGM_Label_Text_Color 		= sanitize_text_field( $_POST['PVGM_Label_Text_Color'] );
		$PVGM_Descp_Text_Color 		= sanitize_text_field( $_POST['PVGM_Descp_Text_Color'] );
		$PVGM_Show_Gallery_Title    = sanitize_text_field( $_POST['PVGM_Show_Gallery_Title'] );
		$PVGM_Show_Image_Label      = sanitize_text_field( $_POST['PVGM_Show_Image_Label'] );
		$PVGM_Show_Image_Descp      = sanitize_text_field( $_POST['PVGM_Show_Image_Descp'] );
		$PVGM_Show_Image_Button	   	= sanitize_text_field( $_POST['PVGM_Show_Image_Button'] );
		$PVGM_Button_Text	   		= stripslashes(esc_attr($_POST['PVGM_Button_Text']));
		$PVGM_Gallery_Layout        = sanitize_text_field( $_POST['PVGM_Gallery_Layout'] );
		$PVGM_Open_Link		        = sanitize_text_field( $_POST['PVGM_Open_Link'] );
		$PVGM_Font_Style           	= sanitize_text_field( $_POST['PVGM_Font_Style'] );
		$PVGM_Lable_Font_Size      	= sanitize_text_field( $_POST['PVGM_Lable_Font_Size']);
		$PVGM_Desc_Font_Size      	= sanitize_text_field($_POST['PVGM_Desc_Font_Size'] );
		$PVGM_Light_Box           	= sanitize_text_field( $_POST['PVGM_Light_Box'] );
		$PVGM_Image_Border          = sanitize_text_field( $_POST['PVGM_Image_Border'] );
		$PVGM_Custom_CSS    		= wp_filter_nohtml_kses( $_POST['PVGM_Custom_CSS'] );
		$PVGM_Settings_Array = serialize( array(
			'PVGM_Effect'          		    => $PVGM_Effect,
			'PVGM_Color'         		    => $PVGM_Color,
			'PVGM_Label_Bg_Color'       	=> $PVGM_Label_Bg_Color,
			'PVGM_Label_Text_Color'     	=> $PVGM_Label_Text_Color,
			'PVGM_Descp_Text_Color'     	=> $PVGM_Descp_Text_Color,
			'PVGM_Show_Gallery_Title'		=> $PVGM_Show_Gallery_Title,
			'PVGM_Show_Image_Label'    		=> $PVGM_Show_Image_Label,
			'PVGM_Show_Image_Descp'     	=> $PVGM_Show_Image_Descp,
			'PVGM_Show_Image_Button'     	=> $PVGM_Show_Image_Button,
			'PVGM_Button_Text'     			=> $PVGM_Button_Text,
			'PVGM_Gallery_Layout'      		=> $PVGM_Gallery_Layout,
			'PVGM_Open_Link'      			=> $PVGM_Open_Link,
			'PVGM_Font_Style'				=> $PVGM_Font_Style,
			'PVGM_Lable_Font_Size'			=> $PVGM_Lable_Font_Size,
			'PVGM_Desc_Font_Size'			=> $PVGM_Desc_Font_Size,
			'PVGM_Light_Box'          		=> $PVGM_Light_Box,
			'PVGM_Image_Border'         	=> $PVGM_Image_Border,
			'PVGM_Custom_CSS'   			=> $PVGM_Custom_CSS
		) );

		$PVGM_Gallery_Settings = "PVGM_Gallery_Settings_".$PostID;
		update_post_meta($PostID, $PVGM_Gallery_Settings, $PVGM_Settings_Array);
	  }
	}
}

global $PVGM;
$PVGM = PVGM::forge();

/**
 * Simple Gallery Short Code [PVGM].
 */
require_once("gallery-short-code.php");

add_action('media_buttons_context', 'pvgm_add_pvgm_custom_button');
add_action('admin_footer', 'pvgm_add_pvgm_inline_popup_content');


function pvgm_add_pvgm_custom_button($context) {
  $img = plugins_url( '/images/gallery.png' , __FILE__ );
  $container_id = 'PVGM';
  $title =  __('Select Gallery to insert into post','PVGM_TEXT_DOMAIN') ;
  $context = '<a class="button button-primary thickbox"  title="'. __("Select Gallery to insert into post",'PVGM_TEXT_DOMAIN').'"
  href="#TB_inline?width=400&inlineId='.$container_id.'">
		<span class="wp-media-buttons-icon" style="background: url('.esc_url( $img ).'); background-repeat: no-repeat; background-position: left bottom;"></span>
	'. __("Get Gallery Shortcode",'PVGM_TEXT_DOMAIN').'
	</a>';
  return $context;
}

function pvgm_add_pvgm_inline_popup_content() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#pvgm_galleryinsert').on('click', function() {
			var id = jQuery('#pvgm-gallery-select option:selected').val();
			window.send_to_editor('<p>[PVGM id=' + id + ']</p>');
			tb_remove();
		})
	});
	</script>

	<div id="PVGM" style="display:none;">
	  <h3><?php _e('Select Any Gallery to Insert Into Post or Page','PVGM_TEXT_DOMAIN');?></h3>
	  <?php
		$all_posts = wp_count_posts( 'pvgm_gallery')->publish;
		$args = array('post_type' => 'pvgm_gallery', 'posts_per_page' =>$all_posts);
		global $pvgm_galleries;
		$pvgm_galleries = new WP_Query( $args );
		if( $pvgm_galleries->have_posts() ) { ?>
			<select id="pvgm-gallery-select">
				<?php
				while ( $pvgm_galleries->have_posts() ) : $pvgm_galleries->the_post(); ?>
				<option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
				<?php
				endwhile;
				?>
			</select>
			<button class='button primary' id='pvgm_galleryinsert'><?php _e('Insert Gallery Shortcode','PVGM_TEXT_DOMAIN');?></button>
			<?php
		} else {
			_e('No Gallery found','PVGM_TEXT_DOMAIN');
		}
		?>
	</div>
	<?php
}

function pvgm_get_gallery_value($PostId){
	$PVGM_Default_Options = array(
		'PVGM_Effect'  				=> 'fifth',
		'PVGM_Color' 				=> '#31A3DD',
		'PVGM_Label_Bg_Color' 		=> '#000000',
		'PVGM_Label_Text_Color' 	=> '#ffffff',
		'PVGM_Descp_Text_Color' 	=> '#ffffff',
		'PVGM_Show_Gallery_Title'   => 'yes',
		'PVGM_Show_Image_Label'     => 'yes',
		'PVGM_Show_Image_Descp'     => 'yes',
		'PVGM_Show_Image_Button'	=> 'yes',
		'PVGM_Button_Text'			=> '',
		'PVGM_Gallery_Layout'       => 'col-md-6',
		'PVGM_Open_Link'        	=> '_blank',
		'PVGM_Font_Style'           => 'Arial',
		'PVGM_Lable_Font_Size'		=> '18',
		'PVGM_Desc_Font_Size'		=> '14',
		'PVGM_Light_Box'           	=> 'lightbox',
		'PVGM_Image_Border'         => 'no',
		'PVGM_Custom_CSS'			=> ''
	);
	
	$PVGM_Settings = "PVGM_Gallery_Settings_".$PostId;
	$PVGM_Settings = unserialize(get_post_meta( $PostId, $PVGM_Settings, true));
	
	$PVGM_Settings = wp_parse_args($PVGM_Settings , $PVGM_Default_Options);
	
	return $PVGM_Settings;
}
?>
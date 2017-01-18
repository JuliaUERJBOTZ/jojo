<?php
/**
 * Plugin Name: Ultimate Gallery Master
 * Version: 1.2.3
 * Description: Ultimate Gallery Master is indeed the ultimate responsive multimedia Gallery with an insane set of features that allows to create unlimited Image, Video (YouTube and Vimeo) and link galleries with unlimited possibilities. It is the most complate and advance gallery plugin runs on all major browsers with support for older browsers like IE8 and mobile devices like iPhone, iPad, IOS, Android or Windows mobile.
 * Author: WebHunt Infotech
 * Author URI: http://www.webhuntinfotech.com/
 * Plugin URI: http://demo.webhuntinfotech.com/demo/#ugmp
 */

/** Constant Variable */
define("UGML_TEXT_DOMAIN","UGML_TEXT_DOMAIN" );
define("UGML_PLUGIN_URL", plugin_dir_url(__FILE__));

/** Translation Ready */
add_action('plugins_loaded', 'UGML_GetReadyTranslation');
function UGML_GetReadyTranslation() {
	load_plugin_textdomain('UGML_TEXT_DOMAIN', FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

/** Crop Images In Desire Format */
add_image_size( 'UGML_gallery_admin_thumb', 300, 300, array( 'top', 'center' ) );
add_image_size( 'UGML_gallery_admin_circle', 400, 400, array( 'top', 'center' ) );

// Function To Remove Feature Image
function UGML_remove_image_box() {
	remove_meta_box('postimagediv','ugml_cpt','side');
}
add_action('do_meta_boxes', 'UGML_remove_image_box');

/** Short Code Detect Function To UpLoad JS And CSS */
function UGML_ShortCodeDetect() {
    global $wp_query;
    $Posts = $wp_query->posts;
    $Pattern = get_shortcode_regex();

		/** js scripts */
            wp_enqueue_script('jquery');

		/**   css scripts  */
           wp_enqueue_style('UGML-global-css', UGML_PLUGIN_URL.'css/global.css');
		
		/** js */
            wp_enqueue_script( 'UGML-FWDUGP-js', UGML_PLUGIN_URL.'js/FWDUGP.js');		
}
add_action( 'wp_enqueue_scripts', 'UGML_ShortCodeDetect' );
add_filter( 'widget_text', 'do_shortcode' );

// Register Custom Post Type
add_action('init', 'ugml_gallery_cpt');
function ugml_gallery_cpt() {
    register_post_type('ugml_cpt',
        array(
            'labels' => array(
                'name' => __('Ultimate Gallery Master','UGML_TEXT_DOMAIN' ),
				'singular_name' => __('Ultimate Gallery Master','UGML_TEXT_DOMAIN' ),
				'add_new' => __('Add New Gallery', 'UGML_TEXT_DOMAIN' ),
				'add_new_item' => __('Add New Gallery', 'UGML_TEXT_DOMAIN' ),
				'edit_item' => __('Edit Gallery', 'UGML_TEXT_DOMAIN' ),
				'new_item' => __('New Gallery', 'UGML_TEXT_DOMAIN' ),
				'view_item' => __('View Gallery', 'UGML_TEXT_DOMAIN' ),
				'search_items' => __('Search Gallery', 'UGML_TEXT_DOMAIN' ),
				'not_found' => __('No Gallery found', 'UGML_TEXT_DOMAIN' ),
				'not_found_in_trash' => __('No Gallery found in Trash', 'UGML_TEXT_DOMAIN' ),
				'parent_item_colon' => __('Parent Gallery:', 'UGML_TEXT_DOMAIN' ),
				'all_items' => __('All Galleries', 'UGML_TEXT_DOMAIN' ),
				'menu_name' => __('Ultimate Gallery Master', 'UGML_TEXT_DOMAIN' ),
            ),
            'supports' => array('title', 'thumbnail'),
            'show_in' => true,
            'show_in_nav_menus' => false,
            'public' => true,
            'menu_icon' => 'dashicons-format-gallery',
        )
    );
}
	
class UGML {
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
        add_action('admin_print_scripts-post.php', array(&$this, 'ugml_admin_print_scripts'));
        add_action('admin_print_scripts-post-new.php', array(&$this, 'ugml_admin_print_scripts'));
        add_image_size('ugml_gallery_admin_thumb', $this->admin_thumbnail_size, $this->admin_thumbnail_size, true);
        add_image_size('ugml_gallery_thumb', $this->thumbnail_size_w, $this->thumbnail_size_h, true);
        add_shortcode('ugmlgallery', array(&$this, 'shortcode'));
        
        if (is_admin()) {
			add_action('add_meta_boxes', array(&$this, 'add_all_ugml_meta_boxes'));
			add_action('admin_init', array(&$this, 'add_all_ugml_meta_boxes'), 1);

			add_action('save_post', array(&$this, 'UGML_image_meta_box_save'), 9, 1);
			add_action('save_post', array(&$this, 'UGML_settings_meta_save'), 9, 1);
			
			add_action('wp_ajax_ugmlgallery_get_thumbnail', array(&$this, 'ajax_get_thumbnail'));
		}
    }
	
	//Required JS & CSS
	public function ugml_admin_print_scripts() {
		if ( 'ugml_cpt' == $GLOBALS['post_type'] ) {
			wp_enqueue_script('media-upload');
			wp_enqueue_script('media-uploader-js', UGML_PLUGIN_URL . 'js/multiple-media-uploader.js', array('jquery'));

			wp_enqueue_media();
			//custom add image box css
			wp_enqueue_style('image-box-css', UGML_PLUGIN_URL.'css/image-box.css');
			wp_enqueue_style('smart-forms-css', UGML_PLUGIN_URL.'css/smart-forms.css');

			wp_enqueue_script('jquery-ui-slider');

			//font awesome css
			wp_enqueue_style('ugml-font-awesome', UGML_PLUGIN_URL.'css/font-awesome.min.css');
		}
	}

	public function add_all_ugml_meta_boxes() {
		add_meta_box( __('Add Images', 'UGML_TEXT_DOMAIN'), __('Add Images', 'UGML_TEXT_DOMAIN'), array(&$this, 'UGML_generate_add_image_meta_box_function'), 'ugml_cpt', 'normal', 'low' );
		add_meta_box( __('Apply Setting On Gallery', 'UGML_TEXT_DOMAIN'), __('Apply Setting On Gallery', 'UGML_TEXT_DOMAIN'), array(&$this, 'UGML_settings_meta_box_function'), 'ugml_cpt', 'normal', 'low');
		add_meta_box ( __('Gallery Shortcode', 'UGML_TEXT_DOMAIN'), __('Gallery Shortcode', 'UGML_TEXT_DOMAIN'), array(&$this, 'UGML_shotcode_meta_box_function'), 'ugml_cpt', 'side', 'low');
		
		// Rate Us Meta Box
		add_meta_box(__('We need your reviews in order to improve our services', 'UGML_TEXT_DOMAIN') , __('We need your reviews in order to improve our services', 'UGML_TEXT_DOMAIN'), array(&$this,'Rate_us_meta_box_ugml'), 'ugml_cpt', 'side', 'low');

		// Pro Features Meta Box
		add_meta_box(__('Pro Feature List', 'UGML_TEXT_DOMAIN') , __('Pro Features List', 'UGML_TEXT_DOMAIN'), array(&$this,'ugml_pro_features'), 'ugml_cpt', 'side', 'low');
	}
	
	// Rate Us Meta Box Function
	function Rate_us_meta_box_ugml() { ?>
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
				window.open('https://wordpress.org/plugins/ultimate-gallery-master/', '_blank');
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
			<a href="https://wordpress.org/plugins/ultimate-gallery-master/" target="_blank" class="btn-web button-3"><?php _e('RATE US','UGML_TEXT_DOMAIN'); ?></a>
		</div>
		<?php
	}

	function ugml_pro_features(){
	?>
		<ul style="">
			<li class="plan-feature">(1) <?php _e('100% Responsive Design.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(2) <?php _e('Four grid layouts (dynamic, masonry, classic and infinite).','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(3) <?php _e('Vertical and horizontal variation.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(4) <?php _e('Full multimedia support (image, self hosted video, youtube, youku, vimeo, audio, flash, iframe, google maps, external link).','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(5) <?php _e('More than 150 Presets (thumbnail display styles) included.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(6) <?php _e('Multiple shortcode on page or post.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(7) <?php _e('Unique settings for each gallery.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(8) <?php _e('Runs on all major browsers.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(9) <?php _e('Super easy to use for beginners.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(10) <?php _e('All-purpose usage.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(11) <?php _e('Quick and easy setup.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(12) <?php _e('SEO optimized.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(13) <?php _e('Mobile optimized.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(14) <?php _e('Single or multiple categories selection.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(15) <?php _e('Filterable categories.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(16) <?php _e('Optional menu.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(17) <?php _e('Two menu types list or dropbox.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(18) <?php _e('Customizable menu position with variation based on layout.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(19) <?php _e('Optional search box.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(20) <?php _e('Optional lazy loading with load more button or scroll.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(21) <?php _e('Multiple thumbnails hide / show animation types.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(22) <?php _e('Thumbnails multimedia icons.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(23) <?php _e('Adjustable thumbnails number to display / load per set.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(24) <?php _e('Adjustable thumbnail spacings / size and much more.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(25) <?php _e('Adjustable thumbnail geometry and styling.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(26) <?php _e('Light Box View.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(27) <?php _e('Two lightbox screen (classic and modern).','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(28) <?php _e('Unlimited color scheme.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(29) <?php _e('Social networks sharing.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(30) <?php _e('500+ Google Fonts.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(31) <?php _e('Custom CSS Option.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(32) <?php _e('Developer friendly.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(33) <?php _e('Translation ready.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(34) <?php _e('Updates and support.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(35) <?php _e('Extensive documentation.','UGML_TEXT_DOMAIN'); ?></li>
			<li class="plan-feature">(36) <?php _e('And many more..','UGML_TEXT_DOMAIN'); ?></li>
		</ul>
	<?php
	}
	

	/**
	 * This function display Add New Image interface
	 * Also loads all saved gallery photos into gallery
	 */
    public function UGML_generate_add_image_meta_box_function($post) { ?>
		
			<div class="" style="padding:20px;text-align: center;">  
			  <a href="http://demo.webhuntinfotech.com/demo/#ugmp" target="_blank" class="btn-web button-1"><?php _e('View Live Demo (PRO)','UGML_TEXT_DOMAIN'); ?></a>
			  <a href="http://webhuntinfotech.com/amember/signup/ugmp/" target="_blank" class="btn-web button-2"><?php _e('Upgrade To PRO','UGML_TEXT_DOMAIN'); ?></a>
			  <a  href="http://demo.webhuntinfotech.com/documentation/ultimate-gallery-master-pro/" target="_blank" class="btn-web button-3"><?php _e('Documention','UGML_TEXT_DOMAIN'); ?></a>
			</div>
		<div >
			<div class="ugml-tips-div">
				<p><strong><?php _e('Tips','UGML_TEXT_DOMAIN'); ?>:</strong> <?php _e('Upload all gallery images using Add New Image button. Do not use/add pre-uploaded images which are uploaded previously using Media/Post/Page. Minimum Dimensions for Upload Image is 400*400.','UGML_TEXT_DOMAIN'); ?></p>
			</div>
			<input id="UGML_delete_all_button" class="button" type="button" value="Delete All" rel="">
			<input type="hidden" id="UGML_wl_action" name="UGML_wl_action" value="UGML-save-settings">
            <ul id="ugml_gallery_thumbs" class="clearfix">
				<?php
				/* load saved photos into gallery */
				$WPGP_AllPhotosDetails = unserialize(get_post_meta( $post->ID, 'UGML_all_photos_details', true));
				$TotalImages =  get_post_meta( $post->ID, 'UGML_total_images_count', true );
				if($TotalImages) {
					foreach($WPGP_AllPhotosDetails as $WPGP_SinglePhotoDetails) {
						$name = $WPGP_SinglePhotoDetails['UGML_image_label'];
						$UniqueString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
						$url = $WPGP_SinglePhotoDetails['UGML_image_url'];
						$url1 = $WPGP_SinglePhotoDetails['UGML_gallery_admin_thumb'];
						$circle = $WPGP_SinglePhotoDetails['UGML_gallery_admin_circle'];
						$type = $WPGP_SinglePhotoDetails['UGML_display_type'];
						$description = $WPGP_SinglePhotoDetails['UGML_image_descp'];
						$link = $WPGP_SinglePhotoDetails['UGML_external_link'];
						$extra_button_url = $WPGP_SinglePhotoDetails['UGML_extra_button_url'];
						?>
						<li class="ugml-image-entry" id="ugml_img">
							<a class="image_gallery_remove ugmlgallery_remove" href="#gallery_remove" id="ugml_remove_bt" ><img src="<?php echo  esc_url(UGML_PLUGIN_URL.'images/image-close-icon.png'); ?>" /></a>
							<div class="ugml-admin-inner-div1" >

								<img src="<?php echo esc_url($url1); ?>" class="ugml-meta-image" alt=""  style="">
								<input type="hidden" id="unique_string[]" name="unique_string[]" value="<?php echo esc_attr($UniqueString); ?>" />

								<p>
									<label><?php _e('Display Type','UGML_TEXT_DOMAIN')?></label>
									<select name="UGML_display_type[]" id="UGML_display_type[]" style="width:100%; margin-top:5px;">
										<optgroup label="Select Type">
											<option value="image" <?php if($type == 'image') echo "selected=selected"; ?>><i class="fa fa-image"></i> <?php _e('Image','UGML_TEXT_DOMAIN')?></option>
											
											<option value="yv_video" <?php if($type == 'yv_video') echo "selected=selected"; ?>><i class="fa fa-youtube-play"></i> <?php _e('Youtube/Vimeo video','UGML_TEXT_DOMAIN')?></option>
											
											<option value="link" <?php if($type == 'link') echo "selected=selected"; ?>><i class="fa fa-link"></i> <?php _e('Link','UGML_TEXT_DOMAIN')?></option>
										</optgroup>
									</select>
								</p>
							</div>
							<div class="ugml-admin-inner-div2" >

								<input type="text" id="UGML_image_url[]" name="UGML_image_url[]" class="ugml_label_text"  value="<?php echo esc_url($url); ?>"  readonly="readonly" style="display:none;" />
								<input type="text" id="UGML_gallery_admin_thumb[]" name="UGML_gallery_admin_thumb[]" class="ugml_label_text"  value="<?php echo esc_url($url1); ?>"  readonly="readonly" style="display:none;" />
								<input type="text" id="UGML_gallery_admin_circle[]" name="UGML_gallery_admin_circle[]" class="ugml_label_text"  value="<?php echo esc_url($circle); ?>"  readonly="readonly" style="display:none;" />

								<p>
									<div class="ugml_label"><label ><?php _e('Label','UGML_TEXT_DOMAIN')?>: </label></div>
									<input type="text" id="UGML_image_label[]" name="UGML_image_label[]" value="<?php echo esc_attr($name); ?>" placeholder="Enter Label Here" class="ugml_label_text">
								</p>
								<p>
									<label class="ugml_label" style="vertical-align:top;"><?php _e('Description','UGML_TEXT_DOMAIN')?>: </label>
									<textarea id="UGML_image_descp[]" name="UGML_image_descp[]" class="ugml_textarea" placeholder="Enter Image Description"><?php echo esc_html($description); ?></textarea>
								</p>
								<p>
									<label class="ugml_label"><?php _e('Link','UGML_TEXT_DOMAIN')?>: </label>
									<input type="text" id="UGML_external_link[]" name="UGML_external_link[]" value="<?php echo esc_url($link); ?>" placeholder="Enter Link URL" class="ugml_label_text">
								</p>
								<p>
									<label class="ugml_label"><?php _e('Extra Button url','UGML_TEXT_DOMAIN')?>: </label>
									<input type="text" id="UGML_extra_button_url[]" name="UGML_extra_button_url[]" value="<?php echo esc_url($extra_button_url); ?>" placeholder="Enter Extra Button URL" class="ugml_label_text">
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

		<!--Add New Image Button-->
		<div class="ugml-image-entry add_ugml_new_image" id="ugml_gallery_upload_button" data-uploader_title="Upload Image" data-uploader_button_text="Select" >
			<div class="dashicons dashicons-plus"></div>
			<p>
				<?php _e('Add New Media', 'UGML_TEXT_DOMAIN'); ?>
			</p>
		</div>
		<div style="clear:left;"></div>
        <?php
    }

	/** Call settings meta box */
    public function UGML_settings_meta_box_function($post) {

		require_once('ultimate-gallery-master-lite-settings.php');
	}

	/** Shortcode metabox function */
	public function UGML_shotcode_meta_box_function() { ?>
		<p><?php _e("Use below shortcode in any Page/Post to publish your gallery", 'UGML_TEXT_DOMAIN');?></p>
		<input readonly="readonly" type="text" value="<?php echo "[UGML id=".get_the_ID()."]"; ?>">
		<?php
	}

	public function admin_thumb($id) {
		$image  = wp_get_attachment_image_src($id, 'UGML_gallery_admin_original', true);
		$image1 = wp_get_attachment_image_src($id, 'UGML_gallery_admin_thumb', true);
		$circle = wp_get_attachment_image_src($id, 'UGML_gallery_admin_circle', true);
		$UniqueString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        ?>
		<li class="ugml-image-entry" id="ugml_img">
			<a class="image_gallery_remove ugmlgallery_remove" href="#gallery_remove" id="ugml_remove_bt" ><img src="<?php echo  esc_url(UGML_PLUGIN_URL.'images/image-close-icon.png'); ?>" /></a>
			<div class="ugml-admin-inner-div1" >

				<img src="<?php echo esc_url($image1[0]); ?>" class="ugml-meta-image" alt=""  style="">
				<label><?php _e('Display Type','UGML_TEXT_DOMAIN')?></label>
				<select name="UGML_display_type[]" id="UGML_display_type[]" style="width:100%;">
					<optgroup label="Select Type">
						<option value="image" selected="selected"><i class="fa fa-image"></i> <?php _e('Image','UGML_TEXT_DOMAIN')?></option>
						<option value="yv_video"><i class="fa fa-link"></i> <?php _e('Youtube/Vimeo video','UGML_TEXT_DOMAIN')?></option>
						<option value="link"><i class="fa fa-link"></i> <?php _e('Link','UGML_TEXT_DOMAIN')?></option>
					</optgroup>
				</select>
			</div>
			<div class="ugml-admin-inner-div2" >
				<input type="text" id="UGML_image_url[]" name="UGML_image_url[]" class="ugml_label_text"  value="<?php echo esc_url($image[0]); ?>"  readonly="readonly" style="display:none;" />
				<input type="text" id="UGML_gallery_admin_thumb[]" name="UGML_gallery_admin_thumb[]" class="ugml_label_text"  value="<?php echo esc_url($image1[0]); ?>"  readonly="readonly" style="display:none;" />
				<input type="text" id="UGML_gallery_admin_circle[]" name="UGML_gallery_admin_circle[]" class="ugml_label_text"  value="<?php echo esc_url($circle[0]); ?>"  readonly="readonly" style="display:none;" />
				
				<p>
					<label class="ugml_label"><?php _e('Label','UGML_TEXT_DOMAIN')?>: </label>
					<input type="text" id="UGML_image_label[]" name="UGML_image_label[]" placeholder="Enter Label Here" class="ugml_label_text">
				</p>
				<p>
					<label class="ugml_label" style="vertical-align:top;"><?php _e('Description','UGML_TEXT_DOMAIN')?>: </label>
					<textarea id="UGML_image_descp[]" name="UGML_image_descp[]" class="ugml_textarea" placeholder="Enter Image Description"></textarea>
				</p>
				<p>
					<label class="ugml_label"><?php _e('Link','UGML_TEXT_DOMAIN')?>: </label>
					<input type="text" id="UGML_external_link[]" name="UGML_external_link[]" placeholder="Enter Link URL" class="ugml_label_text">
				</p>
				<p>
					<label class="ugml_label"><?php _e('Extra Button url','UGML_TEXT_DOMAIN')?>: </label>
					<input type="text" id="UGML_extra_button_url[]" name="UGML_extra_button_url[]" placeholder="Enter Extra Button URL" class="ugml_label_text">
				</p>
			</div>
		</li>
        <?php
    }


    public function ajax_get_thumbnail() {
        echo $this->admin_thumb($_POST['imageid']);
        die;
    }

	//save Image meta box values
    public function UGML_image_meta_box_save($PostID) {
		if(isset($PostID) && isset($_POST['UGML_wl_action'])) {
			if(isset($_POST['UGML_image_url'])){
				$TotalImages = count($_POST['UGML_image_url']);
				$ImagesArray = array();
				if($TotalImages) {
					for($i=0; $i < $TotalImages; $i++) {
						$image_label 		= stripslashes($_POST['UGML_image_label'][$i]);
						$url 				= $_POST['UGML_image_url'][$i];
						$url1 				= $_POST['UGML_gallery_admin_thumb'][$i];
						$circle 			= $_POST['UGML_gallery_admin_circle'][$i];
						$type 				= $_POST['UGML_display_type'][$i];
						$tagline 			= "";
						$description 		= $_POST['UGML_image_descp'][$i];
						$link 				= $_POST['UGML_external_link'][$i];
						$extra_button_url 	= $_POST['UGML_extra_button_url'][$i];
						$ImagesArray[] = array(
							'UGML_image_label' 				=> sanitize_text_field( $image_label ),
							'UGML_image_url' 				=> esc_url_raw( $url ),
							'UGML_gallery_admin_thumb' 		=> esc_url_raw( $url1 ),
							'UGML_gallery_admin_circle' 	=> esc_url_raw( $circle ),
							'UGML_display_type' 			=> sanitize_text_field( $type ),
							'UGML_image_tagline' 			=> sanitize_text_field( $tagline ),
							'UGML_image_descp' 				=> stripslashes(esc_attr($description)),
							'UGML_external_link' 			=> esc_url_raw( $link ),
							'UGML_extra_button_url' 		=> esc_url_raw( $extra_button_url )
						);
					}
					update_post_meta($PostID, 'UGML_all_photos_details', serialize($ImagesArray));
					update_post_meta($PostID, 'UGML_total_images_count', $TotalImages);
				}

			}else {
				$TotalImages = 0;
				update_post_meta($PostID, 'UGML_total_images_count', $TotalImages);
				$ImagesArray = array();
				update_post_meta($PostID, 'UGML_all_photos_details', serialize($ImagesArray));
			}
		}
    }

	//save settings meta box values
	public function UGML_settings_meta_save($PostID) {
	  if(isset($PostID) && isset($_POST['UGML_useIconButtons'])){
		$UGML_Grid_Layout  				= $_POST['UGML_Grid_Layout'] ;
		$UGML_Grid_Orientation    		= $_POST['UGML_Grid_Orientation'];
		$UGML_cvThumbnail    			= $_POST['UGML_cvThumbnail'];
		$UGML_maxWidth					= $_POST['UGML_maxWidth'];
		$UGML_maxHeight					= $_POST['UGML_maxHeight'];
		$UGML_Font_Style           		= $_POST['UGML_Font_Style'];
		$UGML_disableThumbnails			= $_POST['UGML_disableThumbnails'];
		$UGML_hoverColor 				= $_POST['UGML_hoverColor'];
		$UGML_Color_Opacity         	= $_POST['UGML_Color_Opacity'];
		$UGML_imageHoverTextColor		= $_POST['UGML_imageHoverTextColor'];
		$UGML_useIconButtons    		= $_POST['UGML_useIconButtons'];
		$UGML_IconStyle					= $_POST['UGML_IconStyle'];
		$UGML_thumbnailBorderSize		= $_POST['UGML_thumbnailBorderSize'];
		$UGML_spaceBwThumbnails			= $_POST['UGML_spaceBwThumbnails'];
		$UGML_showMenu					= $_POST['UGML_showMenu'];
		$UGML_menuBgColor				= $_POST['UGML_menuBgColor'];
		$UGML_showSearchBox				= $_POST['UGML_showSearchBox'];
		$UGML_menuPosition				= $_POST['UGML_menuPosition'];
		$UGML_showZoomButton			= $_POST['UGML_showZoomButton'];
		$UGML_showDescriptionButton		= $_POST['UGML_showDescriptionButton'];
		$UGML_descriptionByDefault		= $_POST['UGML_descriptionByDefault'];
		$UGML_Custom_CSS    			= $_POST['UGML_Custom_CSS'];
		$UGML_Settings_Array = serialize( array(
			'UGML_Grid_Layout'          	=> $UGML_Grid_Layout,
			'UGML_Grid_Orientation'    		=> $UGML_Grid_Orientation,
			'UGML_cvThumbnail'          	=> $UGML_cvThumbnail,
			'UGML_maxWidth'					=> $UGML_maxWidth,
			'UGML_maxHeight'				=> $UGML_maxHeight,
			'UGML_Font_Style'				=> $UGML_Font_Style,
			'UGML_disableThumbnails'		=> $UGML_disableThumbnails,
			'UGML_hoverColor'         		=> $UGML_hoverColor,
			'UGML_Color_Opacity'			=> $UGML_Color_Opacity,
			'UGML_imageHoverTextColor'		=> $UGML_imageHoverTextColor,
			'UGML_useIconButtons'          	=> $UGML_useIconButtons,
			'UGML_IconStyle'          		=> $UGML_IconStyle,
			'UGML_thumbnailBorderSize'     	=> $UGML_thumbnailBorderSize,
			'UGML_spaceBwThumbnails'     	=> $UGML_spaceBwThumbnails,
			'UGML_showMenu'					=> $UGML_showMenu,
			'UGML_menuBgColor'				=> $UGML_menuBgColor,
			'UGML_showSearchBox'			=> $UGML_showSearchBox,
			'UGML_menuPosition'				=> $UGML_menuPosition,
			'UGML_showZoomButton'			=> $UGML_showZoomButton,
			'UGML_showDescriptionButton'	=> $UGML_showDescriptionButton,
			'UGML_descriptionByDefault'		=> $UGML_descriptionByDefault,
			'UGML_Custom_CSS'   			=> $UGML_Custom_CSS
		) );

		$UGML_Gallery_Settings = "UGML_Gallery_Settings_".$PostID;
		update_post_meta($PostID, $UGML_Gallery_Settings, $UGML_Settings_Array);
	  }
	}
}

global $UGML;
$UGML = UGML::forge();

/**
 * Ultimate Gallery Master Short Code [UGML].
 */
require_once("ultimate-gallery-master-lite-shortcode.php");

/*	'Media Button' code for Page or Post */
add_action('media_buttons_context', 'ugml_add_ugml_custom_button');
add_action('admin_footer', 'ugml_add_ugml_inline_popup_content');

function ugml_add_ugml_custom_button($context) {
  $img = plugins_url( '/images/gallery.png' , __FILE__ );
  $container_id = 'UGML';
  $title =  __('Select Gallery to insert into post or page','UGML_TEXT_DOMAIN') ;
  $context = '<a class="button button-primary thickbox"  title="'. __("Select Gallery to insert into post or page",'UGML_TEXT_DOMAIN').'"
  href="#TB_inline?width=400&inlineId='.$container_id.'">
		<span class="wp-media-buttons-icon" style="background: url('.esc_url( $img ).'); background-repeat: no-repeat; background-position: left bottom;"></span>
	'. __("Get Gallery Shortcode",'UGML_TEXT_DOMAIN').'
	</a>';
  return $context;
}

function ugml_add_ugml_inline_popup_content() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#ugml_galleryinsert').on('click', function() {
			var id = jQuery('#ugml-gallery-select option:selected').val();
			window.send_to_editor('<p>[UGML id=' + id + ']</p>');
			tb_remove();
		})
	});
	</script>

	<div id="UGML" style="display:none;">
	  <h3><?php _e('Select Any Gallery to Insert Into Post or page','UGML_TEXT_DOMAIN');?></h3>
	  <?php
		$all_posts = wp_count_posts( 'ugml_cpt')->publish;
		$args = array('post_type' => 'ugml_cpt', 'posts_per_page' =>$all_posts);
		global $ugml_galleries;
		$ugml_galleries = new WP_Query( $args );
		if( $ugml_galleries->have_posts() ) { ?>
			<select id="ugml-gallery-select">
				<?php
				while ( $ugml_galleries->have_posts() ) : $ugml_galleries->the_post(); ?>
				<option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
				<?php
				endwhile;
				?>
			</select>
			<button class='button primary' id='ugml_galleryinsert'><?php _e('Insert Gallery Shortcode','UGML_TEXT_DOMAIN');?></button>
			<?php
		} else {
			_e('No Gallery found','UGML_TEXT_DOMAIN');
		}
		?>
	</div>
	<?php
}

function ugml_get_gallery_value($PostId){
	$UGML_Default_Options = array(
		'UGML_Grid_Layout'  			=> 'classic',
		'UGML_Grid_Orientation'			=> 'Vertical',
		'UGML_cvThumbnail'				=> 'animtext',
		'UGML_maxWidth'					=> '1600',
		'UGML_maxHeight'				=> '600',
		'UGML_Font_Style'				=> 'Arial',
		'UGML_disableThumbnails'		=> 'no',
		'UGML_hoverColor' 				=> '#31A3DD',
		'UGML_Color_Opacity'        	=> 0.8,	
		'UGML_imageHoverTextColor'		=> '#ffffff',
		'UGML_useIconButtons'			=> 'yes',
		'UGML_IconStyle'				=> 'no',
		'UGML_thumbnailBorderSize'		=> 0,
		'UGML_spaceBwThumbnails'		=> 5,
		'UGML_showMenu'					=> 'yes',
		'UGML_menuBgColor'				=> '#31A3DD',
		'UGML_showSearchBox'			=> 'yes',
		'UGML_menuPosition'				=> 'left',
		'UGML_showZoomButton'			=> 'yes',
		'UGML_showDescriptionButton'	=> 'yes',
		'UGML_descriptionByDefault'		=> 'yes',
		'UGML_Custom_CSS'				=> ''
	);
	
	$UGML_Settings = "UGML_Gallery_Settings_".$PostId;
	$UGML_Settings = unserialize(get_post_meta( $PostId, $UGML_Settings, true));
	
	$UGML_Settings = wp_parse_args($UGML_Settings , $UGML_Default_Options);
	
	return $UGML_Settings;
}
?>
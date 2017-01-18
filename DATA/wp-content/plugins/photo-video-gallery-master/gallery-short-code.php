<?php
add_shortcode( 'PVGM', 'PhotoVideoGalleryMasterShortCode' );
function PhotoVideoGalleryMasterShortCode( $Id ) {
    ob_start();
	
	/**
	 * Load Hex to Rgb color code function
	 */
	require_once("webhunt_color_code_function.php");
	
	/**
	 * Load All Image Gallery Custom Post Type
	 */
	$CPT_Name = "pvgm_gallery";
	$AllGalleries = array( 'post_id' => $Id['id'], 'post_type' => $CPT_Name, 'orderby' => 'ASC');
	$loop = new WP_Query( $AllGalleries );

	while ( $loop->have_posts() ) : $loop->the_post();
	
	/**
     * Load Saved Gallery Settings
     */

    if(!isset($AllGalleries['post_id'])) {
        $AllGalleries['post_id'] = "";		
    } else {
		$PVGM_Id = $AllGalleries['post_id'];
		$PVGM_Gallery_Settings = pvgm_get_gallery_value($PVGM_Id);
		if(count($PVGM_Gallery_Settings)) {
			$PVGM_Effect				= esc_attr( $PVGM_Gallery_Settings['PVGM_Effect'] );
			$PVGM_Color 				= esc_attr( $PVGM_Gallery_Settings['PVGM_Color'] );
			$PVGM_Label_Bg_Color 		= esc_attr( $PVGM_Gallery_Settings['PVGM_Label_Bg_Color'] );
			$PVGM_Label_Text_Color 		= esc_attr( $PVGM_Gallery_Settings['PVGM_Label_Text_Color'] );
			$PVGM_Descp_Text_Color 		= esc_attr( $PVGM_Gallery_Settings['PVGM_Descp_Text_Color'] );
			$PVGM_Show_Gallery_Title	= esc_attr( $PVGM_Gallery_Settings['PVGM_Show_Gallery_Title'] );
			$PVGM_Show_Image_Label		= esc_attr( $PVGM_Gallery_Settings['PVGM_Show_Image_Label'] );
			$PVGM_Show_Image_Descp		= esc_attr( $PVGM_Gallery_Settings['PVGM_Show_Image_Descp'] );
			$PVGM_Show_Image_Button		= esc_attr( $PVGM_Gallery_Settings['PVGM_Show_Image_Button'] );
			$PVGM_Button_Text			= esc_attr( $PVGM_Gallery_Settings['PVGM_Button_Text'] );
			$PVGM_Gallery_Layout		= esc_attr( $PVGM_Gallery_Settings['PVGM_Gallery_Layout'] );
			$PVGM_Open_Link        		= esc_attr( $PVGM_Gallery_Settings['PVGM_Open_Link'] );
			$PVGM_Font_Style			= esc_attr( $PVGM_Gallery_Settings['PVGM_Font_Style'] );
			$PVGM_Lable_Font_Size		= esc_attr( $PVGM_Gallery_Settings['PVGM_Lable_Font_Size']);
			$PVGM_Desc_Font_Size		= esc_attr( $PVGM_Gallery_Settings['PVGM_Desc_Font_Size']);
			$PVGM_Light_Box				= esc_attr( $PVGM_Gallery_Settings['PVGM_Light_Box'] );
			$PVGM_Image_Border			= esc_attr( $PVGM_Gallery_Settings['PVGM_Image_Border'] );
			$PVGM_Custom_CSS			= wp_filter_nohtml_kses( $PVGM_Gallery_Settings['PVGM_Custom_CSS'] );
		}
	}
	?>
		
	<script type="text/javascript">
		jQuery(document).ready(function(){
			;( function( jQuery ) {
				jQuery( '.swipebox_<?php echo $PVGM_Id;?>' ).swipebox({
							hideBarsDelay:0,
							hideCloseButtonOnMobile : false,
						});
			})( jQuery );
		});
	</script>
	<?php 
		
	$border =  PVGM_hex2rgb( "#000000" );
	$image_border = implode(", ", $border);
    $bg_color =  PVGM_hex2rgb( $PVGM_Color );
	$img_bg_color = implode(", ", $bg_color);
	
	$Label_Bg_Color =  PVGM_hex2rgb( $PVGM_Label_Bg_Color );
	$Label_Bg_Color = implode(", ", $Label_Bg_Color);
	
	$Label_Text_Color =  PVGM_hex2rgb( $PVGM_Label_Text_Color );
	$Label_Text_Color = implode(", ", $Label_Text_Color);
	
	// Image Label String Length Corp
	$str_lenght = 30;
	
	
	if($PVGM_Button_Text == ""){
		$PVGM_Button_Text = "Read More";
	}
	?>
	<style>
		.pvgm-gallery-title{
			font-weight: bold;font-size:18px;padding-bottom:20px; border-bottom:3px solid #f1f1f1; margin-bottom: 20px; text-align:center
		}
		.view-<?php echo $PVGM_Effect?> .mask {
			background-color: rgba(<?php echo $img_bg_color; ?>,1 ) !important;
		}
		.row .<?php echo $PVGM_Gallery_Layout; ?>{
			padding-left:5px !important;
			padding-right:5px !important;
		}
		<?php include("css/style-".$PVGM_Effect.".css");?>
	</style>
		<?php include("css/style_common.php");?>

	<style>
		<?php echo $PVGM_Custom_CSS; ?>
	</style>
	<?php if($PVGM_Show_Gallery_Title=="yes"){?>
	<div class="pvgm-gallery-title">
        <?php echo esc_attr( get_the_title($PVGM_Id) ) ;?>
    </div>
	<?php } ?>
	
	<div class="row">
	<?php
		$PVGM_AllPhotosDetails = unserialize(get_post_meta( $PVGM_Id, 'PVGM_all_photos_details', true));
		$TotalImages =  get_post_meta( $PVGM_Id, 'PVGM_total_images_count', true );
		if($TotalImages) {
			foreach($PVGM_AllPhotosDetails as $PVGM_SinglePhotoDetails) {
				$name = esc_attr( $PVGM_SinglePhotoDetails['PVGM_image_label'] );
				$UniqueString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
				$url = esc_url( $PVGM_SinglePhotoDetails['PVGM_image_url'] );
				$url1 = esc_url( $PVGM_SinglePhotoDetails['PVGM_gallery_admin_thumb'] );
				$url2 = esc_url( $PVGM_SinglePhotoDetails['PVGM_gallery_admin_medium'] );
				$circle = esc_url( $PVGM_SinglePhotoDetails['PVGM_gallery_admin_circle'] );
				$video = esc_url( $PVGM_SinglePhotoDetails['PVGM_video_link'] ); 
				$link = esc_url( $PVGM_SinglePhotoDetails['PVGM_external_link'] );
				$type = esc_attr( $PVGM_SinglePhotoDetails['PVGM_portfolio_type'] );
				$description = stripslashes(esc_attr($PVGM_SinglePhotoDetails['PVGM_image_descp']));
				if($type=="image"){
					$href_link = $url;
				}  elseif($type=="link"){
					$href_link = $link;
				} else {
					$href_link = $video;
				}
				
				?>
				<div class="<?php echo $PVGM_Gallery_Layout; ?> col-sm-6 wl-gallery">
				
				<?php if($PVGM_Show_Image_Button == "no"){ ?>
					<a href="<?php echo $href_link; ?>" <?php  
								if($type != "link") { if($PVGM_Light_Box == "lightbox2") { ?> data-rel="prettyPhoto[portfolio<?php echo $PVGM_Id;?>]" class="info" <?php } else { ?> class="swipebox_<?php echo $PVGM_Id;?> info" <?php } }else{ ?> class="info" target="<?php echo $PVGM_Open_Link; ?>" <?php } 
								?>>
						<div class=" view view-<?php echo $PVGM_Effect ?> " style="cursor:pointer !important;">
							<img src="<?php echo $circle; ?>" />
							<div class="mask">
							<?php if( $PVGM_Show_Image_Label =="yes"){ ?>
								<h2 class="h2"><?php if(strlen($name) > $str_lenght ) echo substr($name,0,$str_lenght)."..."; else echo $name; ?></h2>
							<?php } ?>	
								<p>
								<?php  if($PVGM_Show_Image_Descp == "yes") { 
									echo $description;
								} ?>
								</p>
							</div>
						</div> 
					</a>
				<?php } else {?>
				
					<div class=" view view-<?php echo $PVGM_Effect ?> ">
							<img src="<?php echo $circle; ?>" />
							<div class="mask">
								<?php if( $PVGM_Show_Image_Label =="yes"){ ?>
								<h2 class="h2"><?php if(strlen($name) > $str_lenght ) echo substr($name,0,$str_lenght)."..."; else echo $name; ?></h2>
								<?php } ?>
								<p>
								<?php  if($PVGM_Show_Image_Descp == "yes") { 
									echo $description;
								} ?>
								</p>
								<a href="<?php echo $href_link; ?>" <?php  
									if($type != "link") { if($PVGM_Light_Box == "lightbox2") { ?> data-rel="prettyPhoto[portfolio<?php echo $PVGM_Id;?>]" class="info" <?php } else { ?> class="swipebox_<?php echo $PVGM_Id;?> info" <?php } }else{ ?> class="info" target="<?php echo $PVGM_Open_Link; ?>" <?php } 
									?>><?php echo $PVGM_Button_Text; ?> </a>
							</div>
						</div>
				<?php } ?>
				
				</div>	
				<?php
			}
		}
		?>
	</div>	
	<?php
    return ob_get_clean();
	endwhile;
}
?>
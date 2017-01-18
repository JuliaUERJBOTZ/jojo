<?php
/**
 * Load Saved Gallery Pro Settings
 */
$PostId = $post->ID;
$PVGM_Gallery_Settings = pvgm_get_gallery_value($PostId);
if($PVGM_Gallery_Settings['PVGM_Show_Gallery_Title'] && $PVGM_Gallery_Settings['PVGM_Color'] ) {
	$PVGM_Effect				= esc_attr( $PVGM_Gallery_Settings['PVGM_Effect'] );
	$PVGM_Color 				= esc_attr( $PVGM_Gallery_Settings['PVGM_Color'] );
	$PVGM_Label_Bg_Color 		= esc_attr( $PVGM_Gallery_Settings['PVGM_Label_Bg_Color'] );
	$PVGM_Label_Text_Color 		= esc_attr( $PVGM_Gallery_Settings['PVGM_Label_Text_Color'] );
	$PVGM_Descp_Text_Color 		= esc_attr( $PVGM_Gallery_Settings['PVGM_Descp_Text_Color'] );
	$PVGM_Show_Gallery_Title	= esc_attr( $PVGM_Gallery_Settings['PVGM_Show_Gallery_Title'] );
	$PVGM_Show_Image_Label		= esc_attr( $PVGM_Gallery_Settings['PVGM_Show_Image_Label'] );
	$PVGM_Show_Image_Descp		= esc_attr( $PVGM_Gallery_Settings['PVGM_Show_Image_Descp'] );
	$PVGM_Show_Image_Button		= esc_attr( $PVGM_Gallery_Settings['PVGM_Show_Image_Button'] );
	$PVGM_Button_Text			= esc_html( $PVGM_Gallery_Settings['PVGM_Button_Text'] );
	$PVGM_Gallery_Layout		= esc_attr( $PVGM_Gallery_Settings['PVGM_Gallery_Layout'] );
	$PVGM_Open_Link        		= esc_attr( $PVGM_Gallery_Settings['PVGM_Open_Link'] );
	$PVGM_Font_Style			= esc_attr( $PVGM_Gallery_Settings['PVGM_Font_Style'] );
	$PVGM_Lable_Font_Size		= esc_attr( $PVGM_Gallery_Settings['PVGM_Lable_Font_Size']);
	$PVGM_Desc_Font_Size		= esc_attr( $PVGM_Gallery_Settings['PVGM_Desc_Font_Size']);
	$PVGM_Light_Box				= esc_attr( $PVGM_Gallery_Settings['PVGM_Light_Box'] );
	$PVGM_Image_Border			= esc_attr( $PVGM_Gallery_Settings['PVGM_Image_Border'] );
	$PVGM_Custom_CSS			= wp_filter_nohtml_kses( $PVGM_Gallery_Settings['PVGM_Custom_CSS'] );
}
?>
<script>
jQuery(document).ready(function(){

	PVGM_ReadButtonClick();

});
function PVGM_ReadButtonClick() {
	if (jQuery('#PVGM_Show_Image_Button1').is(":checked")) {
	  jQuery('.buttontext').show();
	} else {
		jQuery('.buttontext').hide();
	}
}
</script>
<style>
@media only screen and (min-width: 970px){
	#post-body.columns-2 #postbox-container-1 {
		float: right;
		margin-right: 15px;
		width: 280px;
		right:0;
		position:absolute;
	}
}
.thumb-pro th, .thumb-pro label, .thumb-pro h3, .thumb-pro{
	color:#31a3dd !important;
	font-weight:bold;
}
.caro-pro th, .caro-pro label, .caro-pro h3, .caro-pro{
	color:#DA5766 !important;
	font-weight:bold;
}
.arrow::after{
	transform: none;
	height: 0px;
	left:0px;
}
.arrow {
	left: auto;
}
.smart-forms .option-group
{
	padding-top:6px;
}
</style>

<input type="hidden" id="wl_action" name="wl_action" value="wl-save-settings">
<body class="woodbg">

	<div class="smart-wrap">
    	<div class="smart-forms smart-container wrap-1">

        	<div class="form-header header-primary">
            	<h4><i class="fa fa-list-ul"></i><?php _e('Apply Setting On Gallery','PVGM_TEXT_DOMAIN'); ?></h4>
            </div><!-- end .form-header section -->

            <form method="post" action="" id="form-ui">
            	<div class="form-body">

                    <div class="spacer-b30">
                    	<div class="tagline"><span><?php _e('Product by WebHunt Infotech','PVGM_TEXT_DOMAIN'); ?></span></div><!-- .tagline -->
                    </div>

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Transition Effect','PVGM_TEXT_DOMAIN'); ?>:</label>
                        <div class="section colm colm6">
                            <label class="field select">
                                <select name="PVGM_Effect" id="PVGM_Effect">
                                   <optgroup label="Select Effect">
										<option value="fifth" <?php if($PVGM_Effect == 'fifth') echo "selected=selected"; ?>><?php _e('Effect 1','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="third" <?php if($PVGM_Effect == 'third') echo "selected=selected"; ?>><?php _e('Effect 2','PVGM_TEXT_DOMAIN'); ?></option>
									 </optgroup>
                                </select>
                                <i class="arrow double"></i>
                            </label>

                        </div><!-- end section -->

                    </div><!-- end .frm-row section -->

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Image Hover Color','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" name="PVGM_Color" id="PVGM_Color1" value="#31A3DD" <?php if($PVGM_Color == '#31A3DD' ) { echo "checked"; } ?>>
									<span class="radio"></span> <?php _e('Sky Blue','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Color" id="PVGM_Color2" value="#de4c28" <?php if($PVGM_Color == '#de4c28' ) { echo "checked"; } ?>>
									<span class="radio"></span> <?php _e('Red','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Color" id="PVGM_Color3" value="#000000" <?php if($PVGM_Color == '#000000' ) { echo "checked"; } ?>>
									<span class="radio"></span>  <?php _e('Black','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Label Background Color','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" name="PVGM_Label_Bg_Color" id="PVGM_Label_Bg_Color1" value="#ffffff" <?php if($PVGM_Label_Bg_Color == '#ffffff' ) { echo "checked"; } ?>>
									<span class="radio"></span> <?php _e('White','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Label_Bg_Color" id="PVGM_Label_Bg_Color2" value="#000000" <?php if($PVGM_Label_Bg_Color == '#000000' ) { echo "checked"; } ?>>
									<span class="radio"></span>  <?php _e('Black','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>


					 <div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Label Text Color','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" name="PVGM_Label_Text_Color" id="PVGM_Label_Text_Color1" value="#ffffff" <?php if($PVGM_Label_Text_Color == '#ffffff' ) { echo "checked"; } ?>>
									<span class="radio"></span> <?php _e('White','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Label_Text_Color" id="PVGM_Label_Text_Color2" value="#000000" <?php if($PVGM_Label_Text_Color == '#000000' ) { echo "checked"; } ?>>
									<span class="radio"></span>  <?php _e('Black','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>


					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Description Color','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" name="PVGM_Descp_Text_Color" id="PVGM_Descp_Text_Color1" value="#ffffff" <?php if($PVGM_Descp_Text_Color == '#ffffff' ) { echo "checked"; } ?>>
									<span class="radio"></span> <?php _e('White','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Descp_Text_Color" id="PVGM_Descp_Text_Color2" value="#000000" <?php if($PVGM_Descp_Text_Color == '#000000' ) { echo "checked"; } ?>>
									<span class="radio"></span>  <?php _e('Black','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>

					<!-- Gallery Layout -->
					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Gallery Layout','PVGM_TEXT_DOMAIN'); ?>:</label>
                        <div class="section colm colm6">
                            <label class="field select">
                                <select name="PVGM_Gallery_Layout" id="PVGM_Gallery_Layout">
                                    <optgroup label="Select Gallery Layout">
										<option value="col-md-6" <?php if($PVGM_Gallery_Layout == 'col-md-6') echo "selected=selected"; ?>><?php _e('Two Column','PVGM_TEXT_DOMAIN')?></option>
									</optgroup>
                                </select>
                                <i class="arrow double"></i>
                            </label>
                        </div><!-- end section -->
                    </div><!-- end .frm-row section -->

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Show Gallery Title','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" name="PVGM_Show_Gallery_Title" id="PVGM_Show_Gallery_Title1" value="yes" <?php if($PVGM_Show_Gallery_Title == 'yes' ) { echo "checked"; } ?>>
									<span class="radio"></span> <?php _e('Yes','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Show_Gallery_Title" id="PVGM_Show_Gallery_Title2" value="no" <?php if($PVGM_Show_Gallery_Title == 'no' ) { echo "checked"; } ?>>
									<span class="radio"></span> <?php _e('No','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Show Image Label','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" name="PVGM_Show_Image_Label" id="PVGM_Show_Image_Label1" value="yes" <?php if($PVGM_Show_Image_Label == 'yes' ) { echo "checked"; } ?> >
									<span class="radio"></span> <?php _e('Yes','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Show_Image_Label" id="PVGM_Show_Image_Label2" value="no" <?php if($PVGM_Show_Image_Label == 'no' ) { echo "checked"; } ?> >
									<span class="radio"></span> <?php _e('No','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Show Description','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" name="PVGM_Show_Image_Descp" id="PVGM_Show_Image_Descp1" value="yes" <?php if($PVGM_Show_Image_Descp == 'yes' ) { echo "checked"; } ?> >
									<span class="radio"></span> <?php _e('Yes','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Show_Image_Descp" id="PVGM_Show_Image_Descp2" value="no" <?php if($PVGM_Show_Image_Descp == 'no' ) { echo "checked"; } ?> >
									<span class="radio"></span> <?php _e('No','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Show Button','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" name="PVGM_Show_Image_Button" id="PVGM_Show_Image_Button1" value="yes" <?php if($PVGM_Show_Image_Button == 'yes' ) { echo "checked"; } ?>  onclick="return PVGM_ReadButtonClick();">
									<span class="radio"></span> <?php _e('Yes','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Show_Image_Button" id="PVGM_Show_Image_Button2" value="no" <?php if($PVGM_Show_Image_Button == 'no' ) { echo "checked"; } ?> onclick="return PVGM_ReadButtonClick();" >
									<span class="radio"></span> <?php _e('No','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>

					<div class="frm-row buttontext">
                    	<label class="field-label colm colm3 align-right"><?php _e('Button Text','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
                           <label class="field prepend-icon">
                               <input type="text" name="PVGM_Button_Text" id="PVGM_Button_Text" class="gui-input" value="<?php echo $PVGM_Button_Text; ?>" placeholder="Button Text">
                               <span class="field-icon"><i class="fa fa-edit"></i></span>
                           </label>
                       </div><!-- end section -->
					</div>	<!-- end .frm-row section -->

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Open Link','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" name="PVGM_Open_Link" id="PVGM_Open_Link" value="_self" <?php if($PVGM_Open_Link == '_self' ) { echo "checked"; } ?> >
									<span class="radio"></span> <?php _e('In Same Tab','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" name="PVGM_Open_Link" id="PVGM_Open_Link" value="_blank" <?php if($PVGM_Open_Link == '_blank' ) { echo "checked"; } ?> >
									<span class="radio"></span> <?php _e('In New Tab','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Font Style','PVGM_TEXT_DOMAIN'); ?>:</label>
                        <div class="section colm colm6">
                            <label class="field select">
                                <select name="PVGM_Font_Style" id="PVGM_Font_Style">
                                    <optgroup label="Default Fonts">
										<option value="Arial" <?php selected($PVGM_Font_Style, 'Arial' ); ?>><?php _e('Arial','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_arial_black" <?php selected($PVGM_Font_Style, '_arial_black' ); ?>><?php _e('Arial Black','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="Courier New" <?php selected($PVGM_Font_Style, 'Courier New' ); ?>><?php _e('Courier New','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="georgia" <?php selected($PVGM_Font_Style, 'Georgia' ); ?>><?php _e('Georgia','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="grande" <?php selected($PVGM_Font_Style, 'Grande' ); ?>><?php _e('Grande','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_helvetica_neue" <?php selected($PVGM_Font_Style, '_helvetica_neue' ); ?>><?php _e('Helvetica Neue','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_impact" <?php selected($PVGM_Font_Style, '_impact' ); ?>><?php _e('Impact','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_lucida" <?php selected($PVGM_Font_Style, '_lucida' ); ?>><?php _e('Lucida','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_lucida" <?php selected($PVGM_Font_Style, '_lucida' ); ?>><?php _e('Lucida Grande','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_OpenSansBold" <?php selected($PVGM_Font_Style, 'OpenSansBold' ); ?>><?php _e('OpenSansBold','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_palatino" <?php selected($PVGM_Font_Style, '_palatino' ); ?>><?php _e('Palatino','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_sans" <?php selected($PVGM_Font_Style, '_sans' ); ?>><?php _e('Sans','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_sans" <?php selected($PVGM_Font_Style, 'Sans-Serif' ); ?>><?php _e('Sans-Serif','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_tahoma" <?php selected($PVGM_Font_Style, '_tahoma' ); ?>><?php _e('Tahoma','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_times"<?php selected($PVGM_Font_Style, '_times' ); ?>><?php _e('Times New Roman','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_trebuchet" <?php selected($PVGM_Font_Style, 'Trebuchet' ); ?>><?php _e('Trebuchet','PVGM_TEXT_DOMAIN'); ?></option>
										<option value="_verdana" <?php selected($PVGM_Font_Style, '_verdana' ); ?>><?php _e('Verdana','PVGM_TEXT_DOMAIN'); ?></option>
									</optgroup>
                                </select>
                                <i class="arrow double"></i>
                            </label>
                        </div><!-- end section -->
                    </div><!-- end .frm-row section -->

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Lable Font Size','PVGM_TEXT_DOMAIN'); ?>:</label>
                        <div class="section colm colm6">
                            <label class="field select">
                                <select name="PVGM_Lable_Font_Size" id="PVGM_Lable_Font_Size">
                                    <optgroup label="Select Label Font Size">
										<option value="11" <?php if($PVGM_Lable_Font_Size == '11') echo "selected=selected"; ?>>11</option>
										<option value="12" <?php if($PVGM_Lable_Font_Size == '12') echo "selected=selected"; ?>>12</option>
										<option value="13" <?php if($PVGM_Lable_Font_Size == '13') echo "selected=selected"; ?>>13</option>
										<option value="14" <?php if($PVGM_Lable_Font_Size == '14') echo "selected=selected"; ?>>14</option>
										<option value="15" <?php if($PVGM_Lable_Font_Size == '15') echo "selected=selected"; ?>>15</option>
										<option value="16" <?php if($PVGM_Lable_Font_Size == '16') echo "selected=selected"; ?>>16</option>
										<option value="17" <?php if($PVGM_Lable_Font_Size == '17') echo "selected=selected"; ?>>17</option>
										<option value="18" <?php if($PVGM_Lable_Font_Size == '18') echo "selected=selected"; ?>>18</option>
										<option value="19" <?php if($PVGM_Lable_Font_Size == '19') echo "selected=selected"; ?>>19</option>
										<option value="20" <?php if($PVGM_Lable_Font_Size == '20') echo "selected=selected"; ?>>20</option>
										<option value="21" <?php if($PVGM_Lable_Font_Size == '21') echo "selected=selected"; ?>>21</option>
										<option value="22" <?php if($PVGM_Lable_Font_Size == '22') echo "selected=selected"; ?>>22</option>
										<option value="23" <?php if($PVGM_Lable_Font_Size == '23') echo "selected=selected"; ?>>23</option>
										<option value="24" <?php if($PVGM_Lable_Font_Size == '24') echo "selected=selected"; ?>>24</option>
									</optgroup>
                                </select>
                                <i class="arrow double"></i>
                            </label>
                        </div><!-- end section -->
                    </div><!-- end .frm-row section -->

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Description Font Size','PVGM_TEXT_DOMAIN'); ?>:</label>
                        <div class="section colm colm6">
                            <label class="field select">
                                <select name="PVGM_Desc_Font_Size" id="PVGM_Desc_Font_Size">
                                    <optgroup label="Select Label Font Size">
										<option value="10" <?php if($PVGM_Desc_Font_Size == '10') echo "selected=selected"; ?>>10</option>
										<option value="11" <?php if($PVGM_Desc_Font_Size == '11') echo "selected=selected"; ?>>11</option>
										<option value="12" <?php if($PVGM_Desc_Font_Size == '12') echo "selected=selected"; ?>>12</option>
										<option value="13" <?php if($PVGM_Desc_Font_Size == '13') echo "selected=selected"; ?>>13</option>
										<option value="14" <?php if($PVGM_Desc_Font_Size == '14') echo "selected=selected"; ?>>14</option>
										<option value="15" <?php if($PVGM_Desc_Font_Size == '15') echo "selected=selected"; ?>>15</option>
										<option value="16" <?php if($PVGM_Desc_Font_Size == '16') echo "selected=selected"; ?>>16</option>
										<option value="17" <?php if($PVGM_Desc_Font_Size == '17') echo "selected=selected"; ?>>17</option>
										<option value="18" <?php if($PVGM_Desc_Font_Size == '18') echo "selected=selected"; ?>>18</option>
										<option value="19" <?php if($PVGM_Desc_Font_Size == '19') echo "selected=selected"; ?>>19</option>
										<option value="20" <?php if($PVGM_Desc_Font_Size == '20') echo "selected=selected"; ?>>20</option>
									</optgroup>
                                </select>
                                <i class="arrow double"></i>
                            </label>
                        </div><!-- end section -->
                    </div><!-- end .frm-row section -->

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Light Box Style','PVGM_TEXT_DOMAIN'); ?>:</label>
                        <div class="section colm colm6">
                            <label class="field select">
                                <select name="PVGM_Light_Box" id="PVGM_Light_Box">
                                    <optgroup label="Select Light Box Styles">
										<option value="lightbox" <?php if($PVGM_Light_Box == 'lightbox') echo "selected=selected"; ?>><?php _e('Swipe Box','PVGM_TEXT_DOMAIN'); ?></option>
									</optgroup>
                                </select>
                                <i class="arrow double"></i>
                            </label>
                        </div><!-- end section -->

                    </div><!-- end .frm-row section -->

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Image Border','PVGM_TEXT_DOMAIN'); ?>:</label>
						<div class="section colm colm6">
							<div class="option-group field">
								<label class="option">
									<input type="radio" onclick="return PVGM_BorderClick();" name="PVGM_Image_Border" id="PVGM_Image_Border" value="yes" <?php if($PVGM_Image_Border == 'yes' ) { echo "checked"; } ?> >
									<span class="radio"></span> <?php _e('Yes','PVGM_TEXT_DOMAIN'); ?>
								</label>

								<label class="option">
									<input type="radio" onclick="return PVGM_BorderClick();" name="PVGM_Image_Border" id="PVGM_Image_Border" value="no" <?php if($PVGM_Image_Border == 'no' ) { echo "checked"; } ?> >
									<span class="radio"></span> <?php _e('No','PVGM_TEXT_DOMAIN'); ?>
								</label>
							</div><!-- end .option-group section -->
						</div><!-- end section -->
					 </div>

					<div class="frm-row">
                    	<label class="field-label colm colm3 align-right"><?php _e('Custom CSS','PVGM_TEXT_DOMAIN'); ?>:</label>
                        <div class="section colm colm8">
							<label class="field prepend-icon">
								<textarea class="gui-textarea" id="PVGM_Custom_CSS" name="PVGM_Custom_CSS" placeholder="Put Your Css Here"><?php echo $PVGM_Custom_CSS; ?></textarea>
								<span class="field-icon"><i class="fa fa-comments"></i></span>
								<span class="input-hint">
									<strong><?php _e('Note','PVGM_TEXT_DOMAIN'); ?>:</strong> <?php _e('Please Do Not Use','PVGM_TEXT_DOMAIN'); ?> <b><?php _e('Style','PVGM_TEXT_DOMAIN'); ?></b> <?php _e('Tag with Custom CSS','PVGM_TEXT_DOMAIN'); ?>.
								</span>
							</label>
						</div><!-- end section -->
					</div>	<!-- end .frm-row section -->
				</div>
			</form>
		</div>
	</div>
</body>

<?php
get_header();
?>
<!-- Start Home Page Slider -->
<div id="kyma_owl_slider" class="owl-carousel">
	<?php for($i=1 ; $i<=3 ; $i++){ ?>
	<div class="item">
		<img src="<?php echo get_template_directory_uri(); ?>/images/slide1.jpg" alt="Slide Title">
		<div class="owl_slider_con">
			<span class="owl_text_a">
				<span>
					<span><?php _e('Kyma Theme IS The Best', 'kyma'); ?></span>
				<a href="#"><span><i class="fa fa-angle-right"></i></span></a>
				</span>
			</span>
			<span class="owl_text_b"><span><?php _e('Fully Responsive Design', 'kyma'); ?></span></span>
			<span class="owl_text_c"><span><?php _e('Lorem Ipsum is simply dummy text of the printing and industry', 'kyma'); ?></span></span>
			<span class="owl_text_d">
				<a href="#" target="_self" class="btn_a">
					<span><i class="in_left fa fa-shopping-cart"></i><span><?php _e('Purchase Now', 'kyma'); ?></span><i class="in_right fa fa-shopping-cart"></i></span>
				</a>
			</span>
		</div>
	</div>
	<?php } ?>
</div>

<!--- Home Page Service -->
<section class="content_section bg_gray">
	<div class="container icons_spacer">
		<div class="main_title centered upper">
			<h2><span class="line"><span class="dot"></span></span><?php _e('About Kyma', 'kyma'); ?></h2>
		</div>
		<div class="icon_boxes_con style1 clearfix">
			<?php $service_title = array('Super Coding', 'Best User Interface', 'Unique Design', 'Easy to Customize');
			$service_icon = array('fa fa-code', 'color1 fa fa-mobile', 'color2 fa fa-heart', 'color3 fa fa-key');
			$j=0;
			for($i=1 ; $i<=4 ; $i++){ ?>
			<div class="col-md-3">
				<div class="service_box">
					<span class="icon"><i class="<?php echo esc_attr($service_icon[$j]); ?>"></i></span>
					<div class="service_box_con centered">
						<h3><?php echo esc_attr($service_title[$j]); ?></h3>
						<span class="desc"><?php _e('There are many variations of demo text passed sages of Lorem Ipsum available the majority.', 'kyma'); ?></span>
						<a href="#" class="ser-box-link"><span></span><?php _e('Read More', 'kyma'); ?></a>
					</div>
				</div>
			</div>
			<?php $j++; } ?>
		</div>
	</div>
</section>
<!--- Portfolio Section -->
<section class="content_section bg_white">
	<div class="row_spacer clearfix">

		<div class="content">
			<div class="main_title centered upper">
				<h2><span class="line"><i class="fa fa-folder-open"></i></span><?php _e('Our Portfolio', 'kyma'); ?></h2>
			</div>
		</div>
		
			<!-- Filter Content -->
			<div class="hm_filter_wrapper three_blocks project_text_nav boxed_portos has_sapce_portos nav_with_nums upper_title upper_title">     
						   
				<div class="hm_filter_wrapper_con">
					<?php for($i=1 ; $i<=3 ; $i++){ ?>
					<div class="filter_item_block video">
						<div class="porto_block">
							<div class="porto_type">
								<a data-rel="magnific-popup" href="<?php echo get_template_directory_uri(); ?>/images/item4.jpg">
									<img src="<?php echo get_template_directory_uri(); ?>/images/item4.jpg" alt="Portfolio Name">
								</a>
								<div class="porto_nav">
									<a href="#" class="expand_img"><?php _e('View Larger', 'kyma'); ?></a>
									<a href="#" class="detail_link"><?php _e('More Details', 'kyma'); ?></a>
								</div>
							</div>
							<div class="porto_desc">
								<h6 class="name"><?php _e('Flat Logo Design', 'kyma'); ?></h6>
								<div class="porto_meta clearfix">
								<span class="porto_date"><span class="number"><?php _e('20141213', 'kyma'); ?></span><?php _e('December 13, 2014', 'kyma'); ?></span>
								</div>
							</div>
						</div>
					</div><!-- Item -->
					<?php } ?>
				</div>
			</div>
			<!-- End Filter Content -->
	</div>    
</section>
<!--- Blog Section -->


<section class="content_section bg_gray border_b_n">
	<div class="content row_spacer no_padding">	
		<div class="main_title centered upper">
			<h2><span class="line"><i class="fa fa-pencil-square-o"></i></span><?php _e('From The Blog', 'kyma'); ?></h2>
		</div>
		<div class="rows_container clearfix">
			<div class="hm_blog_grid">
				
				<!-- Filter Content -->
				<div class="hm_filter_wrapper masonry_grid_posts three_blocks">  
					<ul class="hm_filter_wrapper_con">
						<?php $blog_title = array('Mobile Ready Theme', 'Corporate Theme', 'Retina Ready Theme', 'Easy to Customize', 'Responsive Design', 'Quick Support');
						//$service_icon = array('fa fa-code', 'color1 fa fa-mobile', 'color2 fa fa-heart', 'color3 fa fa-key');
						$j=0;
						for($i=1 ; $i<=6 ; $i++){ ?>
						<li class="filter_item_block animated" data-animation-delay="300" data-animation="rotateInUpLeft">
							<div class="blog_grid_block">
								<div class="feature_inner">
									<div class="feature_inner_corners">
										<div class="feature_inner_btns">
											<a href="#" class="expand_image"><i class="fa fa-expand"></i></a>
											<a href="#" class="icon_link"><i class="fa fa-link"></i></a>
										</div>
										<div class="porto_galla">
										<a href="<?php echo get_template_directory_uri(); ?>/images/blog.jpg" class="feature_inner_ling" data-rel="magnific-popup">
											<img src="<?php echo get_template_directory_uri(); ?>/images/blog.jpg" alt="Post Title">
										</a>
										<a href="<?php echo get_template_directory_uri(); ?>/images/blog.jpg" class="feature_inner_ling" data-rel="magnific-popup">
											<img src="<?php echo get_template_directory_uri(); ?>/images/blog.jpg" alt="Post Title">
										</a>
										</div>
									</div>
								</div>
								<div class="blog_grid_con">
									<a href="#" class="blog_grid_format"><i class="fa fa-pencil"></i></a>
									<h6 class="title"><a href="#"></a><?php echo esc_attr($blog_title[$j]); ?></h6>
									<span class="meta">
										<span class="meta_part"><a href="#"><?php _e('2015/4/30', 'kyma'); ?></a></span>
										<span class="meta_slash">/</span>
										<span class="meta_part"><a href="#"><?php _e('34 Comments', 'kyma'); ?></a></span>
										<span class="meta_slash">/</span>
										<span class="meta_part"><a href="#"><?php _e('News', 'kyma'); ?></a></span>
									</span>
									<p class="desc"><?php _e('Lorem Ipsum is simply dummy text of the prine
		and typesetting industry Lorem Ipsum.', 'kyma'); ?></p>
								</div>
							</div>
						</li>
						<?php $j++; } ?>
					</ul>
				</div>
				<!-- End Filter Content -->
				<div class="centered post-btn1">
                    <a class="btn_c append-button">
                        <span class="btn_c_ic_a"><i class="fa fa-repeat"></i></span>
                        <span class="btn_c_t"><?php _e('See More Posts', 'kyma'); ?></span>
                        <span class="btn_c_ic_b"><i class="fa fa-repeat"></i></span>
                    </a>
                </div>
			</div>
			<!-- End blog grid -->
		</div>
	</div>
</section>
<!-- Intro Banner -->
	<div class="welcome_banner full_colored">
	    <div class="content clearfix">
		    <h3><?php _e('Best HTML5 Theme Ever!','kyma'); ?></h3>
		<a href="#" target="_self" class="btn_a f_right">
		    <span><i class="in_left  fa fa-shopping-cart"></i><span><?php _e('Purchase Now','kyma');?> </span><i class="in_right fa fa-shopping-cart"></i></span>
		</a>
		<span class="intro_text"><?php _e('There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour of this randomised words which don`t look even slightly believable If you are going to use a passage of Lorem Ipsum.','kyma'); ?></span>
	    </div>
	</div>    
	<!-- End Intro Banner -->
<?php	
get_footer();
?>
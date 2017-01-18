<?php $kyma_theme_options = kyma_theme_options();
if ($kyma_theme_options['home_extra_enabled'] == 1){ ?>
<section class="content_section extra_section">
    <div class="container row_spacer">
			<p><?php echo apply_filters('the_content', $kyma_theme_options['extra_content_home']); ?></p>
    </div>
</section>
<?php } ?>
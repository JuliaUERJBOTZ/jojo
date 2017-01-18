<?php
/**
 * Welcome Screen Class
 */
class Kyma_Welcome {

	/**
	 * Constructor for the welcome screen
	 */
	public function __construct() {

		/* create dashbord page */
		add_action( 'admin_menu', array( $this, 'Kyma_lite_welcome_register_menu' ) );

		/* activation notice */
		add_action( 'load-themes.php', array( $this, 'Kyma_lite_activation_admin_notice' ) );

		/* enqueue script and style for welcome screen */
		add_action( 'admin_enqueue_scripts', array( $this, 'Kyma_lite_welcome_style_and_scripts' ) );

		/* enqueue script for customizer */
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'Kyma_lite_welcome_scripts_for_customizer' ) );

		/* load welcome screen */
		add_action( 'Kyma_lite_welcome', array( $this, 'Kyma_lite_welcome_getting_started' ), 	    10 );
		
		add_action( 'Kyma_lite_welcome', array( $this, 'Kyma_lite_welcome_child_themes' ), 		    30 );
		
		add_action( 'Kyma_lite_welcome', array( $this, 'Kyma_lite_welcome_plugins' ), 		    40 );
		
		add_action( 'Kyma_lite_welcome', array( $this, 'Kyma_lite_welcome_changelog' ), 				50 );

		/* ajax callback for dismissable required actions */
		add_action( 'wp_ajax_Kyma_lite_dismiss_required_action', array( $this, 'Kyma_lite_dismiss_required_action_callback') );
		add_action( 'wp_ajax_nopriv_Kyma_lite_dismiss_required_action', array($this, 'Kyma_lite_dismiss_required_action_callback') );

	}

	/**
	 * Creates the dashboard page
	 * @see  add_theme_page()
	 * @since 1.8.2.4
	 */
	public function Kyma_lite_welcome_register_menu() {
		add_theme_page( 'About Kyma', 'About Kyma', 'activate_plugins', 'kyma-welcome', array( $this, 'Kyma_lite_welcome_screen' ) );
	}

	/**
	 * Adds an admin notice upon successful activation.
	 * @since 1.8.2.4
	 */
	public function Kyma_lite_activation_admin_notice() {
		global $pagenow;

		if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'Kyma_lite_welcome_admin_notice' ), 99 );
		}
	}

	/**
	 * Display an admin notice linking to the welcome screen
	 * @since 1.8.2.4
	 */
	public function Kyma_lite_welcome_admin_notice() {
		?>
			<div class="updated notice is-dismissible">
				<p><?php echo sprintf( esc_html__( 'Welcome! Thank you for choosing Kyma Theme! To fully take advantage of the best our theme can offer please make sure you visit our %swelcome page%s.', 'kyma' ), '<a href="' . esc_url( admin_url( 'themes.php?page=kyma-welcome' ) ) . '">', '</a>' ); ?></p>
				<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=kyma-welcome' ) ); ?>" class="button" style="text-decoration: none;"><?php _e( 'Get started with Kyma', 'kyma' ); ?></a></p>
			</div>
		<?php
	}

	/**
	 * Load welcome screen css and javascript
	 * @since  1.8.2.4
	 */
	public function Kyma_lite_welcome_style_and_scripts( $hook_suffix ) {

		if ( 'appearance_page_kyma-welcome' == $hook_suffix ) {
			wp_enqueue_style( 'kyma-welcome-screen-css', get_template_directory_uri() . '/inc/welcome-screen/css/welcome.css' );
			wp_enqueue_script( 'kyma-welcome-screen-js', get_template_directory_uri() . '/inc/welcome-screen/js/welcome.js', array('jquery') );

			global $Kyma_required_actions;

			$nr_actions_required = 0;

			wp_localize_script( 'kyma-welcome-screen-js', 'kymaLiteWelcomeScreenObject', array(
				'nr_actions_required' => $nr_actions_required,
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'template_directory' => get_template_directory_uri(),
				'no_required_actions_text' => __( 'Hooray! There are no required actions for you right now.','kyma' )
			) );
		}
	}

	/**
	 * Load scripts for customizer page
	 * @since  1.8.2.4
	 */
	public function Kyma_lite_welcome_scripts_for_customizer() {

		wp_enqueue_style( 'kyma-lite-welcome-screen-customizer-css', get_template_directory_uri() . '/inc/welcome-screen/css/welcome_customizer.css' );
		wp_enqueue_script( 'kyma-lite-welcome-screen-customizer-js', get_template_directory_uri() . '/inc/welcome-screen/js/welcome_customizer.js', array('jquery'), '20120206', true );

		global $Kyma_required_actions;

		$nr_actions_required = 0;

		

		wp_localize_script( 'kyma-lite-welcome-screen-customizer-js', 'kymaLiteWelcomeScreenCustomizerObject', array(
			'nr_actions_required' => $nr_actions_required,
			'aboutpage' => esc_url( admin_url( 'themes.php?page=kyma-welcome#actions_required' ) ),
			'customizerpage' => esc_url( admin_url( 'customize.php#actions_required' ) ),
			'themeinfo' => __('View Theme Info','kyma'),
		) );
	}

	/**
	 * Dismiss required actions
	 * @since 1.8.2.4
	 */
	public function Kyma_lite_dismiss_required_action_callback() {

		global $Kyma_required_actions;

		$Kyma_dismiss_id = (isset($_GET['dismiss_id'])) ? $_GET['dismiss_id'] : 0;

		echo $Kyma_dismiss_id; /* this is needed and it's the id of the dismissable required action */

		if( !empty($Kyma_dismiss_id) ):

			/* if the option exists, update the record for the specified id */
			if( get_option('Kyma_show_required_actions') ):

				$Kyma_show_required_actions = get_option('Kyma_show_required_actions');

				$Kyma_show_required_actions[$Kyma_dismiss_id] = false;

				update_option( 'Kyma_show_required_actions',$Kyma_show_required_actions );

			/* create the new option,with false for the specified id */
			else:

				$Kyma_show_required_actions_new = array();

				if( !empty($Kyma_required_actions) ):

					foreach( $Kyma_required_actions as $Kyma_required_action ):

						if( $Kyma_required_action['id'] == $Kyma_dismiss_id ):
							$Kyma_show_required_actions_new[$Kyma_required_action['id']] = false;
						else:
							$Kyma_show_required_actions_new[$Kyma_required_action['id']] = true;
						endif;

					endforeach;

				update_option( 'Kyma_show_required_actions', $Kyma_show_required_actions_new );

				endif;

			endif;

		endif;

		die(); // this is required to return a proper result
	}


	/**
	 * Welcome screen content
	 * @since 1.8.2.4
	 */
	public function Kyma_lite_welcome_screen() {

		require_once( ABSPATH . 'wp-load.php' );
		require_once( ABSPATH . 'wp-admin/admin.php' );
		require_once( ABSPATH . 'wp-admin/admin-header.php' );
		?>

		<ul class="kyma-lite-nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#getting_started" aria-controls="getting_started" role="tab" data-toggle="tab"><?php esc_html_e( 'Getting started','kyma'); ?></a></li>
			<li role="presentation"><a href="#child_themes" aria-controls="child_themes" role="tab" data-toggle="tab"><?php esc_html_e( 'Premium Themes','kyma'); ?></a></li>
			<li role="presentation"><a href="#pro_plugins" aria-controls="pro_plugins" role="tab" data-toggle="tab"><?php esc_html_e( 'Premium Plugins','kyma'); ?></a></li>
			<li role="presentation"><a href="#changelog" aria-controls="changelog" role="tab" data-toggle="tab"><?php esc_html_e( 'Changelog','kyma'); ?></a></li>
		</ul>

		<div class="kyma-lite-tab-content">

			<?php
			/**
			 * @hooked Kyma_lite_welcome_getting_started - 10
			 * @hooked Kyma_lite_welcome_actions_required - 20
			 * @hooked Kyma_lite_welcome_child_themes - 30
			 * @hooked Kyma_lite_welcome_plugins - 40
			 * @hooked Kyma_lite_welcome_github - 60
			 * @hooked Kyma_lite_welcome_changelog - 50
			 */
			do_action( 'Kyma_lite_welcome' ); ?>

		</div>
		<?php
	}

	/**
	 * Getting started
	 * @since 1.8.2.4
	 */
	public function Kyma_lite_welcome_getting_started() {
		require_once( get_template_directory() . '/inc/welcome-screen/sections/getting-started.php' );
	}

	/**
	 * Pro themes
	 * @since 1.8.2.4
	 */
	public function Kyma_lite_welcome_child_themes() {
		require_once( get_template_directory() . '/inc/welcome-screen/sections/pro-themes.php' );
	}
	
	/**
	 * Pro Plugins
	 * @since 1.8.2.4
	 */
	public function Kyma_lite_welcome_plugins() {
		require_once( get_template_directory() . '/inc/welcome-screen/sections/pro-plugins.php' );
	}

	/**
	 * Changelog
	 * @since 1.8.2.4
	 */
	public function Kyma_lite_welcome_changelog() {
		require_once( get_template_directory() . '/inc/welcome-screen/sections/changelog.php' );
	}

}

$GLOBALS['Kyma_Welcome'] = new Kyma_Welcome();
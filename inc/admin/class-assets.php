<?php
/**
 * Bluehost_Admin_App_Assets class
 */
class Bluehost_Admin_App_Assets {
	/**
	 * @var string
	 */
	protected $page_hook = 'bluehost';
	/**
	 * @var string
	 */
	protected $current_admin_hook;
	/**
	 * @var stdClass
	 */
	protected static $instance;
	/**
	 * @var array
	 */
	protected $app_dependencies = array(
		'wp-a11y',
		'wp-api-fetch',
		'wp-data',
		'wp-dom',
		'wp-dom-ready',
		'wp-keycodes',
		'wp-element',
		'wp-components',
		'react-router-dom',
		'axios'
	);
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected $url;
	/**
	 * @return Bluehost_Admin_App_Assets|stdClass
	 */
	public static function return_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Bluehost_Admin_App_Assets ) ) {
			self::$instance = new Bluehost_Admin_App_Assets();
			self::$instance->primary_init();
		}
		return self::$instance;
	}
	/**
	 *
	 */
	protected function primary_init() {
		// TODO: restore admin css patch for nav
		// <style>li#toplevel_page_bluehost a:after,
		// li#toplevel_page_bluehost a:after {
    	// border: 0px transparent !important;
		// }</style>
		$this->url = trailingslashit( MM_BASE_URL ) . 'assets/';
		add_action( 'admin_enqueue_scripts', array( $this, 'regsiter_global_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
	}
	public function regsiter_global_assets() {
		$min  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$rand = time();
		wp_register_style(
			'bluehost-font',
			'https://fonts.googleapis.com/css?family=Open+Sans:300,600'
		);

		wp_register_script(
			'axios',
			$this->url . 'axios' . $min . '.js',
			array(),
			empty( $min ) ? $rand : '0.18.0'
		);

		wp_register_script(
			'react-router-dom',
			$this->url . 'react-router-dom' . $min . '.js',
			array( 'wp-element' ),
			empty( $min ) ? $rand : '5.0.0'
		);

		wp_register_style(
			'animatecss',
			$this->url . 'animate' . $min . '.css',
			array(),
			empty( $min ) ? $rand : '3.7.1'
		);

		wp_register_style(
			'bluehost-brand',
			$this->url . 'bluehost.css',
			array(),
			empty( $min ) ? $rand : '0.1.0'
		);

		wp_register_style(
			'purecss',
			$this->url . 'pure/pure' . $min . '.css',
			array(),
			empty( $min ) ? $rand : '1.0'
		);

		wp_register_style(
			'purecss-base',
			$this->url . 'pure/base' . $min . '.css',
			array(),
			empty( $min ) ? $rand : '1.0'
		);

		wp_register_style(
			'purecss-grids-base',
			$this->url . 'pure/grids' . $min . '.css',
			array(),
			empty( $min ) ? $rand : '1.0'
		);

		wp_register_style(
			'purecss-grids',
			$this->url . 'pure/grids-responsive' . $min . '.css',
			array( 'purecss-base','purecss-grids-base' ),
			empty( $min ) ? $rand : '1.0'
		);

	}
	/**
	 * @param $hook
	 */
	public function register_assets( $hook ) {
		$this->current_admin_hook = $hook;
		if ( false !== stripos( $this->current_admin_hook, $this->page_hook ) ) {
			$this->page_css( $this->url );
			$this->page_js( $this->url );
		}
	}
	/**
	 * Register Page CSS Here
	 */
	protected function page_css( $assets_url ) {
		wp_register_style(
			'eig-wp-admin-ui-styles',
			$assets_url . 'admin.css',
			array( 'wp-components', 'animatecss', 'bluehost-font', 'bluehost-brand', 'purecss-grids' ),
			time()
		);
		wp_enqueue_style( 'eig-wp-admin-ui-styles' );
	}
	/**
	 * Register Page JS Here
	 */
	protected function page_js( $dist_url ) {
		wp_register_script(
			'eig-wp-admin-ui-manifest',
			$dist_url . 'admin-manifest.js',
			$this->app_dependencies,
			time(),
			true
		);
		wp_register_script(
			'eig-wp-admin-ui-app',
			$dist_url . 'admin.js',
			array( 'eig-wp-admin-ui-manifest' ),
			time(),
			true
		);
		wp_enqueue_script( 'eig-wp-admin-ui-app' );

		$coming_soon = get_option( 'mm_coming_soon', 'false' );
		wp_add_inline_script( 'eig-wp-admin-ui-app', 'window.bluehost={comingSoon:' . $coming_soon . '};' );

		if ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) {
			wp_enqueue_script(
				'livereload',
				'http://localhost:35729/livereload.js',
				array( 'eig-wp-admin-ui-app' ),
				time(),
				true
			);
		}
	}
}
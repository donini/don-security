<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/don-security
 * @since      1.0.0
 *
 * @package    Don_Security
 * @subpackage Don_Security/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Don_Security
 * @subpackage Don_Security/admin
 * @author     Rodrigo Donini <donini@gmail.com>
 */
class Don_Security_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_dependencies();
		$this->run_options();

	}


	/**
	 * Load the required dependencies for admin area.
	 *
	 * Include the following files that make up the admin features:
	 *
	 * - Don_Security_Options. Define the options of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The file is responsible for show the bunch of options of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/don-security-admin-display.php';

	}

	/**
	 * Run the options the user set.
	 *
	 * @since    1.0.0
	 */
	public function run_options() {
		$ds_options	= get_option('don_security_settings_options');

		if (is_array($ds_options)) {
			if ("1" === $ds_options['disable_robots']){
				add_action('do_robots', array($this, 'hook_robots'), 1);
			}
			if ("1" === $ds_options['disable_xml_rpc']) {
				add_filter('wp_xmlrpc_server_class', array($this, 'add_fake_xmlrpc') );
			}
			if ("1" === $ds_options['remove_generator_info']) {
				remove_action('wp_head', 'wp_generator');
				add_filter('the_generator', array($this, 'remove_generator') );
			}
			if ("1" === $ds_options['remove_version_number']) {
				add_action('init', array($this, 'init'));
			}
			if ("1" === $ds_options['disable_detect_agents']) {
				$this->blur_user_agent();
			}
			if ("1" === $ds_options['prevent_advanced_fingerprinting']) {
				$this->prevent_fingerprint();
			}
			if ("1" === $ds_options['stop_plugin_enumeration']) {
				$this->stop_plugin_enumeration();
			}
			if ("1" === $ds_options['prevent_username_enumeration']) {
				$this->prevent_user_enumeration();
			}
			if ("1" === $ds_options['stop_wpconfig_enumeration']) {
				$this->stop_wpconfig_enumeration();
			}
		}
	}

	public function hook_robots() {
		status_header(404);
		die();
	}

	public function add_fake_xmlrpc() {
		if (!empty($_POST)) {
			return 'wp_xmlrpc_server';
		} else {
			return 'fake_xmlrpc';
		}
	}

	public function remove_generator() {
		return '';
	}

	public function init() {
		global $wp_version;
		$wp_version = 'version_hidden';
	}

	public function blur_user_agent() {
		if (!empty($_SERVER['HTTP_USER_AGENT']) && preg_match('/WPScan/i', $_SERVER['HTTP_USER_AGENT'])) {
			die('Wrong user agent');
		}
	}

	public function prevent_fingerprint() {
		if (isset($_GET['advanced_fingerprinting'])) {
			switch ($_GET['advanced_fingerprinting']) {
				case '1':
					// Unpack file
					$file = gzopen(ABSPATH.'wp-includes/js/tinymce/wp-tinymce.js.gz', 'rb');
					// Add comment
					$out = '// '.uniqid(true)."\n";
					while(!gzeof($file)) {
						$out .= gzread($file, 4096);
					}

					// Pack again
					header('Content-type: application/x-gzip');
					echo gzencode($out);
				break;

				default:
					status_header(404);
			}

			die();
		}
	}

	public function stop_plugin_enumeration() {
		if (isset($_GET['plugin_enumeration'])) {
			// Display something random
			die('<!-- ' .uniqid() .'-->');
		}
	}

	public function prevent_user_enumeration() {
		if (!is_admin() && isset($_REQUEST['author'])) {
			status_header(404);
			die();
		}
	}

	public function stop_wpconfig_enumeration() {
		$transient_name = 'wce_block_'.$_SERVER['REMOTE_ADDR'];

		$transient_value = get_transient($transient_name);

		if ($transient_value !== false) {
		  die('BANNED!');
		}

		if (isset($_GET['wp_config_enumeration'])) {
		  set_transient($transient_name, 1, DAY_IN_SECONDS);
		  die('BANNED!');
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Don_Security_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Don_Security_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/don-security-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Don_Security_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Don_Security_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/don-security-admin.js', array( 'jquery' ), $this->version, false );

	}

}
/*
 * Fake class for xmlrpc
 */
class fake_xmlrpc {
	function serve_request() {
		die();
	}
}

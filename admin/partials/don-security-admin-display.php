<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/plugins/don-security
 * @since      1.0.0
 *
 * @package    Don_Security
 * @subpackage Don_Security/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}
/* ------------------------------------*
 * Class to set Don_Security settings in wp-admin
 * ------------------------------------*/
class Don_Security_Options {
  
    /*--------------------------------------------*
     * Attributes
     *--------------------------------------------*/
  
    /** Refers to a single instance of this class. */
    private static $instance = null;
     
    /* Saved options */
    public $options;
  
    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/
  
    /**
     * Creates or returns an instance of this class.
     *
     * @return  Don_Security_Options A single instance of this class.
     */
    public static function get_instance() {
  
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
  
        return self::$instance;
  
    } // end get_instance;
  
    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    private function __construct() {
        // Add the page to the admin menu
        add_action( 'admin_menu', array( &$this, 'add_page' ) );

        // Register page options
        add_action( 'admin_init', array( &$this, 'register_page_options') );

        // Get registered option
        $this->options = get_option( 'don_security_settings_options' );
    }
  
    /*--------------------------------------------*
     * Functions
     *--------------------------------------------*/

    /**
     * Function that will add the options page under Setting Menu.
     */
    public function add_page() { 
        add_options_page( __('Don Security', 'don-security'), __('Don Security', 'don-security'), 'manage_options', 'don_security_settings_options', array( $this, 'display_page' ) );
    }

    /**
     * Function that will display the options page.
     */
    public function display_page() { 
        ?>
        <div class="wrap">
            <h2><?php echo __('Security Options', 'don-security');?></h2>
            <form method="post" action="options.php">     
                <?php 
                settings_fields('don_security_settings_options');
                do_settings_sections('don_security_settings_options');
                submit_button();
                ?>
            </form>
        </div> <!-- /wrap -->
        <?php    
    }

    /**
     * Function that will register admin page options.
     */
    public function register_page_options() { 

        // Add Section for option fields
        add_settings_section( 'don_security_section', __('Select the methods you want prevent to scan.', 'don-security'), array( $this, 'display_section' ), 'don_security_settings_options' );

        // Add disable robots.txt
        add_settings_field( 'ds_disable_robots', __('Disable robots.txt', 'don-security'), array( $this, 'ds_disable_robots_field' ), 'don_security_settings_options', 'don_security_section' ); 

        // Add disable detect agents
        add_settings_field( 'ds_disable_detect_agents', __('Disable detect User Agent', 'don-security'), array( $this, 'ds_disable_detect_agents_field' ), 'don_security_settings_options', 'don_security_section' ); 

        // Add disable xml-rpc
        add_settings_field( 'ds_disable_xml_rpc', __('Disable XML-RPC', 'don-security'), array( $this, 'ds_disable_xml_rpc_field' ), 'don_security_settings_options', 'don_security_section' ); 

        // Add Remove generator info
        add_settings_field( 'ds_remove_generator_info', __('Remove generator info', 'don-security'), array( $this, 'ds_remove_generator_info_field' ), 'don_security_settings_options', 'don_security_section' ); 

        // Add Prevent advanced fingerprinting
        add_settings_field( 'ds_prevent_advanced_fingerprinting', __('Prevent advanced fingerprinting', 'don-security'), array( $this, 'ds_prevent_advanced_fingerprinting_field' ), 'don_security_settings_options', 'don_security_section' ); 

        // Add Remove version number
        add_settings_field( 'ds_remove_version_number', __('Remove version number', 'don-security'), array( $this, 'ds_remove_version_number_field' ), 'don_security_settings_options', 'don_security_section' ); 

        // Add Stop plugin enumeration
        add_settings_field( 'ds_stop_plugin_enumeration', __('Prevent plugin enumeration', 'don-security'), array( $this, 'ds_stop_plugin_enumeration_field' ), 'don_security_settings_options', 'don_security_section' ); 

        // Add Prevent username enumeration
        add_settings_field( 'ds_prevent_username_enumeration', __('Prevent username enumeration', 'don-security'), array( $this, 'ds_prevent_username_enumeration_field' ), 'don_security_settings_options', 'don_security_section' ); 

        // Add Prevent username enumeration
        add_settings_field( 'ds_prevent_wpconfig_enumeration', __('Prevent wpconfig enumeration', 'don-security'), array( $this, 'ds_prevent_wpconfig_enumeration_field' ), 'don_security_settings_options', 'don_security_section' ); 


        // Register Settings
        register_setting( 'don_security_settings_options', 'don_security_settings_options', array( $this, 'validate_options' ) );
    }

    /**
     * Functions that display the fields.
     */
    public function ds_disable_robots_field() {
        $val = ( isset( $this->options['disable_robots'] ) ) ? $this->options['disable_robots'] : '';
        echo '<input type="checkbox" name="don_security_settings_options[disable_robots]" value="1" '. checked( 1, $val, false ) . '/>';
    }
    public function ds_disable_detect_agents_field() {
        $val = ( isset( $this->options['disable_detect_agents'] ) ) ? $this->options['disable_detect_agents'] : '';
        echo '<input type="checkbox" name="don_security_settings_options[disable_detect_agents]" value="1" '. checked( 1, $val, false ) . '/>';
    }
    public function ds_disable_xml_rpc_field() {
        $val = ( isset( $this->options['disable_xml_rpc'] ) ) ? $this->options['disable_xml_rpc'] : '';
        echo '<input type="checkbox" name="don_security_settings_options[disable_xml_rpc]" value="1" '. checked( 1, $val, false ) . '/>';
    }
    public function ds_remove_generator_info_field() {
        $val = ( isset( $this->options['remove_generator_info'] ) ) ? $this->options['remove_generator_info'] : '';
        echo '<input type="checkbox" name="don_security_settings_options[remove_generator_info]" value="1" '. checked( 1, $val, false ) . '/>';
    }
    public function ds_prevent_advanced_fingerprinting_field() {
        $val = ( isset( $this->options['prevent_advanced_fingerprinting'] ) ) ? $this->options['prevent_advanced_fingerprinting'] : '';
        echo '<input type="checkbox" name="don_security_settings_options[prevent_advanced_fingerprinting]" value="1" '. checked( 1, $val, false ) . '/>';
    }
    public function ds_remove_version_number_field() {
        $val = ( isset( $this->options['remove_version_number'] ) ) ? $this->options['remove_version_number'] : '';
        echo '<input type="checkbox" name="don_security_settings_options[remove_version_number]" value="1" '. checked( 1, $val, false ) . '/>';
    }
    public function ds_stop_plugin_enumeration_field() {
        $val = ( isset( $this->options['stop_plugin_enumeration'] ) ) ? $this->options['stop_plugin_enumeration'] : '';
        echo '<input type="checkbox" name="don_security_settings_options[stop_plugin_enumeration]" value="1" '. checked( 1, $val, false ) . '/>';
    }
    public function ds_prevent_username_enumeration_field() {
        $val = ( isset( $this->options['prevent_username_enumeration'] ) ) ? $this->options['prevent_username_enumeration'] : '';
        echo '<input type="checkbox" name="don_security_settings_options[prevent_username_enumeration]" value="1" '. checked( 1, $val, false ) . '/>';
    }
    public function ds_prevent_wpconfig_enumeration_field() {
        $val = ( isset( $this->options['prevent_wpconfig_enumeration'] ) ) ? $this->options['prevent_wpconfig_enumeration'] : '';
        echo '<input type="checkbox" name="don_security_settings_options[prevent_wpconfig_enumeration]" value="1" '. checked( 1, $val, false ) . '/>';
    }


    /**
     * Function that will validate all fields.
     */
    public function validate_options( $fields ) {
        $valid_fields = array();
 
        // Validateions
        $disable_robots = trim( $fields['disable_robots'] );
        $valid_fields['disable_robots'] = strip_tags( stripslashes( $disable_robots ) );

        $disable_detect_agents = trim( $fields['disable_detect_agents'] );
        $valid_fields['disable_detect_agents'] = strip_tags( stripslashes( $disable_detect_agents ) );

        $disable_xml_rpc = trim( $fields['disable_xml_rpc'] );
        $valid_fields['disable_xml_rpc'] = strip_tags( stripslashes( $disable_xml_rpc ) );

        $remove_generator_info = trim( $fields['remove_generator_info'] );
        $valid_fields['remove_generator_info'] = strip_tags( stripslashes( $remove_generator_info ) );

        $prevent_advanced_fingerprinting = trim( $fields['prevent_advanced_fingerprinting'] );
        $valid_fields['prevent_advanced_fingerprinting'] = strip_tags( stripslashes( $prevent_advanced_fingerprinting ) );

        $remove_version_number = trim( $fields['remove_version_number'] );
        $valid_fields['remove_version_number'] = strip_tags( stripslashes( $remove_version_number ) );

        $stop_plugin_enumeration = trim( $fields['stop_plugin_enumeration'] );
        $valid_fields['stop_plugin_enumeration'] = strip_tags( stripslashes( $stop_plugin_enumeration ) );

        $prevent_username_enumeration = trim( $fields['prevent_username_enumeration'] );
        $valid_fields['prevent_username_enumeration'] = strip_tags( stripslashes( $prevent_username_enumeration ) );

        $prevent_wpconfig_enumeration = trim( $fields['prevent_wpconfig_enumeration'] );
        $valid_fields['prevent_wpconfig_enumeration'] = strip_tags( stripslashes( $prevent_wpconfig_enumeration ) );

        return apply_filters( 'validate_options', $valid_fields, $fields);
    }
     
    /**
     * Callback function for settings section
     */
    public function display_section() { /* Leave blank */ } 


}
Don_Security_Options::get_instance();
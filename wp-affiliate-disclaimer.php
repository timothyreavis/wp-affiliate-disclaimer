<?php

/*
    Plugin Name: WP Affiliate Disclaimer
    Description: Adds an affiliate disclaimer at the end of each blog post
    Version: 1.0
    Author: Reavis Digital
    Author URI: https://reavisdigital.com/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class WpAffiliateDisclaimer {

    public $plugin_name;

	function __construct() {
		 $this->plugin_name = plugin_basename( __FILE__ );
	}

    function init() {
        // menu link
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
        // config settings actions
        add_action('admin_init', array( $this, 'settings'));
        // plugin list settings link
		add_filter( "plugin_action_links_$this->plugin_name", array( $this, 'settings_link' ) );
        // do the thing
        add_filter( 'the_content', array( $this, 'append_disclaimer' ), 1);
	}

    /* Add Settings link in plugin list */
	function settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=wp_affiliate_disclaimer">Settings</a>';
		array_push( $links, $settings_link );
		return $links;
	}

    /* Add plugin to sidebar */
	function add_admin_pages() {
        add_menu_page( 'WP Affiliate Disclaimer', 'Affiliate Disclaimer', 'manage_options', 'wp_affiliate_disclaimer', array( $this, 'config_index' ), 'dashicons-amazon', null );
    }

    /* Set plugin config page */
    function config_index() {
        require_once plugin_dir_path( __FILE__ ) . 'templates/config.php';
        // TODO: add config page
    }

    /* Populate plugin config page with settings */
    function settings() {
        add_settings_section( 'wcp_config_1', null, null, 'wp_affiliate_disclaimer' );
        // location dropdown
        add_settings_field( 'wpad_location','Display Location', array( $this, 'locationHTML'),'wp_affiliate_disclaimer','wcp_config_1' );
        register_setting( 'wpaffiliatedisclaimer', 'wpad_location', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0' ));
        // blurb
        add_settings_field( 'wpad_disclaimer_text','Disclaimer', array( $this, 'disclaimerTextHTML'),'wp_affiliate_disclaimer','wcp_config_1' );
        register_setting( 'wpaffiliatedisclaimer', 'wpad_disclaimer_text', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'As an affiliate, we may earn a commission from qualifying purchases made using our links.' ));
    }

    function locationHTML() { ?>
        <select name="wpad_location">
            <option value="1" <?php selected( get_option('wpad_location'), 1 ) ?>>Top of post</option>
            <option value="0" <?php selected( get_option('wpad_location'), 0 ) ?>>Bottom of post</option>
        </select>
    <?php }

    function disclaimerTextHTML() { ?>
        <textarea name="wpad_disclaimer_text"><?php echo esc_attr( get_option('wpad_disclaimer_text') )?></textarea>
    <?php }

    /* Add disclaimer to end of post */
    function append_disclaimer($post_content) {
        $disclaimer = "<p><small>" . esc_attr( get_option('wpad_disclaimer_text') ) . "</small></p>";
        if (is_single() && is_main_query()) {
            return $post_content . $disclaimer;
        }
        return $post_content;
    }
}

$wpad = new WpAffiliateDisclaimer();
$wpad->init();

?>
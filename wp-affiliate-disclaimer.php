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

	public function __construct() {
		 $this->plugin_name = plugin_basename( __FILE__ );
	}

    function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

		add_filter( "plugin_action_links_$this->plugin_name", array( $this, 'settings_link' ) );

        add_filter( 'the_content', array( $this, 'append_disclaimer' ), 1);
	}

    /* Add Settings link in plugin list */
	public function settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=wp_affiliate_disclaimer">Settings</a>';
		array_push( $links, $settings_link );
		return $links;
	}

    /* Add plugin to sidebar */
	public function add_admin_pages() {
        add_menu_page( 'WP Affiliate Disclaimer', 'Affiliate Disclaimer', 'manage_options', 'wp_affiliate_disclaimer', array( $this, 'config_index' ), 'dashicons-amazon', null );
   }

   /* Set plugin config page */
   public function config_index() {
        require_once plugin_dir_path( __FILE__ ) . 'templates/config.php';
        // TODO: add config page
    }

    /* Add disclaimer to end of post */
    public function append_disclaimer($post_content) {
        $disclaimer = '<p><small>As an affiliate, we may earn a commission from qualifying purchases made using our links.</small></p>';
        if (is_single() && is_main_query()) {
            return $post_content . $disclaimer;
        }
        return $post_content;
    }
}

$wpad = new WpAffiliateDisclaimer();
$wpad->init();

?>

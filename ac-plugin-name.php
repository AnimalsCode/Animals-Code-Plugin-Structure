<?php
/*
 * Plugin Name: {ANIMALS_CODE_PLUGIN_NAME}
 * Plugin URI:  {ANIMALS_CODE_PLUGIN_URL}
 * Version:     0.0.1
 * Description: {ANIMALS_CODE_PLUGIN_DESCRIPTION}
 * Author:      Animals Code
 * Author URI:  http://animalscode.com
 *
 * Text Domain: {ANIMALS_CODE_PLUGIN_TEXT_DOMAIN}
 * Domain Path: /languages/
 *
 * Requires at least: 4.1
 * Tested up to: 4.5.3
 *
 * Copyright: © 2016 Animals Code
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( ! defined('ABSPATH') ) exit; // Exit if accessed directly.

if ( ! class_exists( 'ACWC_{PLUGIN_NAME}' ) ) {
	class ACWC_{PLUGIN_NAME} {

		/* Plugin version. */
		const VERSION = '1.0.0';

		/* Required WC version. */
		const REQ_WC_VERSION = '2.3.0';

		/* Text domain. */
		const TEXT_DOMAIN = '{ANIMALS_CODE_PLUGIN_TEXT_DOMAIN}';

		/**
		 * @var ACWC_{PLUGIN_NAME} - the single instance of the class.
		 *
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main ACWC_{PLUGIN_NAME} Instance.
		 *
		 * Ensures only one instance of ACWC_{PLUGIN_NAME} is loaded or can be loaded.
		 *
		 * @static
		 * @see ACWC_{PLUGIN_NAME}()
		 * @return ACWC_{PLUGIN_NAME} - Main instance
		 * @since 1.0.0
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Foul!' ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Foul!' ), '1.0.0' );
		}

		/**
		 * Load the plugin.
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
			add_action( 'init', array( $this, 'init_plugin' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_meta_links' ), 10, 4 );

			// Include required files
			add_action( 'woocommerce_init', array( $this, 'includes' ) );
		}

		/**
		 * Plugin URL
		 */
		public static function plugin_url() {
			return plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		} // END plugin_url()

		/**
		 * Plugin Path
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		} // END plugin_path()

		/*
		 * Check requirements on activation.
		 */
		public function load_plugin() {
			global $woocommerce;

			// Check that the required WooCommerce is running.
			if ( version_compare( $woocommerce->version, self::REQ_WC_VERSION ) < 0 ) {
				add_action( 'admin_notices', array( $this, 'acwc_admin_notice' ) );
				return false;
			}

			// If Animals Code directory exists, load it's content.
			if ( is_dir( 'ac' ) ) {
				$files = glob( 'ac/*.php' );

				foreach ( $files as $file ) {
					// Load all files except the customers licence key.
					if ( $file != 'ac/key.php' ) {
						require_once( $file );
					}
				}
			}
		} // END load_plugin()

		/**
		 * Display a warning message if minimum version of WooCommerce check fails.
		 *
		 * @return void
		 */
		public function acwc_admin_notice() {
			echo '<div class="error"><p>' . sprintf( __( '%1$s requires at least %2$s %3$s in order to function. Please upgrade %2$s.', {ANIMALS_CODE_PLUGIN_TEXT_DOMAIN} ), '{ANIMALS_CODE_PLUGIN_NAME}', 'WooCommerce', self::REQ_WC_VERSION ) . '</p></div>';
		} // END acwc_admin_notice()

		/**
		 * Initialize the plugin if ready.
		 *
		 * @return void
		 */
		public function init_plugin() {
			// Load text domain.
			load_plugin_textdomain( {ANIMALS_CODE_PLUGIN_TEXT_DOMAIN}, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		} // END init_plugin()

		/**
		 * Show row meta on the plugin screen.
		 *
		 * @param mixed $links Plugin Row Meta
		 * @param mixed $file  Plugin Base file
		 * @return array
		 */
		public function plugin_meta_links( $links, $file, $data, $status ) {
			if ( $file == plugin_basename( __FILE__ ) ) {
				$author1 = '<a href="' . $data[ 'AuthorURI' ] . '">' . $data[ 'Author' ] . '</a>';
				$links[ 1 ] = sprintf( __( 'By %s', {ANIMALS_CODE_PLUGIN_TEXT_DOMAIN} ), $author1 );
			}

			return $links;
		} // END plugin_meta_links()

		/**
		 * Includes the plugin.
		 *
		 * @return void
		 */
		public function includes() {
			if ( is_admin() ) {
				//require_once( 'includes/admin/class-ac-admin-{plugin-name}.php' );
			}

			if ( ! is_admin() ) {
				//require_once( 'includes/class-ac-{plugin-name}.php' );
			}

		} // END include()

	} // END class

} // END if class exists

return ACWC_{PLUGIN_NAME}::instance();
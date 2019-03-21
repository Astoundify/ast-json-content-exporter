<?php
/**
 * Plugin Name: Content Exporter by Astoundify
 * Plugin URI: https://astoundify.com/
 * Description: A JSON content exporter for WordPress.
 * Author: Astoundify
 * Author URI: https://astoundify.com/
 * Version: 1.0.0
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: astoundify-content-exporter
 * Domain Path: resources/languages/
 *
 * @package Content Exporter
 * @category Core
 * @author Astoundify
 */

 /**
  * Init the plugins
  */
 function ast_init_content_exporters(){
	if ( ! is_admin() ) {
		return;
	}
	
	foreach (glob( plugin_dir_path( __FILE__ ) . "/classes/class-wp*.php" ) as $filename) {
		include_once $filename;
	}

	new AST\Content_Importer\WP_Submenu_Page();

 }
 add_action( 'plugins_loaded', 'ast_init_content_exporters', 0 );
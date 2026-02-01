<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://smjrifle.net
 * @since      1.0.0
 *
 * @package    Svg_Map_By_Saedi
 * @subpackage Svg_Map_By_Saedi/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Svg_Map_By_Saedi
 * @subpackage Svg_Map_By_Saedi/includes
 * @author     Saedi Works <info@saediworks.com>
 */
class Svg_Map_By_Saedi_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'svg-map-by-saedi',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

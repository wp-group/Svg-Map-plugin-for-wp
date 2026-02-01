<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://smjrifle.net
 * @since      1.0.0
 *
 * @package    Svg_Map_By_Saedi
 * @subpackage Svg_Map_By_Saedi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Svg_Map_By_Saedi
 * @subpackage Svg_Map_By_Saedi/public
 * @author     Saedi Works <info@saediworks.com>
 */
class Svg_Map_By_Saedi_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Svg_Map_By_Saedi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Svg_Map_By_Saedi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/svg-map-by-saedi-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Svg_Map_By_Saedi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Svg_Map_By_Saedi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/svg-map-by-saedi-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'svgData', $this->load_svg_data_for_js() );

	}
	/**
	 * loads svg point admin data to localize script
	 *
	 * @return array $svg_data_for_js
	 */
	public function load_svg_data_for_js() {
		global $wpdb;
		$svg_data_for_js = array();
		$points_for_js = array();
		$popup_for_js = array();
		$table_name = $wpdb->prefix . 'svg_map';
		$results = $wpdb->get_results( "SELECT * FROM $table_name" );
		if ( $wpdb->num_rows != 0 ) {
			foreach ( $results as $result ) :
				array_push( $points_for_js, $result->map_point );
				array_push( $popup_for_js, $result->map_popup );
			endforeach;
		}
		$svg_data_for_js['points_for_js'] = $points_for_js;
		$svg_data_for_js['popup_for_js'] = $popup_for_js;
		return $svg_data_for_js;
	}

}

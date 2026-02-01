<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://smjrifle.net
 * @since      1.0.0
 *
 * @package    Svg_Map_By_Saedi
 * @subpackage Svg_Map_By_Saedi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Svg_Map_By_Saedi
 * @subpackage Svg_Map_By_Saedi/admin
 * @author     Saedi Works <info@saediworks.com>
 */
class Svg_Map_By_Saedi_Admin
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/svg-map-by-saedi-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url(__FILE__) . 'js/svg-map-by-saedi-admin.js',
			array('jquery'),
			$this->version,
			true
		);

		// ✅ Attach nonce + ajaxurl to THIS script
		wp_localize_script(
			$this->plugin_name, // MUST match enqueue handle
			'svgMapSecurity',
			array(
				'nonce'    => wp_create_nonce('svg_map_nonce'),
				'ajax_url' => admin_url('admin-ajax.php'),
			)
		);
	}
}

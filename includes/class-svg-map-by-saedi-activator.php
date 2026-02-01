<?php

/**
* Fired during plugin activation
*
* @link       https://smjrifle.net
* @since      1.0.0
*
* @package    Svg_Map_By_Saedi
* @subpackage Svg_Map_By_Saedi/includes
*/

/**
* Fired during plugin activation.
*
* This class defines all code necessary to run during the plugin's activation.
*
* @since      1.0.0
* @package    Svg_Map_By_Saedi
* @subpackage Svg_Map_By_Saedi/includes
* @author     Saedi Works <info@saediworks.com>
*/
class Svg_Map_By_Saedi_Activator {

	/**
	* Short Description. (use period)
	*
	* Long Description.
	*
	* @since    1.0.0
	*/
	public static function activate() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'svg_map';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			map_point tinytext NOT NULL,
			map_popup text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'svg_map_db_version', '1.0' );
	}
}

<?php
/**
 * Plugin Name:       Svg Map By Saedi
 * Plugin URI:        https://saediworks.com
 * Description:       SVG Map plugin with admin-managed interactive points.
 * Version:           1.0.1
 * Author:            Saedi Works
 * Author URI:        https://smjrifle.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       svg-map-by-saedi
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*--------------------------------------------------------------
# Activation / Deactivation
--------------------------------------------------------------*/
function activate_svg_map_by_saedi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-svg-map-by-saedi-activator.php';
	Svg_Map_By_Saedi_Activator::activate();
}
function deactivate_svg_map_by_saedi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-svg-map-by-saedi-deactivator.php';
	Svg_Map_By_Saedi_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_svg_map_by_saedi' );
register_deactivation_hook( __FILE__, 'deactivate_svg_map_by_saedi' );

/*--------------------------------------------------------------
# Core Plugin Class
--------------------------------------------------------------*/
require plugin_dir_path( __FILE__ ) . 'includes/class-svg-map-by-saedi.php';

function run_svg_map_by_saedi() {
	$plugin = new Svg_Map_By_Saedi();
	$plugin->run();
}
run_svg_map_by_saedi();

/*--------------------------------------------------------------
# Admin Menu
--------------------------------------------------------------*/
add_action( 'admin_menu', 'svg_map_setup_menu' );

function svg_map_setup_menu() {
	add_menu_page(
		'SVG Map By Saedi',
		'SVG Map',
		'manage_options',
		'svg-map-saedi',
		'svg_map_init'
	);
}

/*--------------------------------------------------------------
# Admin Page + Nonce
--------------------------------------------------------------*/
function svg_map_init() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	wp_enqueue_script( 'jquery' );

	wp_localize_script(
		'jquery',
		'svgMapSecurity',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'svg_map_nonce' ),
		)
	);

	include plugin_dir_path( __FILE__ ) . 'admin/svg-map.php';
}

/*--------------------------------------------------------------
# AJAX: Save Data (SECURE)
--------------------------------------------------------------*/
add_action( 'wp_ajax_save_data', 'svg_map_save_data' );

function svg_map_save_data() {
	check_ajax_referer( 'svg_map_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized', 403 );
	}

	if ( empty( $_POST['points'] ) || ! is_array( $_POST['points'] ) ) {
		wp_die( 'Invalid input' );
	}

	global $wpdb;
	$table = $wpdb->prefix . 'svg_map';

	$points = array_map( 'absint', $_POST['points'] );
	$points_sql = implode( ',', $points );

	$wpdb->query( "DELETE FROM {$table} WHERE map_point NOT IN ($points_sql)" );

	$added = 0;

	foreach ( $points as $point ) {
		$exists = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM {$table} WHERE map_point = %d",
				$point
			)
		);

		if ( ! $exists ) {
			if ( $wpdb->insert(
				$table,
				array(
					'time'       => current_time( 'mysql' ),
					'map_point' => $point,
					'map_popup' => '',
				),
				array( '%s', '%d', '%s' )
			) ) {
				$added++;
			}
		}
	}

	echo esc_html( $added . ' Points Added' );
	wp_die();
}

/*--------------------------------------------------------------
# AJAX: Delete Data (SECURE)
--------------------------------------------------------------*/
add_action( 'wp_ajax_delete_data', 'svg_map_delete_data' );

function svg_map_delete_data() {
	check_ajax_referer( 'svg_map_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized', 403 );
	}

	global $wpdb;
	$table = $wpdb->prefix . 'svg_map';

	$point = isset( $_POST['point'] ) ? absint( $_POST['point'] ) : 0;
	if ( ! $point ) {
		wp_die( 'Invalid ID' );
	}

	$wpdb->delete(
		$table,
		array( 'id' => $point ),
		array( '%d' )
	);

	echo esc_html__( 'Point Deleted', 'svg-map-by-saedi' );
	wp_die();
}

/*--------------------------------------------------------------
# AJAX: Add Popup (SECURE + XSS FIX)
--------------------------------------------------------------*/
add_action( 'wp_ajax_add_popup', 'svg_map_add_popup' );

function svg_map_add_popup() {
	check_ajax_referer( 'svg_map_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized', 403 );
	}

	global $wpdb;
	$table = $wpdb->prefix . 'svg_map';

	$point = isset( $_POST['point'] ) ? absint( $_POST['point'] ) : 0;
	$popup = isset( $_POST['popup'] ) ? wp_kses_post( $_POST['popup'] ) : '';

	if ( ! $point ) {
		wp_die( 'Invalid ID' );
	}

	$wpdb->update(
		$table,
		array( 'map_popup' => $popup ),
		array( 'id' => $point ),
		array( '%s' ),
		array( '%d' )
	);

	echo esc_html__( 'Popup Updated', 'svg-map-by-saedi' );
	wp_die();
}

/*--------------------------------------------------------------
# Shortcode
--------------------------------------------------------------*/
function display_map() {
	include plugin_dir_path( __FILE__ ) . 'public/partials/svg-map-by-saedi-public-display.php';
}
add_shortcode( 'display_svg_map', 'display_map' );

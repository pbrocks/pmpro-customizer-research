<?php
/**
 * Plugin Nam PBrx Customize Examples
 * Description: Examples and a skeleton for hacking and creating new examples.
 * Plugin URI:  https://github.com/pbrocks/pmpro-customize-example
 * Author:      pbrocks, Weston Ruter, XWP
 * Author URI:  https://make.xwp.co/
 * Text Domain: pmpro-customize-example
 * Domain Path: /languages
 * Version:     0.1.0
 *
 * @package Customize_Featured_Content_Demo
 */

namespace PMPro_Customizer;

require_once __DIR__ . '/register-helper.php';

/**
 * Enqueue scripts for the customizer pane/controls/previewer.
 */
function customize_controls_enqueue_scripts() {
	$handle = 'pmpro-customize-example-pane';
	wp_enqueue_script(
		$handle,
		plugin_dir_url( __FILE__ ) . 'pane.js',
		array( 'customize-controls' )
	);
}
add_action( 'customize_controls_enqueue_scripts', __NAMESPACE__ . '\customize_controls_enqueue_scripts' );

/**
 * Handle initialization of customizer preview.
 */
function customize_preview_init() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\wp_enqueue_scripts' );
}
add_action( 'customize_preview_init', __NAMESPACE__ . '\customize_preview_init' );

/**
 * Enqueue scripts for the customizer preview.
 */
function wp_enqueue_scripts() {
	$handle = 'pmpro-customize-example-preview';
	wp_enqueue_script(
		$handle,
		plugin_dir_url( __FILE__ ) . 'preview.js',
		array( 'customize-preview' )
	);
}

<?php
/**
 * Plugin Name: PMPro Customizer
 * Plugin URI: http://testlab.sample.com/wiki/
 * Description: TAdd WordPress Customizer options to an installation with Paid Memberships Pro.
 * Version: 0.1.1
 * Author: pbrocks
 * Author URI: http://testlab.sample.com/wiki/
 */

namespace PMPro_Customizer;

/**
 * Description
 *
 * @return type Words
 */
// include( 'inc/functions/class-central-toggle-control.php' );
require_once( 'autoload.php' );
require_once __DIR__ . '/register-helper.php';


// inc\classes\Central_Toggle_Control::init();
inc\classes\PMPro_Customizer::init();
inc\classes\PMPro_Customizer_Additions::init();
// inc\classes\Setup_Functions::init();
// new inc\classes\Central_Toggle_Control();
if ( ! class_exists( 'WordPress_Welcomes_You' ) ) {
	 // new inc\classes\Central_Toggle_Control();
	 new inc\classes\WordPress_Welcomes_You();
}


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

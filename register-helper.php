<?php

namespace PMPro_Customizer\Register_Helper;

function customize_register( \WP_Customize_Manager $pmpro_additions ) {
	require_once __DIR__ . '/class-register-helper-customize-control.php';
	require_once __DIR__ . '/class-register-helper-customize-setting.php';

	$pmpro_additions->register_control_type(
		__NAMESPACE__ . '\Register_Helper_Customize_Control'
	);

	// $pmpro_additions->get_section ('AAA')->panel = 'BBB';
	$section = new \WP_Customize_Section(
		$pmpro_additions, 'static_register_helper_items', array(
			'title' => 'Register Helper (Static)',
			'panel' => 'pmpro_panel1',
			'priority' => 100,
		)
	);
	$pmpro_additions->add_section( $section );

	$customize_id = 'register_helper[123][status]';
	$setting = new Register_Helper_Customize_Setting(
		$pmpro_additions, $customize_id, array(
			'transport' => 'postMessage',
		)
	);
	$pmpro_additions->add_setting( $setting );

	$control = new Register_Helper_Customize_Control(
		$pmpro_additions, $customize_id, array(
			'section' => 'static_register_helper_items',
			'label' => 'Status',
			'settings' => array(
				'default' => $customize_id,
			),
		)
	);
	$pmpro_additions->add_control( $control );
}
add_action( 'customize_register', __NAMESPACE__ . '\customize_register' );

/**
 * Enqueue scripts for the customizer pane/controls/previewer.
 */
function customize_controls_enqueue_scripts() {
	$handle = 'register-helper-setting';
	wp_enqueue_script(
		$handle,
		plugin_dir_url( __FILE__ ) . 'register-helper-setting.js',
		array( 'customize-controls' )
	);

	$handle = 'register-helper-control';
	wp_enqueue_script(
		$handle,
		plugin_dir_url( __FILE__ ) . 'register-helper-control.js',
		array( 'customize-controls' )
	);

	$handle = 'register-helper';
	wp_enqueue_script(
		$handle,
		plugin_dir_url( __FILE__ ) . 'register-helper.js',
		array(
			'customize-controls',
			'register-helper-setting',
			'register-helper-control',
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', __NAMESPACE__ . '\customize_controls_enqueue_scripts' );

add_filter(
	'customize_dynamic_setting_args', function( $args, $setting_id ) {
		$pattern = '#^register_helper\[(?P<post_id>\d+)\]\[status\]$#';
		if ( preg_match( $pattern, $setting_id, $matches ) ) {
			$args = array(
				'type' => 'register_helper',
				'post_id' => intval( $matches['post_id'] ),
			);
		}
		return $args;
	}, 10, 2
);

add_filter(
	'customize_dynamic_setting_class', function( $class, $setting_id, $args ) {
		if ( isset( $args['type'] ) && 'register_helper' === $args['type'] ) {
			$class = __NAMESPACE__ . '\Register_Helper_Customize_Setting';
		}
		return $class;
	}, 10, 3
);

<?php

namespace PMPro_Customizer\inc\classes;

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

class PMPro_Customizer {
	public static function init() {
		add_action( 'customize_register', array( __CLASS__, 'engage_the_customizer' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'customizer_enqueue' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'customizer_enqueue' ) );
	}

		/**
		 * Customizer manager demo
		 *
		 * @param  WP_Customizer_Manager $pmpro_manager
		 * @return void
		 */
	public static function engage_the_customizer( $pmpro_manager ) {
		self::pmpro_customizer_manager( $pmpro_manager );
	}

	public static function customizer_enqueue() {
		wp_enqueue_style( 'customizer-section', plugins_url( '../css/customizer-section.css', __FILE__ ) );
	}
	/**
	 * Customizer manager demo
	 *
	 * @param  WP_Customizer_Manager $pmpro_manager
	 * @return void
	 */
	public static function pmpro_customizer_manager( $pmpro_manager ) {
		$pmpro_manager->add_panel(
			'pmpro_customizer_panel', array(
				'priority' => 10,
				'capability' => 'edit_theme_options',
				'theme_supports' => '',
				'title' => __( 'PMPro Admin Panel', 'pmpro-customizer' ),
			)
		);

		$pmpro_manager->add_setting(
			'ce_image_https',
			array(
				'default'        => false,
			)
		);

		$pmpro_manager->add_control(
			new Central_Toggle_Control(
				$pmpro_manager,
				'ce_image_https',
				array(
					'settings'    => 'ce_image_https',
					'label'       => __( 'Cet Image https URL', 'pmpro-customizer' ),
					'title'       => __( 'Cet Image URL', 'pmpro-customizer' ),
					'section'     => 'content_options_section',
					'type'        => 'ios',
					'description' => __( 'Configure advanced settings in %s', __FILE__ , 'pmpro-customizer' ),
				)
			)
		);

		// add "Content Options" section
		$pmpro_manager->add_section(
			'content_options_section' , array(
				'title'      => __( 'Content Options', 'pmpro-customizer' ),
				'priority'   => 100,
				'panel'          => 'pmpro_customizer_panel',
				'description'       => __( 'Configure options for ' . esc_url_raw( home_url() ), 'pmpro-customizer' ),
			)
		);

		// add setting for page comment toggle checkbox
		$pmpro_manager->add_setting(
			'page_comment_toggle', array(
				'default' => 1,
			)
		);

		// add control for page comment toggle checkbox
		$pmpro_manager->add_control(
			'page_comment_toggle', array(
				'label'     => __( 'Show comments on pages?', 'pmpro-customizer' ),
				'section'   => 'content_options_section',
				'priority'  => 10,
				'type'      => 'checkbox',
			)
		);

		$pmpro_manager->add_section(
			'diagnostics_section', array(
				'priority' => 10,
				'capability' => 'edit_theme_options',
				'theme_supports' => '',
				'title' => __( 'Diagnostics Section', 'pmpro-customizer' ),
				'description' => __( '<h4>Turn on helpful diagnostic information.</h4>', 'pmpro-customizer' ),
				'panel' => 'pmpro_customizer_panel',
			)
		);

		$pmpro_manager->add_setting(
			'central_diagnostics',
			array(
				'default'    => false,
			)
		);
		$pmpro_manager->add_control(
			new Central_Toggle_Control(
				$pmpro_manager,
				'central_diagnostics', array(
					'settings'    => 'central_diagnostics',
					'label'       => __( 'Central Diagnostics' ),
					'description' => 'Adds a button in upper right corner of front end pages to toggle diagnostic infomation.',
					'section'     => 'diagnostics_section',
					'type'        => 'ios',
				// 'type'        => 'checkbox',
				)
			)
		);
		// if ( true === get_theme_mod( 'central_diagnostics' ) ) {
			$pmpro_manager->add_setting(
				'diagnostic_type',
				array(
					'capability' => 'edit_theme_options',
					'default'    => 'mapping',
					// 'sanitize_callback' => array(
				// __CLASS__,
				// 'customizer_sanitize_radio',
				// ),
				)
			);
			$pmpro_manager->add_control(
				'diagnostic_type',
				array(
					'type'        => 'radio',
					'section'     => 'diagnostics_section',
					'label'       => __( 'Diagnostic Selection' ),
					'description' => __( 'This is a custom radio input.' ),
					'choices'     => array(
						'current'    => __( 'Current URL' ),
						'mapping'    => __( 'Domain Mapping' ),
						'mods'       => __( 'Theme Mods' ),
					),
				)
			);
		// }
	}

	/**
	 * A section to show how you use the default customizer controls in WordPress
	 *
	 * @param  Obj $pmpro_manager - WP Manager
	 *
	 * @return Void
	 */
	private static function themeslug_sanitize_select( $input, $setting ) {

		// Ensure input is a slug.
		$input = sanitize_key( $input );

		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}

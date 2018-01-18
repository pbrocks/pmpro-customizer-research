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
		self::pmpro_panel( $pmpro_manager );
	}

	public static function customizer_enqueue() {
		wp_enqueue_style( 'customizer-section', plugins_url( '../css/customizer-section.css', __FILE__ ) );
	}


	/**
	 * [engage_customizer description]
	 *
	 * @param [type] $pmpro_manager [description]
	 * @return [type]             [description]
	 */
	private static function pmpro_panel( $pmpro_manager ) {

		$pmpro_manager->add_panel(
			'pmpro_panel1', array(
				'title'       => 'PMPro Customizer 1',
				'description' => 'This is a description of this ' . __FUNCTION__ . ' panel',
				'priority'    => 10,
			)
		);

		self::pmpro_section( $pmpro_manager );
		// self::pmpro_admin_section( $pmpro_manager );
		// self::pmpro_core_footer( $pmpro_manager );
		// self::pmpro_advanced( $pmpro_manager );
		self::some_theme_customizer_options( $pmpro_manager );
	}

	/**
	 * The pmpro_section function adds a new section
	 * to the Customizer to display the settings and
	 * controls that we build.
	 *
	 * @param  [type] $pmpro_manager [description]
	 * @return [type]             [description]
	 */
	private static function pmpro_section( $pmpro_manager ) {
		$pmpro_manager->add_section(
			'pmpro_section', array(
				'title'          => 'PMPro Controls',
				'priority'       => 16,
				'panel'          => 'pmpro_panel1',
				'description' => 'This is a description of this text setting in the PMPro Customizer Controls section of the PMPro panel in <h4>' . __FILE__ . '</h4>',
			)
		);

		$pmpro_manager->add_setting(
			'pmpro[the_header]', array(
				'default' => 'header-text default text',
				'type' => 'option',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$pmpro_manager->add_control(
			'pmpro[the_header]', array(
				'section'   => 'pmpro_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
			'label'       => 'Default Header Text',
			'settings'    => 'pmpro[the_header]',
			'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Header Text',
			)
		);

		$pmpro_manager->add_setting(
			'pmpro[the_footer]', array(
				'default' => 'footer-text default text',
				'type' => 'option',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$pmpro_manager->add_control(
			'pmpro[the_footer]', array(
				'section'   => 'pmpro_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
			'label'       => 'Default Footer Text',
			'settings'    => 'pmpro[the_footer]',
			'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Footer Text',
			)
		);

		/**
		 * Adding a Checkbox Toggle
		 */
		// if ( ! class_exists( 'Customizer_Toggle_Control' ) ) {
		// require_once dirname( __FILE__ ) . '/controls/checkbox/toggle-control.php';
		// }
		/**
		 * Radio control
		 */
		$pmpro_manager->add_setting(
			'menu_radio', array(
				'default'        => '2',
			)
		);

		$pmpro_manager->add_control(
			'menu_radio', array(
				// 'section'     => 'theme_options',
				'section'     => 'pmpro_section',
				'type'        => 'radio',
				'label'       => 'Menu Alignment Radio Buttons',
				'description' => 'Description of this radio setting in ' . __FUNCTION__,
				'choices'     => array(
					'1' => 'left',
					'2' => 'center',
					'3' => 'right',
				),
				'priority'    => 11,
			)
		);

	}
	private static function some_theme_customizer_options( $pmpro_manager ) {
		$pmpro_manager->add_section(
			'pmpro_colors_section', array(
				'title'          => 'PMPro Colors',
				'priority'       => 16,
				'panel'          => 'pmpro_panel1',
				'description' => 'PMPro Colors section of the PMPro panel in <h4>' . __FILE__ . '</h4>',
			)
		);

		$pmpro_manager->add_setting(
			'pmpro_colors[header]', array(
				'default' => 'header-text default text',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$pmpro_manager->add_control(
			'pmpro_colors[header]', array(
				'section'   => 'pmpro_colors_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
			'label'       => 'Default Header Text',
			'settings'    => 'pmpro_colors[header]',
			'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Text',
			)
		);

		// // Estimate secondary color
		// $customize_additions->add_setting(
		// 'pmpro_colors[x_color]', array(
		// 'default'           => '#438cb7',
		// 'sanitize_callback' => 'sanitize_hex_color',
		// 'transport' => 'postMessage',
		// )
		// );
		$pmpro_manager->add_setting(
			'pmpro_colors[x_color]', array(
				'default' => 'footer-text default text',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$pmpro_manager->add_control(
			'pmpro_colors[x_color]', array(
				'section'   => 'pmpro_colors_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
			'label'       => 'Default Footer Text',
			'settings'    => 'pmpro_colors[x_color]',
			'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Text',
			)
		);

		// $customize_additions->add_control(
		// new \WP_Customize_Color_Control(
		// $customize_additions, 'pmpro_colors[x_color]', array(
		// 'label'    => __( 'Estimate Header Background', 'sprout-invoices' ),
		// 'section'  => 'pmpro_colors_section',
		// 'settings' => 'pmpro_colors[x_color]',
		// )
		// )
		// );
		$pmpro_manager->add_setting(
			'pmpro_colors[footer]', array(
				'default' => 'footer-text default text',
				'transport' => 'refresh', // refresh (default), postMessage
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_key'
			)
		);

		$pmpro_manager->add_control(
			'pmpro_colors[footer]', array(
				'section'   => 'pmpro_colors_section',
				'type'   => 'text', // text (default), checkbox, radio, select, dropdown-pages
			'label'       => 'Default Footer Text',
			'settings'    => 'pmpro_colors[footer]',
			'description' => 'Description of this text input setting in ' . __FUNCTION__ . ' for Default Text',
			)
		);
		/**
 *      $wp_customize->add_section(
			'memberlite_theme_options',
			array(
				'title' => __( 'Memberlite Options', 'memberlite' ),
				'priority' => 35,
				'capability' => 'edit_theme_options',
				'description' => __('Allows you to customize settings for Memberlite.', 'memberlite'),
			)
		);
		$wp_customize->add_setting(
			'memberlite_webfonts',
			array(
				'default' => $memberlite_defaults['memberlite_webfonts'],
				'santize_callback' => 'sanitize_text_field',
				'sanitize_js_callback' => array('memberlite_Customize', 'memberlite_sanitize_js_callback'),
			)
		);
		$wp_customize->add_control(
			'memberlite_webfonts',
			array(
				'label' => 'Google Webfonts',
				'section' => 'memberlite_theme_options',
				'type'       => 'select',
				'choices'    => array(
					'Lato_Lato'  => 'Lato',
					'PT-Sans_PT-Serif'  => 'PT Sans and PT Serif',
					'Fjalla-One_Noto-Sans'  => 'Fjalla One and Noto Sans',
					'Pathway-Gothic-One_Source-Sans-Pro' => 'Pathway Gothic One and Source Sans Pro',
					'Oswald_Lato' => 'Oswald and Lato',
					'Ubuntu_Open-Sans' => 'Ubuntu and Open Sans',
					'Lato_Source-Sans-Pro' => 'Lato and Source Sans Pro',
					'Roboto-Slab_Roboto'  => 'Roboto Slab and Roboto',
					'Lato_Merriweather'  => 'Lato and Merriweather',
					'Playfair-Display_Open-Sans'  => 'Playfair Display and Open Sans',
					'Oswald_Quattrocento'  => 'Oswald and Quattrocento',
					'Abril-Fatface_Open-Sans'  => 'Abril Fatface and Open Sans',
					'Open-Sans_Gentium-Book-Basic' => 'Open Sans and Gentium Book Basic',
					'Oswald_PT-Mono' => 'Oswald and PT Mono'
				),
				'priority' => 10
			)
		);
		$wp_customize->add_setting(
			'meta_login',
			array(
				'default' => false,
				'santize_callback' => 'memberlite_sanitize_checkbox',
				'santize_js_callback' => array('memberlite_Customize', 'memberlite_sanitize_js_callback'),
			)
		);
		$wp_customize->add_control(
			'meta_login',
			array(
				'type' => 'checkbox',
				'label' => 'Show Login/Member Info in Header',
				'section' => 'memberlite_theme_options',
				'priority' => '15'
			)
		);
		$wp_customize->add_setting(
			'nav_menu_search',
			array(
				'default' => false,
				'santize_callback' => 'memberlite_sanitize_checkbox',
				'santize_js_callback' => array('memberlite_Customize', 'memberlite_sanitize_js_callback'),
			)
		);
		$wp_customize->add_control(
			'nav_menu_search',
			array(
				'type' => 'checkbox',
				'label' => 'Show Search Form After Main Nav',
				'section' => 'memberlite_theme_options',
				'priority' => '20'
			)
		);
 */

		// // Invoice main color
		// $customize_additions->add_setting(
		// 'ca_inv_primary_color', array(
		// 'default'           => '#4086b0',
		// 'sanitize_callback' => 'sanitize_hex_color',
		// 'transport' => 'postMessage',
		// )
		// );
		// $customize_additions->add_control(
		// new \WP_Customize_Color_Control(
		// $customize_additions, 'ca_inv_primary_color', array(
		// 'label'    => __( 'Invoice Primary Color', 'sprout-invoices' ),
		// 'section'  => 'pmpro_colors_section',
		// 'settings' => 'ca_inv_primary_color',
		// )
		// )
		// );
		// // Invoice secondary color
		// $customize_additions->add_setting(
		// 'ca_inv_secondary_color', array(
		// 'default'           => '#438cb7',
		// 'sanitize_callback' => 'sanitize_hex_color',
		// 'transport' => 'postMessage',
		// )
		// );
		// $customize_additions->add_control(
		// new \WP_Customize_Color_Control(
		// $customize_additions, 'ca_inv_secondary_color', array(
		// 'label'    => __( 'Invoice Header Background', 'sprout-invoices' ),
		// 'section'  => 'pmpro_colors_section',
		// 'settings' => 'ca_inv_secondary_color',
		// )
		// )
		// );
		// // Estimate main color
		// $customize_additions->add_setting(
		// 'ca_est_primary_color', array(
		// 'default'           => '#4086b0',
		// 'sanitize_callback' => 'sanitize_hex_color',
		// 'transport' => 'postMessage',
		// )
		// );
		// $customize_additions->add_control(
		// new \WP_Customize_Color_Control(
		// $customize_additions, 'ca_est_primary_color', array(
		// 'label'    => __( 'Estimate Primary Color', 'sprout-invoices' ),
		// 'section'  => 'pmpro_colors_section',
		// 'settings' => 'ca_est_primary_color',
		// )
		// )
		// );
	}
	public static function sample_page_content( $content ) {
		$page = get_page_by_title( 'Customizer Dev Page' );

		if ( is_page( $page->ID ) ) {
			$content = Dev_Tools::return_something();
		}
		return $content;
	}

	public static function create_customizer_dev_page() {
		$customizer_dev_page = 'Customizer Dev Page';
		$customizer_dev_page_content = Dev_Tools::return_something();
		$author_id = get_current_user();
		$check_page = get_page_by_title( $customizer_dev_page );
		if ( null == $check_page ) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( $customizer_dev_page ),
				// $slug = 'wordpress-post-created-with-code';
				'post_content'  => $customizer_dev_page_content,
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'post_author'   => $author_id,
			// 'post_category' => array( 8,39 ),
			);

			// Insert the post into the database
			wp_insert_post( $my_post );
		}
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

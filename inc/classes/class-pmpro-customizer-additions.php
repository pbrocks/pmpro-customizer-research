<?php

namespace PMPro_Customizer\inc\classes;

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

/**
 * An example of how to write code to PEAR's standards
 *
 * Docblock comments start with "/**" at the top.  Notice how the "/"
 * lines up with the normal indenting and the asterisks on subsequent rows
 * are in line with the first asterisk.  The last line of comment text
 * should be immediately followed on the next line by the closing
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     Original Author <author@example.com>
 */
class PMPro_Customizer_Additions {

	/**
	 * Description
	 *
	 * @return void
	 */
	public static function init() {
		add_shortcode( 'pmpro_member_data', array( __CLASS__, 'pmpro_member_data_shortcode' ) );
		// add_action( 'template_redirect', array( __CLASS__, 'pmpromh_template_redirect_homepage' ) );
		add_filter( 'login_redirect', array( __CLASS__, 'pmpro_multisite_login_redirect' ), 10, 3 );
		// add_filter( 'login_redirect', 'pmpromh_login_redirect', 10, 3 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'customizer_additions_enqueue' ) );
		add_action( 'admin_menu', array( __CLASS__, 'pmpro_quick_dashboard_menu' ) );
	}
	/**
	 * Shortcode to retrieve information about a user startdate/enddate.
	 *
	 * @author Andrew
	 * @return void
	 */
	public static function pmpro_member_data_shortcode( $atts, $content = null, $code = '' ) {

		global $current_user;
		extract(
			shortcode_atts(
				array(
					'field' => null,
				), $atts
			)
		);
		$current_user->membership_level = pmpro_getMembershipLevelForUser( $current_user->ID );
		if ( empty( $current_user->membership_level ) ) {
			return __( 'Not A Member', 'paid-memberships-pro' );
		}
		// get the date.
		$date = $current_user->membership_level->{$field};

		if ( ! empty( $date ) ) {
			return date( 'Y-m-d', $date );
		} else {
			return '';
		}
	}

	/**
	 * Description
	 *
	 * @return string j.
	 */
	public static function wds_force_image_https() {
		if ( true == get_theme_mod( 'wds_force_image_https' ) ) {
			add_filter( 'wp_calculate_image_srcset', array( __CLASS__, 'wds_force_https_in_srcset_images' ) );
			// echo '<h3 style="position:absolute;top: 2rem; right:2rem;z-index:82222;">https Filter ON</h3>';
		}
	}

	/**
	 * Description
	 *
	 * @return string j.
	 */
	public static function fixing_background_images() {
		if ( true === get_theme_mod( 'fix_background_image' ) ) {
			// echo '<h2 style="color:#700;text-align:center;">Booya Background</h2>' . __FILE__;
			add_filter( 'theme_mod_background_image', array( __CLASS__, 'dm_fix_wp_get_attachment_url' ), 99 );
		}
	}

	/**
	 * Description
	 *
	 * @return string j.
	 */
	public static function force_https() {
		if ( stripos( get_option( 'siteurl' ), 'https://' ) === 0 ) {
			 $_SERVER['HTTPS'] = 'on';
		}
	}

	/*
	Function to redirect member on login to their membership level's homepage
	*/
	public static function pmpromh_login_redirect( $redirect_to, $request, $user ) {
		// check level
		if ( ! empty( $user ) && ! empty( $user->ID ) && function_exists( 'pmpro_getMembershipLevelForUser' ) ) {
			$level = pmpro_getMembershipLevelForUser( $user->ID );
			$member_homepage_id = self::pmpromh_getHomepageForLevel( $level->id );

			if ( ! empty( $member_homepage_id ) ) {
				$redirect_to = get_permalink( $member_homepage_id );
			}
		}

		return $redirect_to;
	}

	/*
    Function to redirect member to their membership level's homepage when
	trying to access your site's front page (static page or posts page).
	*/

	public static function pmpromh_template_redirect_homepage() {
		global $current_user;
		// is there a user to check?
		if ( ! empty( $current_user->ID ) && is_front_page() ) {
			$member_homepage_id = self::pmpromh_getHomepageForLevel();
			if ( ! empty( $member_homepage_id ) && ! is_page( $member_homepage_id ) ) {
				wp_redirect( get_permalink( $member_homepage_id ) );
				exit;
			}
		}
	}

	/*
	Function to get a homepage for level
	*/
	public static function pmpromh_getHomepageForLevel( $level_id = null ) {
		if ( empty( $level_id ) && function_exists( 'pmpro_getMembershipLevelForUser' ) ) {
			global $current_user;
			$level = pmpro_getMembershipLevelForUser( $current_user->ID );
			if ( ! empty( $level ) ) {
				$level_id = $level->id;
			}
		}

		// look up by level
		if ( ! empty( $level_id ) ) {
			$member_homepage_id = get_option( 'pmpro_member_homepage_' . $level_id );
		} else {
			$member_homepage_id = false;
		}

		return $member_homepage_id;
	}

	/**
	 * Check if user was previously logged in.
	 * http://wordpress.org/support/topic/97314
	 *
	 * @return   [<description>]
	 */
	public static function redirect_current_user_can( $capability, $current_user ) {
		global $wpdb;

		$roles = get_option( $wpdb->prefix . 'user_roles' );
		$user_roles = $current_user->{$wpdb->prefix . 'capabilities'};
		$user_roles = array_keys( $user_roles, true );
		$role = $user_roles[0];
		$capabilities = $roles[ $role ]['capabilities'];

		if ( in_array( $capability, array_keys( $capabilities, true ) ) ) {
			// check array keys of capabilities for match against requested capability
			return true;
		}
		return false;
	}

	/**
	 * Redirect user after successful login.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param string $request URL the user is coming from.
	 * @param object $user Logged user's data.
	 * @return string
	 */
	public static function pmpro_multisite_login_redirect( $redirect_to, $request, $user ) {
		if ( is_multisite() ) {
			if ( is_super_admin() ) {
				return network_admin_url();
			} else {
				$user_info = get_userdata( $user );
				$redirect_url = $user_info->redirect_url;
				return $redirect_url;
			}
		}
	}

	/**
	 * Forces all srcset images to be HTTPS.
	 *
	 * @author Brad Parbs
	 * @param  array $sources array of sources to use for scrset images.
	 * @return array          modified array of sources with HTTPS, or original array
	 */
	public function wds_force_https_in_srcset_images( $sources ) {

		// Allow user to disable this on local installations or non HTTPS servers.
		$should_filter = apply_filters( 'wds_force_https_in_srcset_images', true );

		// Verify type.
		if ( is_array( $sources ) && $should_filter ) {

			// Set up an a return array.
			$return_sources = [];

			// Loop through each source, and add back to our original return array.
			foreach ( $sources as $key => $value ) {

				// If we got a url, then we want to filter that to be HTTPS.
				if ( isset( $value['url'] ) ) {

					// Actually make the replacement to force https.
					$value['url'] = str_replace( 'http:', 'https:', $value['url'] );

					// Allow for further filtering.
					$value['url'] = apply_filters( 'wds_additional_srcset_filters', $value['url'] );
				}

				// Make sure we actually have some data before saving it back into an array.
				$return_value = isset( $value ) ? $value : array();
				$return_key   = isset( $key ) ? $key : '';

				// Save that so we can return it.
				$return_sources[ $return_key ] = $return_value;
			}

			return $return_sources;
		}

		return $sources;
	}

	/**
	 * Creating the text domain
	 * Will change to pmpro.
	 */
	public static function window_size_scripts() {
		wp_enqueue_script( 'window-size', plugins_url( '../js/window-size.js',  __FILE__ ), array( 'jquery' ) );
	}


	public static function customizer_additions_enqueue() {
		// wp_enqueue_style( 'customizer-additions', plugins_url( '../css/customizer-additions.css', __FILE__ ) );
	}

	public static function pmpro_quick_dashboard_menu() {
		add_dashboard_page( __( 'PMPro Dash', 'pmpro-customizer' ), __( 'PMPro Dash', 'pmpro-customizer' ), 'manage_options', 'pmpro-dashboard-page.php', array( __CLASS__, 'pmpro_quick_dashboard_page' ) );
	}

	/**
	 * Description
	 *
	 * @return type
	 */
	public static function pmpro_quick_dashboard_page() {
		echo '<div class="wrap">';
		echo '<h2>' . __FUNCTION__ . '</h2>';
		$mods = get_theme_mods();
		echo '<pre> get_theme_mods() ';
		print_r( $mods );
		echo '</pre>';

		if ( true === get_theme_mod( 'show_diagnostics' ) ) {
			echo '<div class="pmpro-target"><pre>';
			// var_dump( $var );
			if ( 'mods' === get_theme_mod( 'diagnostic_type' ) ) {
				print_r( $mods );
			} elseif ( 'current' === get_theme_mod( 'diagnostic_type' ) ) {
				echo self::current_kovshenin();
			} else {
				if ( function_exists( 'pmpro_get_authoring_domain' ) ) {
					print_r( $auth_domain );
				} else {
					echo 'No data available from sunrise.php';
				}
			}
			echo '</pre></div>';
			echo '<div class="pmpro-trigger">Diagnostics';
			echo '</div>';
		}
		echo '</div>';
	}
}

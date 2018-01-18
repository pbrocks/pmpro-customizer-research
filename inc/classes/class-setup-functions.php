<?php
namespace PMPro_Customizer\inc\classes;
defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

class Setup_Functions {

	public static function init() {

		add_action( 'wp_dashboard_setup', array( __CLASS__, 'plugins_wp_dashboard_setup' ) );
		add_action( 'wp_network_dashboard_setup', array( __CLASS__, 'plugins_network_dashboard_setup' ) );

	}

	/*
	 * Single Site Dashboard Widget
	 */
	public static function plugins_wp_dashboard_setup() {
		wp_add_dashboard_widget( 'plugins_active_site_plugins', __( 'Active Plugins' ), array( __CLASS__, 'plugins_active_site_plugins' ) );
	}

	public static function plugins_active_site_plugins() {
		$the_plugs = get_option( 'active_plugins' );
		echo '<ul>';
		foreach ( $the_plugs as $key => $value ) {
			$string = explode( '/',$value ); // Folder name will be displayed
			echo '<li>' . $string[0] . '</li>';
		}
		echo '</ul>';
	}


	/*
	 * Multisite Dashboard Widget
	 */
	public static function plugins_network_dashboard_setup() {
		wp_add_dashboard_widget( 'plugins_active_network_plugins', __( 'Network Active Plugins' ), array( __CLASS__, 'plugins_active_network_plugins' ) );
	}

	public static function plugins_active_network_plugins() {
		/*
	     * Network Activated Plugins
	     */
		$the_plugs = get_site_option( 'active_sitewide_plugins' );
		echo '<h3>NETWORK ACTIVATED</h3><ul>';
		foreach ( $the_plugs as $key => $value ) {
			$string = explode( '/',$key ); // Folder name will be displayed
			echo '<li>' . $string[0] . '</li>';
		}
		echo '</ul>';

		/*
	     * Iterate Through All Sites
	     */
		global $wpdb;
		$blogs = $wpdb->get_results(
			$wpdb->prepare(
				"
	        SELECT blog_id
	        FROM {$wpdb->blogs}
	        WHERE site_id = '{$wpdb->siteid}'
	        AND spam = '0'
	        AND deleted = '0'
	        AND archived = '0'
	    "
			)
		);

		echo '<h3>ALL SITES</h3>';

		foreach ( $blogs as $blog ) {
			$the_plugs = get_blog_option( $blog->blog_id, 'active_plugins' );
			echo '<hr /><h4><strong>SITE</strong>: ' . get_blog_option( $blog->blog_id, 'blogname' ) . '</h4>';
			echo '<ul>';
			foreach ( $the_plugs as $key => $value ) {
				$string = explode( '/',$value ); // Folder name will be displayed
				echo '<li>' . $string[0] . '</li>';
			}
			echo '</ul>';
		}
	}

	/**
	 * Adding menus
	 * csc.
	 */
	public static function get_mapped_domains_array() {
		global $wpdb;
		$wpdb->dmtable = $wpdb->base_prefix . 'domain_mapping';
		$mapped_alias = $wpdb->get_results( "SELECT blog_id, domain FROM {$wpdb->dmtable} ORDER BY blog_id" );

		return $mapped_alias;
	}


	public static function get_all_sites() {
		$subsites = get_sites();
		$subsite_info = array();
		foreach ( $subsites as $subsite ) {
			$subsite_id = get_object_vars( $subsite )['blog_id'];
			$subsite_name = get_blog_details( $subsite_id )->blogname;
			$subsite_url = get_blog_details( $subsite_id )->siteurl;
			$subsite_path = get_blog_details( $subsite_id )->path;
			if ( $subsite_name ) {
				$subsite_info[] = $subsite_id . ' | <a href="' . $subsite_url . '" target="_blank">' . $subsite_name . '</a> | <a href="' . $subsite_url . '/wp-admin" target="_blank">' . $subsite_path . '</a> | <a href="' . $subsite_url . '/wp-admin" target="_blank"> Admin URL</a><br>';
			}
		}
		return $subsite_info;
	}

	public static function return_all_sites_info() {
		$subsite_info = self::get_all_sites();
		$mapped_alias = self::get_mapped_domains_array();
		return $subsites;
	}

	public static function get_subsite_number() {
		$subsite = get_current_blog_id();
		return $subsite;
	}

	public static function get_subsite_info() {
		$subsite = get_current_blog_id();
		$subsite_info = get_blog_details( $subsite );
		return $subsite_info;
	}

	public static function plugin_styles() {
		wp_enqueue_style( 'css-styles', plugin_dir_url( __FILE__ ) . 'css/css-file.css' );
	}

	public static function detect_mobile_device() {
		$detect_device = '';
		if ( wp_is_mobile() ) {
			$detect_device = 'mobile';
		} else {
			$detect_device = 'desktop';
		}
		return $detect_device;
	}
}

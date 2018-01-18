<?php
/**
 * Short description for file
 *
 * Long description for file (if any)...

 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */

namespace PMPro_Customizer\inc\classes;

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );


/**
 * WordPress welcomes Campbells
 */
class WordPress_Welcomes_You {
	/**
	 * Add the minimum capabilities necessary for the plugin
	 */
	const WP_CAPABILITY = 'manage_options';

	/**
	 * WordPress_Welcomes_You constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'create_admin_menus' ) );
		add_action( 'admin_init', array( $this, 'welcome' ), 11 );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_notices', array( $this, 'admin_message' ) );
		add_filter( 'login_redirect', array( $this, 'admin_default_page' ) );
	}

	/**
	 * Add the page to the admin area
	 */
	public function create_admin_menus() {
		// add_dashboard_page(
		// 'Core WordPress',
		// 'Core WordPress',
		// self::WP_CAPABILITY,
		// 'wp-csc-welcome-page',
		// array( $this, 'welcome_message' )
		// );
		add_submenu_page( 'connect-to-multisite.php', 'Core WordPress', 'Core WordPress', 'manage_options', 'wp-csc-welcome-page.php',  array( $this, 'welcome_message' ) );
		// Remove the page from the menu
		// remove_submenu_page( 'index.php', 'wp-csc-welcome-page' );
	}

	public function hello_multisite_first() {
		$hello_multisite = '';
		if ( is_multisite() ) {
			$hello_multisite = true;
		} else {
			$hello_multisite = false;
		}
		return $hello_multisite;
	}

	public function hello_multisite() {

		if ( defined( 'SUBDOMAIN_INSTALL' ) || defined( 'VHOST' ) || defined( 'SUNRISE' ) ) {
			return true;
		}

		return 'Looks like this ain\'t multisite';
	}

	/**
	 * Display the plugin welcome message
	 */
	public function welcome_message() {
		echo '<div class="wrap">';
		?>
		<style type="text/css">
			.about-text {
				font-size: 1rem;
			}
			.cmpbl-plugins {
				color: #700;
				font-weight: 700;
			}
			.the-logo, .plugins-image {
				padding: 2rem;
			}
			.plugins-container {
				width: 100%;
			}
			.plugins-left, .plugins-right {
				width: 50%;
				float: left;
			}
			.plugin-notice {
				width: 70%;
			}
		</style>
		<div id="plugin-header">
			<img class="the-logo" src="<?php echo plugins_url( '../images/logo-soups-red.png', __FILE__ ); ?>" alt="Plugin logo" />

			<h1>Welcome to WordPress at Campbell's</h1>

			<p class="about-text">You are here. The function that is running is <span class="cmpbl-plugins"><?php echo __FUNCTION__; ?></span> located in <span class="cmpbl-plugins"><?php echo __CLASS__; ?></span></p>

		</div>
		<div class="plugins-container"><div class="plugins-left">
		<h3 style="text-align: center;">This Multisite's Subsites</h3>
		<h4 style="text-align: center;"><?php echo esc_url( site_url() ); ?></h4>
<?php

		$all_sites = Setup_Functions::get_all_sites();
foreach ( $all_sites as $key => $value ) {
	echo '<h4>Site #' . $value . '</h4>';
}
	?>
		</div><div class="plugins-right">
		<h3 style="text-align: center;">Multisite's Mapped Subsites</h3>
		<pre>
<?php
		$mapped_sites = Setup_Functions::get_mapped_domains_array();
foreach ( $mapped_sites as $key => $value ) {
	echo '<h4>Site #' . $value->blog_id . ' points to <a href="//' . $value->domain . '" target="_blank">' . $value->domain . '</a></h4>';
}
	?>
		</pre></div></div>
		<img class="plugins-image" src="<?php echo plugins_url( '../images/logo-soups-red.png', __FILE__ ); ?>" />
<?php
		echo '</div><!-- .wrap -->';
	}


	/**
	 * Check the plugin activated transient exists if does then redirect
	 */
	public function welcome() {
		if ( ! get_transient( 'wp_welcomes_csc_plugin_activated' ) ) {
			return;
		}

		// Delete the plugin activated transient
		delete_transient( 'wp_welcomes_csc_plugin_activated' );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page' => 'wp-csc-welcome-page',
				), admin_url( 'index.php?page=wp-csc-welcome-page' )
			)
		);
		exit;
	}

	/**
	 *
	 */
	public function admin_message() {
		// if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			$multisite = $this->hello_multisite();
			$subsite = Setup_Functions::get_subsite_number();
		if ( true === $multisite ) {
			$class = 'notice notice-info is-dismissible plugin-notice';
			$message = __( 'You\'re on MultiSite, site #' . $subsite, $class, 'cmpbl-core-wp' );

		} else {
			$class = 'notice notice-error is-dismissible plugin-notice';
			$message = __( 'Irks! An error has occurred.', 'cmpbl-core-wp' );
		}
			// $message = 'Looks like this ain\'t multisite';
			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
		// }
	}

	/**
	 *
	 */
	public function admin_default_page() {
		return esc_url( admin_url() . 'index.php' );
	}

	/**
	 *
	 */
	public function admin_head() {
		// Add custom styling to your page
		?>
		<style type="text/css">
			.the-logo {
				float: right;
				padding: 0 1rem;
			}
		</style>
		<?php
	}
}



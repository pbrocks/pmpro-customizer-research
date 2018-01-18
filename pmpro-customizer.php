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

// inc\classes\Admin_Menus::init();
// inc\classes\Central_Toggle_Control::init();
inc\classes\PMPro_Customizer::init();
// inc\classes\Setup_Functions::init();
// new inc\classes\Central_Toggle_Control();
if ( ! class_exists( 'WordPress_Welcomes_You' ) ) {
	 // new inc\classes\Central_Toggle_Control();
	 new inc\classes\WordPress_Welcomes_You();
}

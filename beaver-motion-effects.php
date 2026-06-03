<?php
/**
 * Plugin Name: Beaver Motion Effects
 * Plugin URI: https://example.com/beaver-motion-effects
 * Description: Adds GSAP-powered motion effects placeholders for Beaver Builder rows, columns, and modules.
 * Version: 0.2.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: LDC
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: beaver-motion-effects
 * Domain Path: /languages
 *
 * @package BeaverMotionEffects
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BME_VERSION', '0.2.0' );
define( 'BME_PLUGIN_FILE', __FILE__ );
define( 'BME_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BME_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BME_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

require_once BME_PLUGIN_DIR . 'includes/classes/class-sanitizer.php';
require_once BME_PLUGIN_DIR . 'includes/classes/class-settings.php';
require_once BME_PLUGIN_DIR . 'includes/classes/class-render.php';
require_once BME_PLUGIN_DIR . 'includes/classes/class-assets.php';
require_once BME_PLUGIN_DIR . 'includes/classes/class-plugin.php';

/**
 * Returns the main plugin instance.
 *
 * @return LDC\BeaverMotionEffects\Plugin
 */
function BME_plugin() {
	return LDC\BeaverMotionEffects\Plugin::instance();
}

BME_plugin()->init();

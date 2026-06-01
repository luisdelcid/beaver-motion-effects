<?php
/**
 * Main plugin coordinator.
 *
 * @package BeaverMotionEffects
 */

namespace LDC\BeaverMotionEffects;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Coordinates plugin services.
 */
final class Plugin {
	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Assets service.
	 *
	 * @var Assets
	 */
	private $assets;

	/**
	 * Settings service.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Render service.
	 *
	 * @var Render
	 */
	private $render;

	/**
	 * Gets the singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->settings = new Settings();
		$this->render   = new Render( $this->settings );
		$this->assets   = new Assets();
	}

	/**
	 * Registers WordPress hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		$this->settings->init();
		$this->render->init();
		$this->assets->init();
	}

	/**
	 * Loads translations when language files are added.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'beaver-motion-effects', false, dirname( BME_PLUGIN_BASENAME ) . '/languages' );
	}
}

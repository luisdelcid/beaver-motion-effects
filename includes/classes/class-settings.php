<?php
/**
 * Beaver Builder settings placeholders.
 *
 * @package BeaverMotionEffects
 */

namespace LDC\BeaverMotionEffects;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Owns motion setting defaults and future Beaver Builder integration.
 */
class Settings {
	/**
	 * Registers hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'bme_motion_defaults', array( $this, 'get_defaults' ) );

		// TODO: Register Beaver Builder row, column, and module settings fields.
		// TODO: Map Beaver Builder field values into data-bme attributes at render time.
	}

	/**
	 * Returns supported motion defaults.
	 *
	 * @return array<string, mixed>
	 */
	public function get_defaults() {
		return array(
			'enabled'  => false,
			'effect'   => 'fade-up',
			'duration' => 0.8,
			'delay'    => 0,
			'ease'     => 'power2.out',
			'once'     => true,
		);
	}

	/**
	 * Sanitizes a full settings array.
	 *
	 * @param array<string, mixed> $settings Raw settings.
	 * @return array<string, mixed>
	 */
	public function sanitize( $settings ) {
		$settings = is_array( $settings ) ? $settings : array();
		$defaults = $this->get_defaults();

		return array(
			'enabled'  => Sanitizer::boolean( $settings['enabled'] ?? $defaults['enabled'] ),
			'effect'   => Sanitizer::choice( $settings['effect'] ?? $defaults['effect'], array( 'fade-up', 'fade-in', 'slide-left', 'slide-right', 'zoom-in' ), $defaults['effect'] ),
			'duration' => Sanitizer::float_range( $settings['duration'] ?? $defaults['duration'], 0.1, 10, $defaults['duration'] ),
			'delay'    => Sanitizer::float_range( $settings['delay'] ?? $defaults['delay'], 0, 10, $defaults['delay'] ),
			'ease'     => Sanitizer::text( $settings['ease'] ?? $defaults['ease'] ),
			'once'     => Sanitizer::boolean( $settings['once'] ?? $defaults['once'] ),
		);
	}
}

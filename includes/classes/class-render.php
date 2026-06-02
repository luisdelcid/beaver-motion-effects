<?php
/**
 * Render helpers for motion attributes.
 *
 * @package BeaverMotionEffects
 */

namespace LDC\BeaverMotionEffects;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prepares safe frontend attributes.
 */
class Render {
	/**
	 * Settings service.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Constructor.
	 *
	 * @param Settings $settings Settings service.
	 */
	public function __construct( Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Registers hooks.
	 *
	 * @return void
	 */
	public function init() {
		// TODO: Hook into Beaver Builder render filters for rows, columns, and modules.
		// Example future targets may include FLBuilder row, column, and module attribute filters.
	}

	/**
	 * Builds escaped data attributes for a motion settings array.
	 *
	 * @param array<string, mixed> $raw_settings Raw motion settings.
	 * @return string
	 */
	public function get_motion_attributes( $raw_settings ) {
		$settings = $this->settings->sanitize( $raw_settings );

		if ( empty( $settings['enabled'] ) ) {
			return '';
		}

		$attributes = array(
			'data-bme'          => 'motion',
			'data-bme-effect'   => $settings['effect'],
			'data-bme-duration' => $settings['duration'],
			'data-bme-delay'    => $settings['delay'],
			'data-bme-ease'     => $settings['ease'],
			'data-bme-once'     => $settings['once'] ? 'true' : 'false',
		);

		return $this->render_attributes( $attributes );
	}

	/**
	 * Escapes and renders HTML attributes.
	 *
	 * @param array<string, mixed> $attributes Attributes.
	 * @return string
	 */
	private function render_attributes( $attributes ) {
		$output = '';

		foreach ( $attributes as $name => $value ) {
			if ( '' === $value || null === $value ) {
				continue;
			}

			$output .= sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( (string) $value ) );
		}

		return $output;
	}
}

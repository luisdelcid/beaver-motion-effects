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
		add_filter( 'fl_builder_row_attributes', array( $this, 'add_node_motion_attributes' ), 20, 2 );
		add_filter( 'fl_builder_column_attributes', array( $this, 'add_node_motion_attributes' ), 20, 2 );
		add_filter( 'fl_builder_module_attributes', array( $this, 'add_node_motion_attributes' ), 20, 2 );
	}

	/**
	 * Adds motion data attributes to Beaver Builder rows, columns, and modules.
	 *
	 * @param array<string, mixed> $attributes Existing HTML attributes.
	 * @param object              $node       Beaver Builder node data.
	 * @return array<string, mixed>
	 */
	public function add_node_motion_attributes( $attributes, $node ) {
		if ( ! is_array( $attributes ) || empty( $node->settings ) ) {
			return $attributes;
		}

		$motion_attributes = $this->get_motion_attribute_array( $this->settings->get_node_motion_settings( $node->settings ) );

		if ( empty( $motion_attributes ) ) {
			return $attributes;
		}

		return array_merge( $attributes, $motion_attributes );
	}

	/**
	 * Builds sanitized data attributes for a motion settings array.
	 *
	 * @param array<string, mixed> $raw_settings Raw motion settings.
	 * @return array<string, string>
	 */
	public function get_motion_attribute_array( $raw_settings ) {
		$settings = $this->settings->sanitize( $raw_settings );

		if ( empty( $settings['enabled'] ) ) {
			return array();
		}

		return array(
			'data-bme'          => 'motion',
			'data-bme-effect'   => $settings['effect'],
			'data-bme-duration' => (string) $settings['duration'],
			'data-bme-delay'    => (string) $settings['delay'],
			'data-bme-ease'     => $settings['ease'],
			'data-bme-once'     => $settings['once'] ? 'true' : 'false',
		);
	}

	/**
	 * Builds escaped data attributes for a motion settings array.
	 *
	 * @param array<string, mixed> $raw_settings Raw motion settings.
	 * @return string
	 */
	public function get_motion_attributes( $raw_settings ) {
		return $this->render_attributes( $this->get_motion_attribute_array( $raw_settings ) );
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

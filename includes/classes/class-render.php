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
		add_filter( 'fl_builder_row_attributes', array( $this, 'add_node_attributes' ), 20, 2 );
		add_filter( 'fl_builder_column_attributes', array( $this, 'add_node_attributes' ), 20, 2 );
		add_filter( 'fl_builder_module_attributes', array( $this, 'add_node_attributes' ), 20, 2 );
	}

	/**
	 * Adds BME data attributes to enabled Beaver Builder nodes.
	 *
	 * @param array<string, mixed> $attributes Existing attributes.
	 * @param object              $node       Beaver Builder node.
	 * @return array<string, mixed>
	 */
	public function add_node_attributes( $attributes, $node ) {
		if ( ! is_array( $attributes ) ) {
			$attributes = array();
		}

		if ( ! is_object( $node ) || empty( $node->settings ) ) {
			return $attributes;
		}

		return array_merge( $attributes, $this->get_motion_attributes_array( $node->settings ) );
	}

	/**
	 * Builds a safe data attribute array for a motion settings array/object.
	 *
	 * @param array<string, mixed>|object $raw_settings Raw motion settings.
	 * @return array<string, string>
	 */
	public function get_motion_attributes_array( $raw_settings ) {
		$settings = $this->settings->sanitize( $raw_settings );

		if ( 'yes' !== $settings['enabled'] ) {
			return array();
		}

		return array(
			'data-bme'                => (string) $settings['preset'],
			'data-bme-duration'       => (string) $settings['duration'],
			'data-bme-delay'          => (string) $settings['delay'],
			'data-bme-ease'           => (string) $settings['ease'],
			'data-bme-start'          => (string) $settings['start'],
			'data-bme-once'           => (string) $settings['once'],
			'data-bme-disable-mobile' => (string) $settings['disable_mobile'],
		);
	}

	/**
	 * Builds escaped data attributes for a motion settings array.
	 *
	 * @param array<string, mixed>|object $raw_settings Raw motion settings.
	 * @return string
	 */
	public function get_motion_attributes( $raw_settings ) {
		return $this->render_attributes( $this->get_motion_attributes_array( $raw_settings ) );
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

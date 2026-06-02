<?php
/**
 * Beaver Builder settings integration.
 *
 * @package BeaverMotionEffects
 */

namespace LDC\BeaverMotionEffects;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Owns motion setting defaults and Beaver Builder form fields.
 */
class Settings {
	/**
	 * Registers hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'bme_motion_defaults', array( $this, 'get_defaults' ) );
		add_filter( 'fl_builder_register_settings_form', array( $this, 'register_settings_form' ), 20, 2 );
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
	 * Adds the Beaver Motion Effects tab to row, column, and module settings forms.
	 *
	 * Beaver Builder passes row and column forms with the IDs `row` and `col`.
	 * Module forms use their module slug as the ID, so we add the tab to any
	 * standard settings form that contains tabs.
	 *
	 * @param array<string, mixed> $form Beaver Builder settings form config.
	 * @param string              $id   Settings form ID.
	 * @return array<string, mixed>
	 */
	public function register_settings_form( $form, $id ) {
		if ( ! is_array( $form ) || empty( $form['tabs'] ) || ! is_array( $form['tabs'] ) ) {
			return $form;
		}

		if ( ! $this->is_supported_form_id( $id ) ) {
			return $form;
		}

		$form['tabs']['bme_motion'] = array(
			'title'    => __( 'Motion', 'beaver-motion-effects' ),
			'sections' => array(
				'bme_motion_effects' => array(
					'title'  => __( 'Beaver Motion Effects', 'beaver-motion-effects' ),
					'fields' => $this->get_form_fields(),
				),
			),
		);

		return $form;
	}

	/**
	 * Determines whether a Beaver Builder settings form should get motion fields.
	 *
	 * @param string $id Settings form ID.
	 * @return bool
	 */
	private function is_supported_form_id( $id ) {
		$id = Sanitizer::key( $id );

		if ( in_array( $id, array( 'global', 'layout', 'settings' ), true ) ) {
			return false;
		}

		/**
		 * Filters whether a Beaver Builder form should receive BME controls.
		 *
		 * @param bool   $is_supported Whether the form is supported.
		 * @param string $id           Settings form ID.
		 */
		return (bool) apply_filters( 'bme_is_supported_settings_form', true, $id );
	}

	/**
	 * Returns Beaver Builder field definitions for motion settings.
	 *
	 * @return array<string, mixed>
	 */
	private function get_form_fields() {
		$defaults = $this->get_defaults();

		return array(
			'bme_enabled'  => array(
				'type'    => 'select',
				'label'   => __( 'Enable motion effect', 'beaver-motion-effects' ),
				'default' => $defaults['enabled'] ? '1' : '0',
				'options' => array(
					'0' => __( 'No', 'beaver-motion-effects' ),
					'1' => __( 'Yes', 'beaver-motion-effects' ),
				),
				'help'    => __( 'Adds data-bme attributes to this Beaver Builder element on the front end.', 'beaver-motion-effects' ),
			),
			'bme_effect'   => array(
				'type'    => 'select',
				'label'   => __( 'Effect', 'beaver-motion-effects' ),
				'default' => $defaults['effect'],
				'options' => $this->get_effect_options(),
			),
			'bme_duration' => array(
				'type'        => 'text',
				'label'       => __( 'Duration', 'beaver-motion-effects' ),
				'default'     => (string) $defaults['duration'],
				'placeholder' => '0.8',
				'help'        => __( 'Animation length in seconds. Values are limited from 0.1 to 10.', 'beaver-motion-effects' ),
			),
			'bme_delay'    => array(
				'type'        => 'text',
				'label'       => __( 'Delay', 'beaver-motion-effects' ),
				'default'     => (string) $defaults['delay'],
				'placeholder' => '0',
				'help'        => __( 'Delay before the animation starts, in seconds. Values are limited from 0 to 10.', 'beaver-motion-effects' ),
			),
			'bme_ease'     => array(
				'type'    => 'select',
				'label'   => __( 'Easing', 'beaver-motion-effects' ),
				'default' => $defaults['ease'],
				'options' => $this->get_ease_options(),
			),
			'bme_once'     => array(
				'type'    => 'select',
				'label'   => __( 'Animate once', 'beaver-motion-effects' ),
				'default' => $defaults['once'] ? '1' : '0',
				'options' => array(
					'1' => __( 'Yes', 'beaver-motion-effects' ),
					'0' => __( 'No', 'beaver-motion-effects' ),
				),
			),
		);
	}

	/**
	 * Returns supported effect labels.
	 *
	 * @return array<string, string>
	 */
	private function get_effect_options() {
		return array(
			'fade-up'     => __( 'Fade Up', 'beaver-motion-effects' ),
			'fade-in'     => __( 'Fade In', 'beaver-motion-effects' ),
			'slide-left'  => __( 'Slide Left', 'beaver-motion-effects' ),
			'slide-right' => __( 'Slide Right', 'beaver-motion-effects' ),
			'zoom-in'     => __( 'Zoom In', 'beaver-motion-effects' ),
		);
	}

	/**
	 * Returns supported GSAP easing labels.
	 *
	 * @return array<string, string>
	 */
	private function get_ease_options() {
		return array(
			'power1.out' => __( 'Power 1 Out', 'beaver-motion-effects' ),
			'power2.out' => __( 'Power 2 Out', 'beaver-motion-effects' ),
			'power3.out' => __( 'Power 3 Out', 'beaver-motion-effects' ),
			'back.out'   => __( 'Back Out', 'beaver-motion-effects' ),
			'none'       => __( 'None', 'beaver-motion-effects' ),
		);
	}

	/**
	 * Gets raw motion settings from Beaver Builder node settings.
	 *
	 * @param object|array<string, mixed> $node_settings Beaver Builder node settings.
	 * @return array<string, mixed>
	 */
	public function get_node_motion_settings( $node_settings ) {
		return array(
			'enabled'  => $this->get_setting_value( $node_settings, 'bme_enabled' ),
			'effect'   => $this->get_setting_value( $node_settings, 'bme_effect' ),
			'duration' => $this->get_setting_value( $node_settings, 'bme_duration' ),
			'delay'    => $this->get_setting_value( $node_settings, 'bme_delay' ),
			'ease'     => $this->get_setting_value( $node_settings, 'bme_ease' ),
			'once'     => $this->get_setting_value( $node_settings, 'bme_once' ),
		);
	}

	/**
	 * Reads a setting value from an object or array.
	 *
	 * @param object|array<string, mixed> $settings Settings container.
	 * @param string                      $key      Setting key.
	 * @return mixed|null
	 */
	private function get_setting_value( $settings, $key ) {
		if ( is_array( $settings ) && array_key_exists( $key, $settings ) ) {
			return $settings[ $key ];
		}

		if ( is_object( $settings ) && isset( $settings->{$key} ) ) {
			return $settings->{$key};
		}

		return null;
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
			'effect'   => Sanitizer::choice( $settings['effect'] ?? $defaults['effect'], array_keys( $this->get_effect_options() ), $defaults['effect'] ),
			'duration' => Sanitizer::float_range( $settings['duration'] ?? $defaults['duration'], 0.1, 10, $defaults['duration'] ),
			'delay'    => Sanitizer::float_range( $settings['delay'] ?? $defaults['delay'], 0, 10, $defaults['delay'] ),
			'ease'     => Sanitizer::choice( $settings['ease'] ?? $defaults['ease'], array_keys( $this->get_ease_options() ), $defaults['ease'] ),
			'once'     => Sanitizer::boolean( $settings['once'] ?? $defaults['once'] ),
		);
	}
}

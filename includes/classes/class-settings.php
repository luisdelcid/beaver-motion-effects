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
		add_filter( 'fl_builder_register_module_settings_form', array( $this, 'register_module_settings_form' ), 20, 2 );
	}

	/**
	 * Returns supported motion defaults.
	 *
	 * @return array<string, mixed>
	 */
	public function get_defaults() {
		return array(
			'enabled'        => 'no',
			'preset'         => 'fade-up',
			'duration'       => 0.8,
			'delay'          => 0,
			'ease'           => 'power2.out',
			'start'          => 'top 85%',
			'once'           => 'yes',
			'disable_mobile' => 'no',
		);
	}

	/**
	 * Adds the Motion tab to Beaver Builder row and column settings forms.
	 *
	 * @param array<string, mixed> $form Settings form configuration.
	 * @param string               $id   Settings form ID.
	 * @return array<string, mixed>
	 */
	public function register_settings_form( $form, $id ) {
		if ( in_array( $id, array( 'row', 'col', 'column' ), true ) ) {
			$form = $this->add_motion_tab( $form );
		}

		return $form;
	}

	/**
	 * Adds the Motion tab to all module settings forms.
	 *
	 * @param array<string, mixed> $form Module form configuration.
	 * @param string               $id   Module slug.
	 * @return array<string, mixed>
	 */
	public function register_module_settings_form( $form, $id ) {
		return $this->add_motion_tab( $form );
	}

	/**
	 * Adds the shared Motion tab to a Beaver Builder form.
	 *
	 * @param array<string, mixed> $form Settings form configuration.
	 * @return array<string, mixed>
	 */
	private function add_motion_tab( $form ) {
		if ( isset( $form['tabs'] ) && is_array( $form['tabs'] ) ) {
			$form['tabs']['bme_motion'] = $this->get_motion_tab();
			return $form;
		}

		$form['bme_motion'] = $this->get_motion_tab();

		return $form;
	}

	/**
	 * Returns the Beaver Builder Motion tab definition.
	 *
	 * @return array<string, mixed>
	 */
	private function get_motion_tab() {
		$defaults = $this->get_defaults();

		return array(
			'title'    => __( 'Motion', 'beaver-motion-effects' ),
			'sections' => array(
				'bme_motion_settings' => array(
					'title'  => __( 'Motion Effects', 'beaver-motion-effects' ),
					'fields' => array(
						'bme_enabled'        => array(
							'type'    => 'select',
							'label'   => __( 'Enable Motion', 'beaver-motion-effects' ),
							'default' => $defaults['enabled'],
							'options' => $this->get_yes_no_options(),
						),
						'bme_preset'         => array(
							'type'    => 'select',
							'label'   => __( 'Preset', 'beaver-motion-effects' ),
							'default' => $defaults['preset'],
							'options' => $this->get_preset_options(),
						),
						'bme_duration'       => array(
							'type'        => 'text',
							'label'       => __( 'Duration', 'beaver-motion-effects' ),
							'default'     => (string) $defaults['duration'],
							'description' => __( 'seconds', 'beaver-motion-effects' ),
						),
						'bme_delay'          => array(
							'type'        => 'text',
							'label'       => __( 'Delay', 'beaver-motion-effects' ),
							'default'     => (string) $defaults['delay'],
							'description' => __( 'seconds', 'beaver-motion-effects' ),
						),
						'bme_ease'           => array(
							'type'    => 'select',
							'label'   => __( 'Ease', 'beaver-motion-effects' ),
							'default' => $defaults['ease'],
							'options' => $this->get_ease_options(),
						),
						'bme_start'          => array(
							'type'    => 'select',
							'label'   => __( 'Trigger Start', 'beaver-motion-effects' ),
							'default' => $defaults['start'],
							'options' => $this->get_start_options(),
						),
						'bme_once'           => array(
							'type'    => 'select',
							'label'   => __( 'Animate Once', 'beaver-motion-effects' ),
							'default' => $defaults['once'],
							'options' => $this->get_yes_no_options(),
						),
						'bme_disable_mobile' => array(
							'type'    => 'select',
							'label'   => __( 'Disable on Mobile', 'beaver-motion-effects' ),
							'default' => $defaults['disable_mobile'],
							'options' => $this->get_yes_no_options(),
						),
					),
				),
			),
		);
	}

	/**
	 * Sanitizes a full settings array.
	 *
	 * @param array<string, mixed>|object $settings Raw settings.
	 * @return array<string, mixed>
	 */
	public function sanitize( $settings ) {
		$settings = is_object( $settings ) ? get_object_vars( $settings ) : $settings;
		$settings = is_array( $settings ) ? $settings : array();
		$defaults = $this->get_defaults();

		return array(
			'enabled'        => Sanitizer::yes_no( $settings['bme_enabled'] ?? $settings['enabled'] ?? $defaults['enabled'] ),
			'preset'         => Sanitizer::choice( $settings['bme_preset'] ?? $settings['preset'] ?? $defaults['preset'], array_keys( $this->get_preset_options() ), $defaults['preset'] ),
			'duration'       => Sanitizer::float_range( $settings['bme_duration'] ?? $settings['duration'] ?? $defaults['duration'], 0, 30, $defaults['duration'] ),
			'delay'          => Sanitizer::float_range( $settings['bme_delay'] ?? $settings['delay'] ?? $defaults['delay'], 0, 30, $defaults['delay'] ),
			'ease'           => Sanitizer::choice( $settings['bme_ease'] ?? $settings['ease'] ?? $defaults['ease'], array_keys( $this->get_ease_options() ), $defaults['ease'] ),
			'start'          => Sanitizer::choice( $settings['bme_start'] ?? $settings['start'] ?? $defaults['start'], array_keys( $this->get_start_options() ), $defaults['start'] ),
			'once'           => Sanitizer::yes_no( $settings['bme_once'] ?? $settings['once'] ?? $defaults['once'] ),
			'disable_mobile' => Sanitizer::yes_no( $settings['bme_disable_mobile'] ?? $settings['disable_mobile'] ?? $defaults['disable_mobile'] ),
		);
	}

	/**
	 * Returns yes/no field options.
	 *
	 * @return array<string, string>
	 */
	private function get_yes_no_options() {
		return array(
			'yes' => __( 'Yes', 'beaver-motion-effects' ),
			'no'  => __( 'No', 'beaver-motion-effects' ),
		);
	}

	/**
	 * Returns supported preset options.
	 *
	 * @return array<string, string>
	 */
	private function get_preset_options() {
		return array(
			'fade-up'    => __( 'Fade Up', 'beaver-motion-effects' ),
			'fade-down'  => __( 'Fade Down', 'beaver-motion-effects' ),
			'fade-left'  => __( 'Fade Left', 'beaver-motion-effects' ),
			'fade-right' => __( 'Fade Right', 'beaver-motion-effects' ),
			'scale-in'   => __( 'Scale In', 'beaver-motion-effects' ),
			'blur-in'    => __( 'Blur In', 'beaver-motion-effects' ),
			'parallax'   => __( 'Parallax', 'beaver-motion-effects' ),
		);
	}

	/**
	 * Returns supported easing options.
	 *
	 * @return array<string, string>
	 */
	private function get_ease_options() {
		return array(
			'power1.out' => 'power1.out',
			'power2.out' => 'power2.out',
			'power3.out' => 'power3.out',
			'power4.out' => 'power4.out',
			'back.out'   => 'back.out',
			'none'       => __( 'None', 'beaver-motion-effects' ),
		);
	}

	/**
	 * Returns supported ScrollTrigger start options.
	 *
	 * @return array<string, string>
	 */
	private function get_start_options() {
		return array(
			'top 90%'    => 'top 90%',
			'top 85%'    => 'top 85%',
			'top 75%'    => 'top 75%',
			'center 80%' => 'center 80%',
		);
	}
}

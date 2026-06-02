<?php
/**
 * Frontend asset registration and enqueueing.
 *
 * @package BeaverMotionEffects
 */

namespace LDC\BeaverMotionEffects;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles frontend scripts and styles.
 */
class Assets {
	/**
	 * Registers hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_frontend_assets' ), 20 );
	}

	/**
	 * Registers frontend assets.
	 *
	 * @return void
	 */
	public function register_assets() {
		$asset_version = defined( 'BME_VERSION' ) ? BME_VERSION : '0.1.0';

		wp_register_script(
			'bme-gsap',
			'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
			array(),
			'3.12.5',
			true
		);

		wp_register_script(
			'bme-scrolltrigger',
			'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
			array( 'bme-gsap' ),
			'3.12.5',
			true
		);

		wp_register_script(
			'bme-frontend',
			BME_PLUGIN_URL . 'assets/js/frontend.js',
			array( 'bme-gsap', 'bme-scrolltrigger' ),
			$asset_version,
			true
		);

		wp_register_style(
			'bme-frontend',
			BME_PLUGIN_URL . 'assets/css/frontend.css',
			array(),
			$asset_version
		);
	}

	/**
	 * Enqueues assets only when Beaver Builder content is likely present.
	 *
	 * @return void
	 */
	public function maybe_enqueue_frontend_assets() {
		if ( ! $this->should_enqueue_frontend_assets() ) {
			return;
		}

		wp_enqueue_script( 'bme-frontend' );
		wp_enqueue_style( 'bme-frontend' );

		wp_localize_script(
			'bme-frontend',
			'BMEFrontend',
			array(
				'attributePrefix' => 'data-bme',
				'isDebug'         => (bool) apply_filters( 'bme_frontend_debug', false ),
			)
		);
	}

	/**
	 * Determines whether frontend assets should load.
	 *
	 * This starts conservatively by loading on singular Beaver Builder content,
	 * during previews/customizer, or when explicit BME data attributes are found.
	 *
	 * @return bool
	 */
	private function should_enqueue_frontend_assets() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
			return false;
		}

		if ( is_customize_preview() || $this->is_beaver_builder_editing() ) {
			return true;
		}

		if ( ! is_singular() ) {
			return (bool) apply_filters( 'bme_enqueue_on_non_singular', false );
		}

		$post_id = get_queried_object_id();

		if ( $post_id && $this->is_beaver_builder_enabled( $post_id ) ) {
			return true;
		}

		$content = $post_id ? get_post_field( 'post_content', $post_id ) : '';

		if ( $content && $this->content_looks_supported( $content ) ) {
			return true;
		}

		return (bool) apply_filters( 'bme_enqueue_frontend_assets', false, $post_id );
	}

	/**
	 * Checks whether Beaver Builder editing is active.
	 *
	 * @return bool
	 */
	private function is_beaver_builder_editing() {
		return class_exists( '\FLBuilderModel' )
			&& method_exists( '\FLBuilderModel', 'is_builder_active' )
			&& \FLBuilderModel::is_builder_active();
	}

	/**
	 * Checks whether a singular item has Beaver Builder enabled.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	private function is_beaver_builder_enabled( $post_id ) {
		return class_exists( '\FLBuilderModel' )
			&& method_exists( '\FLBuilderModel', 'is_builder_enabled' )
			&& \FLBuilderModel::is_builder_enabled( absint( $post_id ) );
	}

	/**
	 * Performs a lightweight content sniff for Beaver Builder or BME markup.
	 *
	 * @param string $content Post content.
	 * @return bool
	 */
	private function content_looks_supported( $content ) {
		$needles = array( 'data-bme', 'fl-builder-content', 'fl-row', 'fl-col', 'fl-module' );

		foreach ( $needles as $needle ) {
			if ( false !== strpos( $content, $needle ) ) {
				return true;
			}
		}

		return false;
	}
}

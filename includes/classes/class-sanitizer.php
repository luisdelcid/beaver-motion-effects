<?php
/**
 * Sanitization helpers.
 *
 * @package BeaverMotionEffects
 */

namespace LDC\BeaverMotionEffects;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provides small reusable sanitizers for settings values.
 */
class Sanitizer {
	/**
	 * Sanitizes text.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function text( $value ) {
		return sanitize_text_field( wp_unslash( (string) $value ) );
	}

	/**
	 * Sanitizes a boolean-like value.
	 *
	 * @param mixed $value Raw value.
	 * @return bool
	 */
	public static function boolean( $value ) {
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Normalizes a yes/no value.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function yes_no( $value ) {
		if ( is_bool( $value ) ) {
			return $value ? 'yes' : 'no';
		}

		$value = strtolower( self::text( $value ) );

		return in_array( $value, array( '1', 'true', 'yes', 'on' ), true ) ? 'yes' : 'no';
	}

	/**
	 * Sanitizes a string against an allow list.
	 *
	 * @param mixed  $value Raw value.
	 * @param string[] $choices Allowed choices.
	 * @param string $fallback Fallback value.
	 * @return string
	 */
	public static function choice( $value, $choices, $fallback ) {
		$value = self::text( $value );

		return in_array( $value, $choices, true ) ? $value : $fallback;
	}

	/**
	 * Sanitizes a float inside a range.
	 *
	 * @param mixed $value Raw value.
	 * @param float $min Minimum value.
	 * @param float $max Maximum value.
	 * @param float $fallback Fallback value.
	 * @return float
	 */
	public static function float_range( $value, $min, $max, $fallback ) {
		if ( is_numeric( $value ) ) {
			$value = (float) $value;
		} elseif ( preg_match( '/-?\d+(?:\.\d+)?/', (string) $value, $matches ) ) {
			$value = (float) $matches[0];
		} else {
			$value = (float) $fallback;
		}

		$value = max( (float) $min, min( (float) $max, $value ) );

		return $value;
	}
}

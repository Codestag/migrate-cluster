<?php
/**
 * Plugin Name: Migrate Cluster
 * Plugin URI: https://codestag.com/
 * Description: Migrate your Cluster theme settings from old framework to Customizer.
 * Author: Codestag
 * Author URI: https://codestag.com
 * Version: 1.0.0
 * Requires PHP: 5.6
 * License: GPLv3
 *
 * @package Migrate_Cluster
 */

// Exit, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Migrate Cluster theme settings.
 */
final class Migrate_Cluster {
	public function __construct() {
		// Grab old theme values.
		$legacy_options = array_filter( get_option( 'stag_framework_values' ) );
		$has_updated    = get_theme_mod( 'has_cluster_migrated' );

		if ( ! $has_updated && $legacy_options && '' !== $legacy_options['settings_updated'] ) {
			$mapped_settings = $this->cluster_mapped_settings();

			foreach ( $legacy_options as $legacy_key => $legacy_value ) {
				if ( array_key_exists( $legacy_key, $mapped_settings ) ) {
					$filtered_value = $this->cluster_filter_legacy_value( $legacy_key, $legacy_value );

					set_theme_mod( $mapped_settings[ $legacy_key ], $filtered_value );
				}
			}

			set_theme_mod( 'has_cluster_migrated', true );
		}
	}

	/**
	 * Map old theme key settings to new.
	 *
	 * @return array
	 */
	function cluster_mapped_settings() {
		$settings = [
			// General Settings.
			'general_text_logo'            => 'general_text_logo',
			'general_custom_logo'          => 'general_custom_logo',
			'general_contact_email'        => 'general_contact_email',
			'general_tracking_code'        => 'general_tracking_code',
			'general_disable_seo_settings' => 'general_disable_seo_settings',
			'general_footer_text'          => 'general_footer_text',

			// Styling Options.
			'style_main_layout'            => 'style_main_layout',
			'style_background_color'       => 'style_background_color',
			'style_accent_color'           => 'style_accent_color',
			'portfolio_background_color'   => 'portfolio_background_color',
			'style_footer_color'           => 'style_footer_color',
			'style_body_font'              => 'font-body',
			'style_heading_font'           => 'font-headers',
			'style_font_script'            => 'google-font-subset',
			'style_custom_css'             => 'custom_css',
			'site_background_color'        => 'site_background_color',

			// Blog Settings.
			'site_background'              => 'site_background',
			'site_title'                   => 'site_title',
			'site_subtitle'                => 'site_subtitle',
			'site_background_opacity'      => 'site_background_opacity',
			'site_slide_duration'          => 'site_slide_duration',
			'site_fade_duration'           => 'site_fade_duration',

			// Portfolio Settings.
			'portfolio_style'              => 'portfolio_style',
			'portfolio_count'              => 'portfolio_count',
			'portfolio_title'              => 'portfolio_title',
			'portfolio_subtitle'           => 'portfolio_subtitle',
			'portfolio_background'         => 'portfolio_background',
			'portfolio_background_opacity' => 'portfolio_background_opacity',
		];

		return $settings;
	}

	/**
	 * Filter/sanitize legacy values before saving into customizer.
	 *
	 * @param string $key The settings ID.
	 * @param string $value The setting value to sanitize.
	 * @return mixed
	 */
	public function cluster_filter_legacy_value( $key, $value ) {
		$filtered_value = '';
		$old_settings   = get_option( 'stag_framework_values' );
		$original_value = $old_settings[ $key ];

		switch ( $key ) {
			case 'style_body_font':
			case 'style_header_font':
				$font           = explode( ':', $value );
				$filtered_value = $font[0];
				break;

			case 'general_disable_seo_settings':
				$filtered_value = ( 'off' === $value ) ? false : true;
				break;

			case 'style_font_script':
				$filtered_value = 13;
				break;

			case 'general_footer_text':
				$filtered_value = stripslashes( $value );
				break;

			default:
				$filtered_value = $value;
				break;
		}

		return $filtered_value;
	}
}

new Migrate_Cluster();

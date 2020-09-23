<?php
/**
 * Shortcode: Skills (Gutenberg support)
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


// Gutenberg Block
//------------------------------------------------------

// Add scripts and styles for the editor
if ( ! function_exists( 'trx_addons_gutenberg_sc_skills_editor_assets' ) ) {
	add_action( 'enqueue_block_editor_assets', 'trx_addons_gutenberg_sc_skills_editor_assets' );
	function trx_addons_gutenberg_sc_skills_editor_assets() {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			// Scripts
			wp_enqueue_script(
				'trx-addons-gutenberg-editor-block-skills',
				trx_addons_get_file_url( TRX_ADDONS_PLUGIN_SHORTCODES . 'skills/gutenberg/skills.gutenberg-editor.js' ),
				array( 'wp-blocks', 'wp-editor', 'wp-i18n', 'wp-element', 'trx_addons-admin' ),
				filemtime( trx_addons_get_file_dir( TRX_ADDONS_PLUGIN_SHORTCODES . 'skills/gutenberg/skills.gutenberg-editor.js' ) ),
				true
			);
		}
	}
}

// Block register
if ( ! function_exists( 'trx_addons_sc_skills_add_in_gutenberg' ) ) {
	add_action( 'init', 'trx_addons_sc_skills_add_in_gutenberg' );
	function trx_addons_sc_skills_add_in_gutenberg() {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			register_block_type(
				'trx-addons/skills', array(
					'attributes'      => array(
						'type'               => array(
							'type'    => 'string',
							'default' => 'counter',
						),
						'cutout'             => array(
							'type'    => 'number',
							'default' => 92,
						),
						'compact'            => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'color'              => array(
							'type'    => 'string',
							'default' => '',
						),
						'back_color'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'border_color'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'max'                => array(
							'type'    => 'number',
							'default' => 100,
						),
						'columns'            => array(
							'type'    => 'number',
							'default' => 1,
						),
						'values'             => array(
							'type'    => 'string',
							'default' => '',
						),
						// Title attributes
						'title_style'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'title_tag'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'title_align'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'title_color'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'title_color2'       => array(
							'type'    => 'string',
							'default' => '',
						),
						'gradient_direction' => array(
							'type'    => 'string',
							'default' => '0',
						),
						'title'              => array(
							'type'    => 'string',
							'default' => esc_html__( 'Skills', 'trx_addons' ),
						),
						'subtitle'           => array(
							'type'    => 'string',
							'default' => '',
						),
						'subtitle_align'     => array(
							'type'    => 'string',
							'default' => 'none',
						),
						'subtitle_position'  => array(
							'type'    => 'string',
							'default' => trx_addons_get_setting('subtitle_above_title') ? 'above' : 'below',
						),
						'description'        => array(
							'type'    => 'string',
							'default' => '',
						),
						// Button attributes
						'link'               => array(
							'type'    => 'string',
							'default' => '',
						),
						'link_text'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'link_style'         => array(
							'type'    => 'string',
							'default' => '',
						),
						'link_image'         => array(
							'type'    => 'number',
							'default' => 0,
						),
						'link_image_url'     => array(
							'type'    => 'string',
							'default' => '',
						),
						// ID, Class, CSS attributes
						'id'                 => array(
							'type'    => 'string',
							'default' => '',
						),
						'class'              => array(
							'type'    => 'string',
							'default' => '',
						),
						'className'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'className'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'css'                => array(
							'type'    => 'string',
							'default' => '',
						),
						// Rerender
						'reload'             => array(
							'type'    => 'string',
							'default' => '',
						),
					),
					'render_callback' => 'trx_addons_gutenberg_sc_skills_render_block',
				)
			);
		}
	}
}

// Block render
if ( ! function_exists( 'trx_addons_gutenberg_sc_skills_render_block' ) ) {
	function trx_addons_gutenberg_sc_skills_render_block( $attributes = array() ) {
		if ( ! empty( $attributes['values'] ) ) {
			$attributes['values'] = json_decode( $attributes['values'], true );
			return trx_addons_sc_skills( $attributes );
		} else {
			return esc_html__( 'Add at least one item', 'trx_addons' );
		}
	}
}

// Return list of allowed layouts
if ( ! function_exists( 'trx_addons_gutenberg_sc_skills_get_layouts' ) ) {
	add_filter( 'trx_addons_filter_gutenberg_sc_layouts', 'trx_addons_gutenberg_sc_skills_get_layouts', 10, 1 );
	function trx_addons_gutenberg_sc_skills_get_layouts( $array = array() ) {
		$array['sc_skills'] = apply_filters( 'trx_addons_sc_type', trx_addons_components_get_allowed_layouts( 'sc', 'skills' ), 'trx_sc_skills' );
		return $array;
	}
}

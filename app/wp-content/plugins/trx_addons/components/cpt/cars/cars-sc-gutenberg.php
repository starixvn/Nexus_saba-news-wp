<?php
/**
 * ThemeREX Addons Custom post type: Cars (Gutenberg support)
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.25
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


// Gutenberg Block
//------------------------------------------------------

// Add scripts and styles for the editor
if ( ! function_exists( 'trx_addons_gutenberg_sc_cars_editor_assets' ) ) {
	add_action( 'enqueue_block_editor_assets', 'trx_addons_gutenberg_sc_cars_editor_assets' );
	function trx_addons_gutenberg_sc_cars_editor_assets() {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			// Scripts
			wp_enqueue_script(
				'trx-addons-gutenberg-editor-block-cars',
				trx_addons_get_file_url( TRX_ADDONS_PLUGIN_CPT . 'cars/gutenberg/cars.gutenberg-editor.js' ),
				array( 'wp-blocks', 'wp-editor', 'wp-i18n', 'wp-element', 'trx_addons-admin' ),
				filemtime( trx_addons_get_file_dir( TRX_ADDONS_PLUGIN_CPT . 'cars/gutenberg/cars.gutenberg-editor.js' ) ),
				true
			);
		}
	}
}

// Block register
if ( ! function_exists( 'trx_addons_sc_cars_add_in_gutenberg' ) ) {
	add_action( 'init', 'trx_addons_sc_cars_add_in_gutenberg' );
	function trx_addons_sc_cars_add_in_gutenberg() {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			register_block_type(
				'trx-addons/cars', array(
					'attributes'      => array(
						'type'               => array(
							'type'    => 'string',
							'default' => 'default',
						),
						'pagination'         => array(
							'type'    => 'string',
							'default' => 'none',
						),
						'more_text'          => array(
							'type'    => 'string',
							'default' => esc_html__( 'Read more' ),
						),
						'cars_type'          => array(
							'type'    => 'string',
							'default' => '0',
						),
						'cars_maker'         => array(
							'type'    => 'string',
							'default' => '0',
						),
						'cars_model'         => array(
							'type'    => 'string',
							'default' => '0',
						),
						'cars_status'        => array(
							'type'    => 'string',
							'default' => '0',
						),
						'cars_labels'        => array(
							'type'    => 'string',
							'default' => '0',
						),
						'cars_city'          => array(
							'type'    => 'string',
							'default' => '0',
						),
						'cars_transmission'  => array(
							'type'    => 'string',
							'default' => '',
						),
						'cars_type_drive'    => array(
							'type'    => 'string',
							'default' => '',
						),
						'cars_fuel'          => array(
							'type'    => 'string',
							'default' => '',
						),
						// Query attributes
						'ids'                => array(
							'type'    => 'string',
							'default' => '',
						),
						'count'              => array(
							'type'    => 'number',
							'default' => 2,
						),
						'columns'            => array(
							'type'    => 'number',
							'default' => 2,
						),
						'offset'             => array(
							'type'    => 'number',
							'default' => 0,
						),
						'orderby'            => array(
							'type'    => 'string',
							'default' => 'none',
						),
						'order'              => array(
							'type'    => 'string',
							'default' => 'asc',
						),
						// Slider attributes
						'slider'             => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'slides_space'       => array(
							'type'    => 'number',
							'default' => 0,
						),
						'slides_centered'    => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'slides_overflow'    => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'slider_mouse_wheel' => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'slider_autoplay'    => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'slider_controls'    => array(
							'type'    => 'string',
							'default' => 'none',
						),
						'slider_pagination'  => array(
							'type'    => 'string',
							'default' => 'none',
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
							'default' => esc_html__( 'Cars', 'trx_addons' ),
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
						'css'                => array(
							'type'    => 'string',
							'default' => '',
						),
					),
					'render_callback' => 'trx_addons_gutenberg_sc_cars_render_block',
				)
			);
		}
	}
}

// Block render
if ( ! function_exists( 'trx_addons_gutenberg_sc_cars_render_block' ) ) {
	function trx_addons_gutenberg_sc_cars_render_block( $attributes = array() ) {
		return trx_addons_sc_cars( $attributes );
	}
}

// Return list of allowed layouts
if ( ! function_exists( 'trx_addons_gutenberg_sc_cars_get_layouts' ) ) {
	add_filter( 'trx_addons_filter_gutenberg_sc_layouts', 'trx_addons_gutenberg_sc_cars_get_layouts', 10, 1 );
	function trx_addons_gutenberg_sc_cars_get_layouts( $array = array() ) {
		$array['trx_sc_cars'] = apply_filters( 'trx_addons_sc_type', trx_addons_components_get_allowed_layouts( 'cpt', 'cars', 'sc' ), 'trx_sc_cars' );

		return $array;
	}
}

// Add shortcode's specific vars to the JS storage
if ( ! function_exists( 'trx_addons_gutenberg_sc_cars_params' ) ) {
	add_filter( 'trx_addons_filter_gutenberg_sc_params', 'trx_addons_gutenberg_sc_cars_params' );
	function trx_addons_gutenberg_sc_cars_params( $vars = array() ) {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			// Cars type
			$vars['sc_cars_type']    = trx_addons_get_list_terms( false, TRX_ADDONS_CPT_CARS_TAXONOMY_TYPE );
			$vars['sc_cars_type'][0] = esc_html__( '- Type -', 'trx_addons' );

			// Cars maker
			$vars['sc_cars_maker']    = trx_addons_get_list_terms( false, TRX_ADDONS_CPT_CARS_TAXONOMY_MAKER );
			$vars['sc_cars_maker'][0] = esc_html__( '- Manufacturer -', 'trx_addons' );

			// Cars maker
			$vars['sc_cars_model']    = trx_addons_get_list_terms( false, TRX_ADDONS_CPT_CARS_TAXONOMY_MODEL );
			$vars['sc_cars_model'][0] = esc_html__( '- Model -', 'trx_addons' );

			// Cars status
			$vars['sc_cars_status']    = trx_addons_get_list_terms( false, TRX_ADDONS_CPT_CARS_TAXONOMY_STATUS );
			$vars['sc_cars_status'][0] = esc_html__( '- Status -', 'trx_addons' );

			// Cars labels
			$vars['sc_cars_labels']    = trx_addons_get_list_terms( false, TRX_ADDONS_CPT_CARS_TAXONOMY_LABELS );
			$vars['sc_cars_labels'][0] = esc_html__( '- Label -', 'trx_addons' );

			// Cars city
			$vars['sc_cars_city']    = trx_addons_get_list_terms( false, TRX_ADDONS_CPT_CARS_TAXONOMY_CITY );
			$vars['sc_cars_city'][0] = esc_html__( '- City -', 'trx_addons' );

			// Cars transmission
			$vars['sc_cars_transmission']    = trx_addons_cpt_cars_get_list_transmission();
			$vars['sc_cars_transmission'][0] = esc_html__( '- Transmission -', 'trx_addons' );

			// Cars type of drive
			$vars['sc_cars_type_drive']    = trx_addons_cpt_cars_get_list_type_of_drive();
			$vars['sc_cars_type_drive'][0] = esc_html__( '- Type drive -', 'trx_addons' );

			// Cars fuel
			$vars['sc_cars_fuel']    = trx_addons_cpt_cars_get_list_fuel();
			$vars['sc_cars_fuel'][0] = esc_html__( '- Fuel -', 'trx_addons' );

			return $vars;
		}
	}
}

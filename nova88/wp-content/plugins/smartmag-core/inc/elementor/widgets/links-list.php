<?php

namespace Bunyad\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

/**
 * Lists with Links for Elementor.
 * 
 * Mostly based on Elementor icon list widget.
 */
class LinksList extends Widget_Base
{
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'ts-links-list';
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-bullet-list';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('Links List', 'bunyad-admin');
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords()
	{
		return ['links list', 'links', 'list'];
	}

	/**
	 * Register list widget controls.
	 */
	protected function register_controls()
	{
		$this->start_controls_section(
			'section_links_list',
			[
				'label' => esc_html__('Links List', 'elementor'),
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => esc_html__('Layout', 'elementor'),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'inline',
				'options' => [
					'vertical' => [
						'title' => esc_html__('Default', 'elementor'),
						'icon'  => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => esc_html__('Inline', 'elementor'),
						'icon'  => 'eicon-ellipsis-h',
					],
				],
				'render_type'    => 'template',
				'classes'        => 'elementor-control-start-end',
				'style_transfer' => true,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			[
				'label'       => esc_html__('Text', 'elementor'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__('List Item', 'elementor'),
				'default'     => esc_html__('List Item', 'elementor'),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'   => esc_html__('Link', 'elementor'),
				'type'    => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__('https://your-link.com', 'elementor'),
			]
		);

		$this->add_control(
			'links_list',
			[
				'label'   => esc_html__('Items', 'elementor'),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'text'          => esc_html__('List Item #1', 'elementor'),
					],
					[
						'text'          => esc_html__('List Item #2', 'elementor'),
					],
					[
						'text'          => esc_html__('List Item #3', 'elementor'),
					],
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->add_control(
			'link_click',
			[
				'label'   => esc_html__('Apply Link On', 'elementor'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'full' => esc_html__('Full Width', 'elementor'),
					'inline'     => esc_html__('Inline', 'elementor'),
				],
				'default'      => 'full',
				'separator'    => 'before',
				'prefix_class' => 'ts-el-list-links-',
				'condition'    => ['view' => 'vertical']
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_list',
			[
				'label' => esc_html__('List', 'elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => esc_html__('Space Between', 'elementor'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ts-el-list' => '--spacing: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => esc_html__('Alignment', 'elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'elementor'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'elementor'),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'elementor'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'left'  => 'justify-content: flex-start; text-align: left;',
					'center' => 'justify-content: center; text-align: center;',
					'right' => 'justify-content: flex-end; text-align: right;',
				],
				'selectors' => [
					'{{WRAPPER}} .ts-el-list' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider',
			[
				'label'     => esc_html__('Divider', 'elementor'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Off', 'elementor'),
				'label_on'  => esc_html__('On', 'elementor'),
				'selectors' => [
					'{{WRAPPER}} .ts-el-list' => '--sep-weight: 1px;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label'   => esc_html__('Style', 'elementor'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'solid'  => esc_html__('Solid', 'elementor'),
					'double' => esc_html__('Double', 'elementor'),
					'dotted' => esc_html__('Dotted', 'elementor'),
					'dashed' => esc_html__('Dashed', 'elementor'),
				],
				'default'   => 'solid',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .ts-el-list' => '--sep-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label'   => esc_html__('Weight', 'elementor'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .ts-el-list ' => '--sep-weight: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label'      => esc_html__('Height', 'elementor'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['%', 'px'],
				'default'    => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view'    => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .ts-el-list' => '--sep-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'   => esc_html__('Color', 'elementor'),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
				'condition' => ['divider' => 'yes',],
				'selectors' => [
					'{{WRAPPER}} .ts-el-list' => '--c-sep: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'divider_color_sd',
			[
				'label'   => esc_html__('Dark: Color', 'bunyad-admin'),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'.s-dark {{WRAPPER}} .ts-el-list' => '--c-sep: {{VALUE}}',
				],
				'condition' => ['divider' => 'yes', 'divider_color!' => ''],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__('Text', 'elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__('Text Color', 'elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .ts-el-list .item' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ts-el-list a' => 'color: inherit;',
				],
			]
		);
		$this->add_control(
			'text_color_sd',
			[
				'label'     => esc_html__('Dark: Color', 'bunyad-admin'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'.s-dark {{WRAPPER}} .ts-el-list .item' => 'color: {{VALUE}};',
					'.s-dark {{WRAPPER}} .ts-el-list a' => 'color: inherit;',
				],
				'condition' => ['text_color!' => '']
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label'     => esc_html__('Hover', 'elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .ts-el-list .item a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'text_color_hover_sd',
			[
				'label'     => esc_html__('Dark: Color', 'elementor'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'.s-dark {{WRAPPER}} .ts-el-list .item a:hover' => 'color: {{VALUE}};',
				],
				'condition' => ['text_color_hover!' => '']
			]
		);

		$this->add_control(
			'text_indent',
			[
				'label' => esc_html__('Text Indent', 'elementor'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ts-el-list .item span' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'item_typography',
				'selector' => '{{WRAPPER}} .ts-el-list .item',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute('links_list', 'class', 'ts-el-list ts-el-list-' . $settings['view']);
		$this->add_render_attribute('list_item', 'class', 'item');
	?>
	
		<ul <?php $this->print_render_attribute_string('links_list'); ?>>
			<?php
			foreach ($settings['links_list'] as $index => $item) :
				$repeater_setting_key = $this->get_repeater_setting_key('text', 'links_list', $index);

				$this->add_inline_editing_attributes($repeater_setting_key);

			?>
				<li <?php $this->print_render_attribute_string('list_item'); ?>>
					<?php
					if (!empty($item['link']['url'])) {
						$link_key = 'link_' . $index;
						$this->add_link_attributes($link_key, $item['link']);
					?>
						<a <?php $this->print_render_attribute_string($link_key); ?>>
					<?php
					}
					?>

					<span <?php $this->print_render_attribute_string($repeater_setting_key); ?>><?php $this->print_unescaped_setting('text', 'links_list', $index); ?></span>
					
					<?php if (!empty($item['link']['url'])): ?>
						</a>
					<?php endif; ?>
				</li>
			<?php
			endforeach;
			?>
		</ul>

	<?php
	}

	/**
	 * Render list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function content_template()
	{
		?>
		<#
			view.addRenderAttribute( 'links_list', 'class', 'ts-el-list ts-el-list-' + settings.view );
			view.addRenderAttribute( 'list_item', 'class', 'item' );
		#>
		<# if ( settings.links_list ) { #>
			<ul {{{ view.getRenderAttributeString( 'links_list' ) }}}>
			<# _.each( settings.links_list, function( item, index ) {

					var textKey = view.getRepeaterSettingKey( 'text', 'links_list', index );
					view.addInlineEditingAttributes( textKey ); 
			#>

					<li {{{ view.getRenderAttributeString( 'list_item' ) }}}>
						<# if ( item.link && item.link.url ) { #>
							<a href="{{ item.link.url }}">
						<# } #>
						<span {{{ view.getRenderAttributeString( textKey ) }}}>{{{ item.text }}}</span>
						<# if ( item.link && item.link.url ) { #>
							</a>
						<# } #>
					</li>
				<#
				} ); #>
			</ul>
		<#	} #>

		<?php
	}
}
<?php
/**
 * The template to display the main menu
 *
 * @package WordPress
 * @subpackage FCUNITED
 * @since FCUNITED 1.0
 */
?>
<div class="top_panel_navi sc_layouts_row sc_layouts_row_type_compact sc_layouts_row_fixed sc_layouts_row_fixed_always sc_layouts_row_delimiter
	<?php
	if ( fcunited_is_on( fcunited_get_theme_option( 'header_mobile_enabled' ) ) ) {
		?>
		sc_layouts_hide_on_mobile
		<?php
	}
	?>
">
	<div class="content_wrap">
		<div class="columns_wrap columns_fluid">
			<div class="sc_layouts_column sc_layouts_column_align_left sc_layouts_column_icons_position_left sc_layouts_column_fluid column-1_4">
				<div class="sc_layouts_item">
					<?php
					// Logo
					get_template_part( apply_filters( 'fcunited_filter_get_template_part', 'templates/header-logo' ) );
					?>
				</div>
			</div><div class="sc_layouts_column sc_layouts_column_align_right sc_layouts_column_icons_position_left sc_layouts_column_fluid column-3_4">
				<div class="sc_layouts_item">
					<?php
					// Main menu
					$fcunited_menu_main = fcunited_get_nav_menu( 'menu_main' );
					// Show any menu if no menu selected in the location 'menu_main'
					if ( fcunited_get_theme_setting( 'autoselect_menu' ) && empty( $fcunited_menu_main ) ) {
						$fcunited_menu_main = fcunited_get_nav_menu();
					}
					fcunited_show_layout(
						$fcunited_menu_main,
						'<nav class="menu_main_nav_area sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile"'
							. ' itemscope itemtype="//schema.org/SiteNavigationElement"'
							. '>',
						'</nav>'
					);
					// Mobile menu button
					?>
					<div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button">
						<a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#">
							<span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
						</a>
					</div>
				</div>
				<?php
				if ( fcunited_exists_trx_addons() ) {
					?>
					<div class="sc_layouts_item">
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="<?php echo home_url()?>" style="position: relative;">
								<input type="hidden" value="" name="post_types">
								<input style="height: 40px;font-size: 14px; font-weight: 400;text-transform: lowercase;padding: 0 60px 0 21px !important;width: 18em;line-height: 50px;border: none;color: #b7b7b7;background-color: #262F3E;" type="text" class="search_field fill_inited" placeholder="Tìm kiếm..." value="" name="s">
								<button style="background-color: transparent;
    											color: #ffffff;
											   display: block;
												font-size: 13px;
												text-align: center;
												width: 60px;
												height: 50px;
												line-height: 1em;
												position: absolute;
												top: -5px;
												bottom: auto;
												left: auto;
												right: -10px;
											   " 
								type="submit" class="search_submit trx_addons_icon-search"></button>
							</form>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div><!-- /.columns_wrap -->
	</div><!-- /.content_wrap -->
</div><!-- /.top_panel_navi -->

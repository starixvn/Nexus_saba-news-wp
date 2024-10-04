<script type="text/template" id="tmpl-ts-el-studio-header">
<div class="elementor-templates-modal__header ts-el-studio__header">

	<div class="ts-el-studio__brand">
		Smart Studio
	</div>

	<div class="elementor-templates-modal__header__menu-area">
		<div class="ts-el-studio__header__tab elementor-component-tab elementor-template-library-menu-item <# if (data.activeTab === 'blocks') { #> elementor-active <# } #>" data-tab="blocks">Blocks & Sections</div>
		<div class="ts-el-studio__header__tab elementor-component-tab elementor-template-library-menu-item <# if (data.activeTab === 'pages') { #> elementor-active <# } #>" data-tab="pages">Homepages & Layouts</div>
	</div>
	
	<div class="elementor-templates-modal__header__items-area">
		<div class="elementor-templates-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item" title="<?php esc_html_e('Close', 'bunyad-admin'); ?>">
			<i class="eicon-close" title="Close"></i>
		</div>
	</div>
</div>
</script>

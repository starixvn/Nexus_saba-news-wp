<script type="text/template" id="tmpl-ts-el-studio-pages">
	<div class="ts-el-studio__listing">
		<div class="ts-el-studio__filters">
			<# 
			for (const key in data.filters) { 
				const label = data.filters[key];
			#>

				<a class="ts-el-studio__filters__item" data-filter="{{ key }}">{{ label }}</a>

			<# } #>

		</div>

		<div class="ts-el-studio__items"></div>
	</div>
</script>

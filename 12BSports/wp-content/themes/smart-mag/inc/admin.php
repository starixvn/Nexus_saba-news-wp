<?php
/**
 * General Admin functionality - hooks, methods.
 *  
 * This file serves to be the functions.php for admin functionality. Any
 * non-specific functionality is contained here.
 * 
 * Also see admin/ folder in the root.
 *
 */
class Bunyad_Theme_Admin
{
	public function __construct()
	{
		// Setup plugins before init
		$this->setup_plugins();

		add_action('bunyad_theme_init', [$this, 'init']);

		/**
		 * Include relevant admin files
		 */
		
		// Dashboard, importer and editor
		include get_template_directory() . '/inc/admin/dashboard.php';
		include get_template_directory() . '/inc/admin/import.php';
		include get_template_directory() . '/inc/admin/editor.php';

		// Admin notices.
		Bunyad::register('admin_notices', [
			'class' => '\Bunyad\Core\AdminNotices\Module',
			'init'  => true
		]);

		// Migrations / updates.
		require_once get_theme_file_path('inc/admin/migrations.php');

		// Packaged plugins updates: Only if Sphere Core is inactive or older than 1.4.
		if (
			!class_exists('\Sphere\Core\Plugin', false) 
			|| version_compare(\Sphere\Core\Plugin::VERSION, '1.4.0', '<')
		) {
			include get_template_directory() . '/inc/admin/plugins-update.php';
		}

		// Theme Activation / Update Hooks
		add_action('after_switch_theme', [$this, 'first_setup']);

		// Since 5.8+, block widgets can cause issues.
		add_action('after_switch_theme', [$this, 'disable_unsupported_widgets']);

		// Special case for v5 migrations. At correct hook.
		add_action('admin_init', function() {
			// Note: This is a required notice.
			if (get_option('smartmag_convert_from_v3')) {
				add_action('admin_notices', [$this, 'notice_must_convert_v5']);
			}
		}, 11);
	}
	
	public function init()
	{
		// Register the legacy plugins. Options aren't init yet in original setup_plugins().
		if (Bunyad::options()->legacy_mode && function_exists('tgmpa')) {
			$plugins = [[
				'name'     	=> '(Legacy) Bunyad Shortcodes', // The plugin name
				'slug'     	=> 'bunyad-shortcodes', // The plugin slug (typically the folder name)
				'source'   	=> get_template_directory() . '/lib/vendor/plugins/bunyad-shortcodes.zip', // The plugin source
				'required' 	=> false,
				'optional'  => true,
				'version'   => '1.1.0'
			]];

			tgmpa($plugins);
		}

		$this->editor_styles();
	}
	
	/**
	 * Setup and recommend plugins
	 */
	public function setup_plugins()
	{
		// Note: packaged_plugins below is only used on admin_init, so safe to ignore on non-admin.
		if (!is_admin()) {
			return;
		}
		
		$plugins = include get_template_directory() . '/inc/admin/theme-plugins.php';

		// Set for update checking.
		Bunyad::registry()->set('packaged_plugins', $plugins);

		// Only load and register if in admin (checked above), or if user has permissions.
		if (current_user_can('install_plugins')) {
			// Load the plugin activation class and our enhancements.
			require_once get_template_directory() . '/lib/vendor/class-tgm-plugin-activation.php';
			require_once get_template_directory() . '/inc/admin/dash-plugins.php';

			tgmpa($plugins, [
				'parent_slug' => 'sphere-dash',
				'id'          => 'smartmag_tgmpa'
			]);
		}
	}

	/**
	 * Add editor styles.
	 *
	 * @return void
	 */
	public function editor_styles()
	{

		// Add editor styles
		$styles = [get_stylesheet_uri()];
		$skin   = Bunyad::get('theme')->get_style();
		
		// Add skin css second
		if (isset($skin['css'])) {
			array_push($styles, get_template_directory_uri() . '/css/' . $skin['css'] . '.css');
		}
		
		$styles = array_merge($styles, [
			get_template_directory_uri() . '/css/admin/editor-style.css',
			Bunyad::get('theme')->get_fonts_enqueue()
		]);

		if (!empty($skin['local_fonts'])) {
			foreach ((array) $skin['local_fonts'] as $font) {
				$styles[] = get_theme_file_uri('css/fonts/' . $font . '.css');
			}
		}

		add_editor_style($styles);
	}

	/**
	 * Admin notice for the compulsory migration tool for v5.0 to prevent fatal errors 
	 * and missing data.
	 */
	public function notice_must_convert_v5()
	{
		?>
		<div class="notice error">
			<h2>SmartMag Data Conversion Required!</h2>
			<p>Since v5+ was a rewrite and a lot of data has changed, the converter tool has to run.</p>

		<?php if (!class_exists('SmartMag_Core')): ?>
			<p>
				<strong>SmartMag Core</strong> plugin is required to convert data from old SmartMag to the new one. Please install it 
				from SmartMag > Install Plugins.
			</p>
			<p>Once installed, convert from SmartMag > Covert to v5.</p>

		<?php elseif (!\SmartMag_Core::instance()->did_init): ?>

			<p>SmartMag Core is activated but is conflicting with another plugin. Please disable the conflicting plugins such as Bunyad Widgets or another theme's Core plugin.</p>

		<?php else: ?>
			<p>
				Please run the conversion tool from 
				<a href="<?php echo esc_url(admin_url('admin.php?page=sphere-dash-convert-v5')); ?>">SmartMag > Convert to v5</a>.

			</p>
		<?php endif; ?>
			<p>
		</div>
		<?php 
	}

	/**
	 * Deactivate unsupported block widgets.
	 */
	public function disable_unsupported_widgets()
	{
		// Not a first install. Widgets were previously set. Don't have to do anything.
		if (get_theme_mod('sidebars_widgets')) {
			return;
		}

		$sidebar_widgets = get_option('sidebars_widgets', []);
		if (empty($sidebar_widgets)) {
			return;
		}

		foreach ($sidebar_widgets as $sidebar => $widgets) {
			foreach ($widgets as $key => $id) {

				// Move to inactive.
				if (substr($id, 0, 6) === 'block-') {
					$sidebar_widgets['wp_inactive_widgets'][] = $id;
					unset($sidebar_widgets[$sidebar][$key]);
				}
			}
		}

		update_option('sidebars_widgets', $sidebar_widgets);
	}

	/**
	 * Setup elementor configs for better compatibility, on theme activation.
	 */
	public function first_setup()
	{
		// update_option('elementor_container_width', '1200');
		// update_option('elementor_page_title_selector', '.the-page-heading');
		// update_option('elementor_viewport_lg', '940');
		update_option('elementor_disable_color_schemes', 'yes');
		update_option('elementor_disable_typography_schemes', 'yes');
		update_option('elementor_experiment-e_dom_optimization', 'active');
		update_option('elementor_experiment-e_optimized_assets_loading', 'active');

		// Containers can cause issues.
		update_option('elementor_experiment-container', 'inactive');

		// The onboarding redirection can cause conflict with our own. 
		update_option('elementor_onboarded', true);
	}
}

// init and make available in Bunyad::get('admin')
Bunyad::register('admin', [
	'class' => 'Bunyad_Theme_Admin',
	'init'  => true
]);
<?php
namespace Sphere\Core\AdblockDetect;
use Sphere\Core\Plugin;

/**
 * Adblocker Detection.
 */
class Module 
{
	protected $path_url;
	protected $path;

	public function __construct()
	{
		$this->path_url = Plugin::instance()->path_url . 'components/adblock-detect/';
		$this->path     = Plugin::instance()->path . 'components/adblock-detect/';
		
		add_action('wp', [$this, 'setup']);

		$options = new Options;
		$options->register_hooks();	
	}

	public function setup()
	{
		// Only for ThemeSphere themes.
		if (!class_exists('\Bunyad', false)) {
			return;
		}

		// Auto-load next post is disabled. The filter can return true to force enable.
		$is_enabled = apply_filters('sphere/adblock/enabled', $this->get_option('adblock_enabled'));
		if (!$is_enabled) {
			return;
		}

		add_action('wp_enqueue_scripts', [$this, 'register_assets']);
		add_action('wp_footer', [$this, 'add_modal_markup']);
		add_action('wp_footer', [$this, 'add_js']);

	}

	/**
	 * Registe frontend assets.
	 *
	 * @return void
	 */
	public function register_assets()
	{
		if (!class_exists('Bunyad_Theme_SmartMag', false)) {
			wp_enqueue_style(
				'ts_modal',
				$this->path_url . 'css/ts-modal.css',
				[],
				Plugin::VERSION
			);
		}

		wp_enqueue_style(
			'detect-modal',
			$this->path_url . 'css/modal.css',
			[],
			Plugin::VERSION
		);
	}

	/**
	 * Modal markup and configs.
	 *
	 * @return void
	 */
	public function add_modal_markup()
	{
		?>
			<div id="detect-modal" class="ts-modal detect-modal" aria-hidden="true" 
				data-delay="<?php echo esc_attr($this->get_option('adblock_delay')); ?>" 
				<?php echo ($this->get_option('adblock_no_reshow') ? ' data-no-reshow ' : ''); ?>
				data-reshow-timeout="<?php echo esc_attr($this->get_option('adblock_reshow_timeout')); ?>">
				<div class="ts-modal-overlay" tabindex="-1">
					<div class="ts-modal-container" role="dialog" aria-modal="true" aria-labelledby="detect-modal-title">
						<header class="ts-modal-header">
							<div id="detect-modal-title" class="visuallyhidden">
								<?php echo esc_html($this->get_option('adblock_title')); ?>
							</div>

							<?php if ($this->get_option('adblock_dismissable')): ?>
								<button class="close-btn" aria-label="<?php esc_attr_e('Close modal', 'bunyad'); ?>" data-micromodal-close></button>
							<?php endif; ?>
						</header>

						<div class="detect-modal-content">
							<svg class="stop-icon" width="70px" height="70px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="Warning"><path id="Vector" d="M5.75 5.75L18.25 18.25M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></g></svg>
							<h5 class="heading"><?php echo esc_html($this->get_option('adblock_title')); ?></h5>
							<div class="message">
								<?php echo wp_kses_post($this->get_option('adblock_message')); ?>
							</div>

							<?php if ($this->get_option('adblock_button')): ?>
							<div class="buttons">
								<a href="<?php echo esc_attr($this->get_option('adblock_button_link')); ?>" class="ts-button" target="_blank">
									<?php echo esc_html($this->get_option('adblock_button_label')); ?>
								</a>
							</div>
							<?php endif; ?>
						</div>

					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Add JS code to the footer.
	 * Inline JS so adblocks don't block the detection script URL. 
	 * 
	 * @return void
	 */
	public function add_js()
	{
		$inline_js = file_get_contents($this->path . 'js/detect.js');

		if (!WP_DEBUG) {
			$inline_js = str_replace(["\r", "\n", "\t"], '', $inline_js);
		}

		echo '<script>' . $inline_js . '</script>';
	}

	/**
	 * Get an option from the theme customizer options if available.
	 *
	 * @param string $key
	 * @return mixed|null
	 */
	public function get_option($key)
	{
		if (class_exists('\Bunyad') && \Bunyad::options()) {
			return \Bunyad::options()->get($key);
		}

		$defaults = [
			'adblock_enabled'     => 0,
			'adblock_title'       => '',
			'adblock_message'     => '',
			'adblock_delay'       => 0,
			'adblock_dismissable' => 1,
			'adblock_no_reshow'   => 0,
			'adblock_reshow_timeout' => 24,
			'adblock_button'         => 0,
			'adblock_button_label'   => '',
			'adblock_button_link'    => ''
		];

		return isset($defaults[$key]) ? $defaults[$key] : null;
	}
}
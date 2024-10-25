<?php
/**
 * Custom Codes to be added to be injected into several hooks.
 */
class Bunyad_Theme_CustomCodes
{
	public $locations;

	public function __construct()
	{
		add_action('wp', [$this, 'init']);

		$this->locations = [
			'header_before' => [
				'label' => esc_html__('Above Header', 'bunyad-admin'),
				'id'    => 1,
			],
			'header_after' => [
				'label' => esc_html__('Below Header', 'bunyad-admin'),
				'id'    => 2,
			],
			'footer_before' => [
				'label' => esc_html__('Above Footer', 'bunyad-admin'),
				'id'    => 3,
			],
			'footer_after' => [
				'label' => esc_html__('Below Footer', 'bunyad-admin'),
				'id'    => 4,
			],
			'single_before' => [
				'label'      => esc_html__('Single Post: Above Content', 'bunyad-admin'),
				'conditions' => ['is_single'],
				'hook'       => 'bunyad_post_content_before',
				'id'         => 5,
			],
			'single_after' => [
				'label'      => esc_html__('Single Post: Below Content', 'bunyad-admin'),
				'conditions' => ['is_single'],
				'hook'       => 'bunyad_post_content_after',
				'id'         => 6,
			],
			'single_author' => [
				'label'      => esc_html__('Single: Below Author Box', 'bunyad-admin'),
				'conditions' => ['is_single'],
				'hook'       => 'bunyad_author_box_after',
				'id'         => 7
			],
			'single_para' => [
				'label'      => esc_html__('Single: Between Content', 'bunyad-admin'),
				'conditions' => ['is_single'],
				'filter'     => 'the_content',
				'id'         => 8
			],
		];
	}

	public function init()
	{
		// Check if there are any codes to inject.
		$codes = Bunyad::options()->get_all('codes_');
		if (!count($codes)) {
			return;
		}

		// Special Case: Head tag codes.
		if (!empty($codes['codes_header'])) {
			add_action('wp_head', function() use ($codes) {
				echo do_shortcode($codes['codes_header']);
			});
		}

		// Special Case: Head tag codes AMP.
		if (!empty($codes['codes_header_amp']) && Bunyad::amp()->active()) {
			add_action('wp_head', function() use ($codes) {
				echo do_shortcode($codes['codes_header_amp']);
			});
		}

		// Special Case: Footer tag codes.
		if (!empty($codes['codes_footer'])) {
			add_action('wp_footer', function() use ($codes) {
				echo do_shortcode($codes['codes_footer']);
			});
		}

		// Other codes with hooks. Must be at 'wp' hook so conditionals can be used.
		$this->add_code_hooks();
	}

	/**
	 * Hook to all the locations and output code.
	 */
	public function add_code_hooks()
	{
		foreach ($this->locations as $key => $location) {

			// If have code for any of these areas.
			$has_code = Bunyad::options()->get(
				'codes_' . $key, 
				'codes_amp_' . $key, 
				'codes_md_' . $key, 
				'codes_sm_' . $key
			);

			if (!$has_code) {
				continue;
			}

			if (!$this->is_valid_view(Bunyad::options()->get('codes_hide_' . $key))) {
				continue;
			}

			// A closure is used to keep variables.
			$add_code = function($content = '') use ($key, $location) {
				return $this->add_code($key, $location, $content);
			};

			if (isset($location['filter'])) {
				add_filter($location['filter'], $add_code);
			}
			else {
				$hook = isset($location['hook']) ? $location['hook'] : 'bunyad_' . $key;
				add_action($hook, $add_code);
			}
		}
	}

	/**
	 * Check if current view isn't set to be ignored.
	 * 
	 * @return boolean
	 */
	protected function is_valid_view($hide_on = []) 
	{
		if (!$hide_on) {
			return true;
		}

		$conditions = [
			'pages'      => 'is_page',
			'posts'      => 'is_single',
			'archives'   => 'is_archive',
			'archive'    => 'is_archive', // Alias
			'categories' => 'is_category',
			'tags'       => 'is_tag',
			'search'     => 'is_search',
			'404'        => 'is_404',
			'home' => function() {
				return is_home() || is_front_page();
			},
		];

		$hide = false;
		foreach ($hide_on as $key) {
			if (!isset($conditions[$key]) || !is_callable($conditions[$key])) {
				continue;
			}

			$hide = call_user_func($conditions[$key]);
			
			// Stop going further in loop once satisfied.
			if ($hide) {
				break;
			}
		}

		return $hide ? false : true;
	}

	/**
	 * Callback: Output code at an action or inject to a filter hook.
	 * 
	 * @uses \Bunyad::amp()
	 * @uses \Bunyad::options()
	 * 
	 * @param string $key      Key/Id for the location.
	 * @param array  $location Location data and configs.
	 * @param string $content  Content if using a filter.
	 * 
	 * @return mixed Either the content for filters, or void.
	 */
	public function add_code($key, $location, $content = '')
	{
		if (!$this->check_conditions($key)) {
			return $content;
		}

		// AMP code is different from normal codes.
		if (Bunyad::amp() && Bunyad::amp()->active()) {

			$code = Bunyad::options()->get('codes_amp_' . $key);

			// Fix: Add a wrapper to the 100vw widths, for in-content ads, mainly for adsense.
			// if (preg_match('/width=.?100vw/', $code)) {
			// 	if (strpos($key, 'single_') !== false) {
			// 		$code = sprintf(
			// 			'<div class="a-wrap-sm-full">%1$s</div>',
			// 			$code
			// 		);
			// 	}
			// }

			$codes = [
				'all' => $code
			];
		}
		else {

			$has_devices = !empty(Bunyad::options()->get('codes_devices_' . $key));
			$codes = [
				($has_devices ? 'lg' : 'all') => Bunyad::options()->get('codes_' . $key),
			];

			if ($has_devices) {
				$codes += [
					'md' => Bunyad::options()->get('codes_md_' . $key),
					'sm' => Bunyad::options()->get('codes_sm_' . $key),
				];
			}
		}

		$output = [];
		foreach ($codes as $device => $code) {
			$output[] = $this->render_code(
				$code,
				$device,
				Bunyad::options()->get('codes_label_' . $key),
				Bunyad::options()->get('codes_wrap_' . $key),
				$location['id']
			);
		}

		$output = implode("\n", $output);

		// Special Case: Paragraph injection.
		if (strpos($key, 'single_para') !== false) {
			return $this->inject_content(
				$output,
				$content,
				Bunyad::options()->get('codes_paras_' . $key)
			);
		}

		// Lazyload images.
		$output = str_replace('<img', '<img loading="lazy"', $output);
		echo do_shortcode($output);
	}

	/**
	 * Check speicfied conditions in an OR relation.
	 *
	 * @param string $key Location key.
	 * @return void
	 */
	public function check_conditions($key)
	{
		if (!isset($this->locations[$key]) || !isset($this->locations[$key]['conditions'])) {
			return true;
		}

		foreach ($this->locations[$key]['conditions'] as $condition) {
			if (call_user_func($condition)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Output code at the location.
	 *
	 * @param string  $code    Code to output.
	 * @param string  $device  Show on this speicfic device, lg, md, sm etc.
	 * @param boolean $wrapper Whether there's a background.
	 * @param mixed   $id
	 * @return void
	 */
	public function render_code($code, $device, $label = '', $wrapper = false, $id = '')
	{
		$classes = ['a-wrap a-wrap-base'];

		if (!$code) {
			return;
		}

		// Wrapper class based on a unique id.
		if ($id) {
			$classes[] = "a-wrap-{$id}";
		}

		// Special case for in-post.
		if ($id === 8) {
			$classes[] = 'alignwide';
		}

		if ($wrapper) {
			$classes[] = 'a-wrap-bg';
		}

		// Add device responsive helper class unless it's all.
		if ($device !== 'all') {
			$classes[] = "show-{$device}";

			if ($device === 'sm') {
				$classes[] = 'show-xs';
			}
		}

		if ($label) {
			$label = sprintf('<div class="label">%s</div>', $label);
		}

		$output = sprintf(
			'<div class="%1$s">%2$s %3$s</div>',
			esc_attr(implode(' ', $classes)),
			$label,  // Safe code added by priveleged user.
			$code    // Safe code added by priveleged user.
		);

		return $output;
	}

	/**
	 * Inject content after X paragraphs.
	 * 
	 * @param string  $insert  Code/content to insert.
	 * @param string  $content Content to inject into.
	 * @param integer $number  The number of paragraphs to insert afer.
	 */
	public function inject_content($insert, $content, $number)
	{
		$p_tag  = '</p>';
		$paras  = explode($p_tag, $content);
		$number = absint($number);
		
		foreach ($paras as $key => $para) {
			
			$paras[$key] .= $p_tag;
			
			// Add after this paragraph.
			if ($key + 1 === $number) {
				$paras[$key] .= $insert;
			}
		}
		
		return implode('', $paras);
	}

	public function get_locations()
	{
		return $this->locations;
	}
}

// init and make available in Bunyad::get('custom_codes')
Bunyad::register('custom_codes', [
	'class' => 'Bunyad_Theme_CustomCodes',
	'init'  => true
]);
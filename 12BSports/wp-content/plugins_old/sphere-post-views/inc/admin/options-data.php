<?php

namespace Sphere\PostViews\Admin;
use Sphere\PostViews\Plugin;

/**
 * Options data.
 */
class OptionsData
{
	const OPTIONS_KEY = 'sphere_post_views_options';
	
	public function __construct()
	{
		add_filter('bunyad_theme_options', [$this, 'customizer_options']);

		// Note: Hook after default options have been cleaned up.
		add_action('customize_save_after', [$this, 'customizer_save_options'], 11);
	}

	/**
	 * Copy the options from theme customizer to options.
	 */
	public function customizer_save_options()
	{
		if (!class_exists('\Bunyad') || !\Bunyad::options()) {
			return;
		}

		\Bunyad::options()->reinit();

		$options = \Bunyad::options()->get_all('spv_');
		update_option(self::OPTIONS_KEY, $options);
	}

	/**
	 * Add to theme customizer options.
	 *
	 * @param array $options
	 */
	public function customizer_options(array $options)
	{
		$options[] = [
			'sections' => [[
				'title'  => esc_html__('Sphere Post Views', 'sphere-post-views'),
				'id'     => 'sphere-post-views',
				'priority' => 40,
				'fields' => self::get_options(),
			]]
		];

		return $options;
	}

	public static function get_options()
	{
		$options = [];

		$options[] = [
			'name'  => 'spv_log_limit',
			'label' => esc_html__('Expire Data Log', 'sphere-post-views'),
			'desc'  => esc_html__('To reduce DB size. This does not affect total number count. Enable to set an expiry date for data used for sorting by 30 days, 90 days views etc.', 'sphere-post-views'),
			'value' => 1,
			'type'  => 'toggle',
			'style' => 'inline-sm'
		];

		$options[] = [
			'name'  => 'spv_log_expiry',
			'label' => esc_html__('Log Expiry Days', 'sphere-post-views'),
			'desc'  => esc_html__('If you only do 30 days popular sort, you can set it 30 days to keep database size low. Or even lower if just using overall views count without days limit.', 'sphere-post-views'),
			'value' => 180,
			'type'  => 'number',
			'style' => 'inline-sm',
			'context' => [['key' => 'spv_log_limit', 'value' => '', 'compare' => '!=']],
		];

		$options[] = [
			'name'  => 'spv_skip_loggedin',
			'label' => esc_html__('Skip Logged-in Users', 'sphere-post-views'),
			'desc'  => '',
			'value' => 0,
			'type'  => 'toggle',
			'style' => 'inline-sm',
		];
			
		$options[] = [
			'name'  => 'spv_repeat_count_delay',
			'label' => esc_html__('Repeat Count Delay Hours', 'sphere-post-views'),
			'desc'  => esc_html__('Minimum hours delay to re-count a repeating visitor.', 'sphere-post-views'),
			'value' => 0,
			'type'  => 'number',
			'style' => 'inline-sm',
			'input_attrs' => ['min' => 0, 'max' => 5000, 'step' => .5],
			'classes' => 'sep-bottom',
		];

		// Note: This method is called on SHORTINIT endpoint too where dir_url is unavailable.
		if (Plugin::get_instance()->dir_url !== '') {
			$endpoint_desc = sprintf(
				esc_html__('SHORTINT endpoint that may not work on all hosts. Test if %s shows Successful.', 'sphere-post-views'),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url(Plugin::get_instance()->dir_url . 'log-view.php?test=1'),
					esc_html__('This Link', 'sphere-post-views')
				)
			);
		}

		$options[] = [
			'name'  => 'spv_short_endpoint',
			'label' => 'Performance: Alternate Endpoint',
			'desc'  => $endpoint_desc ?? '',
			'value' => 0,
			'type'  => 'toggle',
			'style' => 'inline-sm',
		];

		$options[] = [
			'name'  => 'spv_sampling',
			'label' => esc_html__('Performance: Enable Sampling', 'sphere-post-views'),
			'desc'  => 
				esc_html__('Only for very high traffic sites. Sampling will only count one visit out of every N vists and update by same N. Acccuracy will eventually be decent based on a maths formula, on high traffic sites.', 'sphere-post-views')
				. ' <a href="https://theme-sphere.com/docs/smartmag/#sphere-post-views-sampling" target="_blank">' 
				. esc_html__('Learn More', 'sphere-post-views') 
				. '</a>',
			'value' => 0,
			'type'  => 'toggle',
			'style' => 'inline-sm'
		];

		$options[] = [
			'name'  => 'spv_sampling_rate',
			'label' => esc_html__('Performance: Sampling Rate', 'sphere-post-views'),
			'desc'  => 
				esc_html__('A sampling rate of 5-25 appropriate for 10-50k visits per day, 100 for 200-300k visits per day. Lower number means more accuracy, but more server load.', 'sphere-post-views')
				. ' <a href="https://theme-sphere.com/docs/smartmag/#sphere-post-views-sampling" target="_blank">' 
				. esc_html__('Learn More', 'sphere-post-views') 
				. '</a>',
			'value' => 10,
			'type'  => 'number',
			'style' => 'inline-sm',
			'context' => [['key' => 'spv_sampling', 'value' => '', 'compare' => '!=']],
		];

		$options[] = [
			'name'  => 'spv_batch_cache',
			'label' => esc_html__('Performance: Batch Update', 'sphere-post-views'),
			'desc'  => 
				esc_html__('For very high traffic sites. If your server has Redis or Memcached (and the plugins installed), we can skip writing to database on each update. Views will be collected in redis/memcached RAM and updated every few minutes instead.', 'sphere-post-views')
				. ' <a href="https://theme-sphere.com/docs/smartmag/#sphere-post-views-batch" target="_blank">' 
				. esc_html__('Learn More', 'sphere-post-views') 
				. '</a>',
			'value' => 0,
			'type'  => 'toggle',
			'style' => 'inline-sm',
		];

		$options[] = [
			'name'  => 'spv_admin_columns',
			'label' => esc_html__('Views Column In Admin', 'sphere-post-views'),
			'desc'  => esc_html__('Show post views in the posts list table in admin.', 'sphere-post-views'),
			'value' => 1,
			'type'  => 'toggle',
			'style' => 'inline-sm',
		];

		return $options;
	}

	public static function get_all() {
		return self::get_options();
	}
}
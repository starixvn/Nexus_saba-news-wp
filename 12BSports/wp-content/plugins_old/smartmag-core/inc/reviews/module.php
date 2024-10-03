<?php
namespace SmartMag\Reviews;
use Bunyad;

/**
 * The Reviews API.
 */
class Module
{
	public $rating_max;
	
	public function __construct()
	{
		$this->rating_max = (!empty(Bunyad::options()->rating_max) ? Bunyad::options()->rating_max : 10);

		// Inject review box in content.
		add_filter('the_content', [$this, 'inject_review']);

		// Hook at earlier priority so users can use filters without setting priorities.
		add_filter('bunyad_meta_boxes', [$this, 'register_metabox'], 1);
		add_action('bunyad_meta_before_save', [$this, 'process_save']);

		// AJAX handlers.
		add_action('wp_ajax_nopriv_bunyad_rate', [$this, 'add_rating']);
		add_action('wp_ajax_bunyad_rate', [$this, 'add_rating']);

		// Add shortcode.
		if (Bunyad::options()->legacy_mode) {
			add_shortcode('review', [$this, '_render_shortcode']);
		}

		add_shortcode('ts_review', [$this, '_render_shortcode']);
	}

	/**
	 * Callback: Setup metabox options via the framework config.
	 */
	public function register_metabox($meta_boxes)
	{
		$meta_boxes['reviews'] = [
			// register_boxes() will add prefix.
			'id'       => 'reviews',
			'title'    => esc_html_x('Review', 'bunyad-admin'),
			'priority' => 'high', 
			'page'     => ['post'],
			'form'     => trailingslashit(__DIR__) . 'metabox/form.php',
			'options'  => trailingslashit(__DIR__) . 'metabox/options.php',
		];

		return $meta_boxes;
	}

	/**
	 * On metabox save, we need additional cleanup for review meta.
	 */
	public function process_save($post_id)
	{
		$meta_prefix = Bunyad::options()->get_config('meta_prefix') . '_';

		// Ensure the review metabox data exists and not an invalid request.
		if (!isset($_POST[$meta_prefix . 'reviews'])) {
			return;
		}

		$meta = Bunyad::posts()->meta(null, $post_id);

		foreach ($meta as $key => $value) {
			if (!preg_match('/criteria_rating_([0-9]+)$/', $key, $match)) {
				continue;
			}

			$meta_key = $meta_prefix . $key;

			if (!isset($_POST[$meta_key])) {
				delete_post_meta($post_id, $meta_key);
				delete_post_meta($post_id, $meta_prefix . 'criteria_label_' . $match[1]);
			}
		}
	}

	/**
	 * Add review/ratings to content
	 * 
	 * @param string $content
	 */
	public function inject_review($content)
	{
		if (!is_single() || !Bunyad::posts()->meta('reviews')) {
			return $content;
		}
		
		$position  = Bunyad::posts()->meta('review_pos');
		$shortcode = '[ts_review position="'. esc_attr($position) .'"]';
		
		// Based on placement.
		if (strstr($position, 'top')) { 
			$content =  $shortcode . $content;
		}
		else if ($position == 'bottom') {
			$content .= $shortcode; 
		}

		return $content;
	}

	/**
	 * Get all the criteria and the associated editor rating.
	 * 
	 * @return array
	 */
	public function get_criteria()
	{
		$meta = Bunyad::posts()->meta();
		$criteria = [];

		foreach ($meta as $key => $value) {
			if (preg_match('/criteria_rating_([0-9]+)$/', $key, $match)) {

				$data = [
					'number' => $match[1],
					'rating' => $value,
					'label'  => $meta['criteria_label_' . $match[1]]
				];

				// Bad import.
				if (is_array($data['rating'])) {
					$data['rating'] = $data['rating'][0];
					$data['label']  = array_values((array) $data['label'])[0];
				}

				$criteria[] = $data;
			}
		}

		return apply_filters('bunyad_reviews_criteria', $criteria);
	}

	/**
	 * Get all the pros and cons.
	 * 
	 * @return array|boolean
	 */
	public function get_pros_cons()
	{
		$pros = Bunyad::posts()->meta('review_pros') ?: [];
		$cons = Bunyad::posts()->meta('review_cons') ?: [];

		if (!$pros && !$cons) {
			return false;
		}

		return apply_filters('bunyad_reviews_pros_cons', [
			'pros' => $pros,
			'cons' => $cons,
			'pros_title' => Bunyad::posts()->meta('review_pros_title') ?: esc_html__('The Good', 'bunyad'),
			'cons_title' => Bunyad::posts()->meta('review_cons_title') ?: esc_html__('The Bad', 'bunyad'),
		]);
	}
	
	/**
	 * Converts percent rating to points rating
	 * 
	 * @param float|int $percent
	 */
	public function percent_to_decimal($percent)
	{
		return ((float) $percent / 100) * $this->rating_max;
	}
	
	/**
	 * Converts point rating to percent 
	 * 
	 * @param float|int $decimal
	 */
	public function decimal_to_percent($decimal)
	{
		return round(floatval($decimal) / $this->rating_max * 100);	
	}

	/**
	 * Get number of votes on a post.
	 */
	public function votes_count($post_id = null)
	{
		$rating = Bunyad::posts()->meta('user_rating', $post_id);
		
		if (!empty($rating['count'])) {
			return $rating['count'];
		}
		
		return 0;
	}
	
	/**
	 * Get overall user rating for a post
	 * 
	 * @param integer|null $post_id
	 * @param string $type  empty for overall number or 'percent' for rounded percent
	 */
	public function get_user_rating($post_id = null, $type = '')
	{
		$rating = Bunyad::posts()->meta('user_rating', $post_id);
		
		if (!empty($rating['overall'])) {
			
			// return percent?
			if ($type == 'percent') {
				return $this->decimal_to_percent($rating['overall']);
			}
			
			return round($rating['overall'], 1);
		}
		
		return 0;
	}
	
	/**
	 * Callback: AJAX add user rating.
	 */
	public function add_rating()
	{		
		// can the rating be added - perform all checks
		if (!$this->can_rate(intval($_POST['id']))) {
			wp_die();
		}
		
		if ($_POST['rating'] && $_POST['id']) {
		
			$votes = Bunyad::posts()->meta('user_rating', intval($_POST['id']));
			
			// defaults if no votes yet
			if (!is_array($votes)) {
				$votes = array('votes' => array(), 'overall' => null, 'count' => 0);
			}
			
			$votes['count']++;
		
			// Add to votes record.
			$votes['votes'][time()] = array($this->percent_to_decimal($_POST['rating']));

			// @deprecated due to GDPR
			//$votes['votes'][time()] = array($this->percent_to_decimal($_POST['rating']), $this->get_user_ip());
			
			// Recount overall.
			$total = 0;
			foreach ($votes['votes'] as $data) {
				$total += $data[0]; // rating
			}
			
			$votes['overall'] = $total / $votes['count'];
			
			// Save meta data.
			update_post_meta(intval($_POST['id']), '_bunyad_user_rating', $votes); 
			
			// Set the cookie.
			$ids = array();
			if (!empty($_COOKIE['bunyad_user_ratings'])) {
				$ids = (array) explode('|', $_COOKIE['bunyad_user_ratings']);
			}
			
			array_push($ids, $_POST['id']);
			setcookie('bunyad_user_ratings', implode('|', $ids), time() + 86400 * 30);
			
			echo json_encode([
				'decimal' => round($votes['overall'], 1), 
				'percent' => $this->decimal_to_percent($votes['overall'])
			]);
		}

		exit;
	}

	/**
	 * Whether a user can rate.
	 * 
	 * @param integer|null $post_id
	 * @param integer|null $user_id
	 */
	public function can_rate($post_id = null, $user_id = null)
	{
		if (!$post_id) {
			$post_id = get_the_ID();
		}
		
		// rating not even enabled
		if (!Bunyad::posts()->meta('reviews', $post_id) || !Bunyad::options()->user_rating) {
			return false;
		}

		// ip check
		// @deprecated due to GDPR policy

		// $votes = Bunyad::posts()->meta('user_rating', $post_id);
		// $user_ip = $this->get_user_ip();
		
		// if (!empty($votes['votes'])) {
			
		// 	foreach ((array) $votes['votes'] as $time => $data) {
		// 		if (!empty($data[1]) && $data[1] == $user_ip) {
		// 			return false;
		// 		}
		// 	}
		// }
		
		// Cookie check.
		if (!empty($_COOKIE['bunyad_user_ratings'])) {
			$ids = (array) explode('|', $_COOKIE['bunyad_user_ratings']);
			
			if (in_array($post_id, $ids)) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Render the shortcode.
	 * 
	 * @access private
	 * @return string
	 */
	public function _render_shortcode($atts)
	{
		$props = shortcode_atts([
			'position' => 'bottom'
		], $atts);

		extract($props, EXTR_SKIP);

		ob_start();
		include __DIR__ . '/review.php';

		return ob_get_clean();
	}

	/**
	 * Get user ip
	 * 
	 * @deprecated No longer used due to GDPR policy
	 */
	public function get_user_ip()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			// check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];	
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			// to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}
}
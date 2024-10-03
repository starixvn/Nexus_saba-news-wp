<?php

namespace Sphere\PostViews;

/**
 * The view logging endpoint.
 *
 * @author Hector Cabrera <me@cabrerahector.com>
 * @author ThemeSphere <support@theme-sphere.com>
 */
class Endpoint 
{
	private $options;

	public function __construct(Options $options)
	{
		$this->options = $options;
	}

	public function init()
	{
		add_action('wp_ajax_update_views_ajax', [$this, 'update_views']);
		add_action('wp_ajax_nopriv_update_views_ajax', [$this, 'update_views']);
	}

	/**
	 * Updates views count on page load via AJAX.
	 */
	public function update_views()
	{
		if (empty($_POST['post_id']) || !Helper::verify_token($_POST['token'] ?? '', 'spv-update-views')) {
			wp_die('Invalid request.');
		}

		if ($this->options->spv_skip_loggedin && is_user_logged_in()) {
			wp_die('Disabled for logged in users.');
		}

		$post_id = intval($_POST['post_id']);
		
		$exec_time = 0;
		$start = microtime(true);

		$result = $this->update_views_count($post_id);
		
		$end = microtime(true);
		$exec_time += round($end - $start, 6);

		if ($result) {
			// Note: On alternate endpoint, timing will be off as using SHORTINT means some
			// options haven't been accessed already and will use the MySQL query.
			wp_die("Sphere Post Views: OK. Execution time: " . $exec_time . " seconds");
		}

		wp_die('Could not update the views count!');
	}

	/**
	 * Updates views count.
	 *
	 * @global object   $wpdb
	 * @param  int      $post_ID
	 * @return bool|int FALSE if query failed, TRUE on success
	 */
	private function update_views_count($post_ID)
	{
		global $wpdb;

		$table = $wpdb->prefix . "popularposts";
		$wpdb->show_errors();

		$now     = Helper::now();
		$curdate = Helper::curdate();
		$views   = $this->options->spv_sampling ? $this->options->spv_sampling_rate : 1;

		/**
		 * Before updating view count. 
		 * 
		 * Notes:
		 * - Not available to themes but to other plugins as it's executed at 'plugins_loaded'.
		 * - This will be not be available when using SHORTINT / Alternate Endpoint.
		 */
		do_action('sphere/post_views/pre_update', $post_ID, $views);

		$result1 = $result2 = false;

		/**
		 * Store in persistent object cache and update only every 2 minutes.
		 */
		if (wp_using_ext_object_cache() && $this->options->spv_batch_cache) {

			$now_datetime = new \DateTime($now, new \DateTimeZone(Helper::get_timezone()));
			$timestamp = $now_datetime->getTimestamp();
			$date_time = $now_datetime->format('Y-m-d H:i');
			$date_time_with_seconds = $now_datetime->format('Y-m-d H:i:s');
			$high_accuracy = false;

			$key = $high_accuracy ? $timestamp : $date_time;

			if (!$wpp_cache = wp_cache_get('_wpp_cache', 'transient')) {
				$wpp_cache = [
					'last_updated' => $date_time_with_seconds,
					'data' => [
						$post_ID => [
							$key => 1
						]
					]
				];
			} else {
				if (!isset($wpp_cache['data'][$post_ID][$key])) {
					$wpp_cache['data'][$post_ID][$key] = 1;
				} else {
					$wpp_cache['data'][$post_ID][$key] += 1;
				}
			}

			// Update cache
			wp_cache_set('_wpp_cache', $wpp_cache, 'transient', 0);

			// How long has it been since the last time we saved to the database?
			$last_update = $now_datetime->diff(new \DateTime($wpp_cache['last_updated'], new \DateTimeZone(Helper::get_timezone())));
			$diff_in_minutes = $last_update->days * 24 * 60;
			$diff_in_minutes += $last_update->h * 60;
			$diff_in_minutes += $last_update->i;

			// It's been more than 2 minutes, save everything to DB
			if ($diff_in_minutes > 2) {

				$query_data = "INSERT INTO {$table}data (`postid`,`day`,`last_viewed`,`pageviews`) VALUES ";
				$query_summary = "INSERT INTO {$table}summary (`postid`,`pageviews`,`view_date`,`view_datetime`) VALUES ";

				foreach ($wpp_cache['data'] as $pid => $data) {
					$views_count = 0;

					foreach ($data as $ts => $cached_views) {
						$views_count += $cached_views;
						$ts = Helper::is_timestamp($ts) ? $ts : strtotime($ts);

						$query_summary .= $wpdb->prepare("(%d,%d,%s,%s),", [
							$pid,
							$cached_views,
							date("Y-m-d", $ts),
							date("Y-m-d H:i:s", $ts)
						]);
					}

					$query_data .= $wpdb->prepare("(%d,%s,%s,%s),", [
						$pid,
						$date_time_with_seconds,
						$date_time_with_seconds,
						$views_count
					]);
				}

				$query_data = rtrim($query_data, ",") . " ON DUPLICATE KEY UPDATE pageviews=pageviews+VALUES(pageviews),last_viewed=VALUES(last_viewed);";
				$query_summary = rtrim($query_summary, ",") . ";";

				// Clear cache
				$wpp_cache['last_updated'] = $date_time_with_seconds;
				$wpp_cache['data'] = [];
				wp_cache_set('_wpp_cache', $wpp_cache, 'transient', 0);

				// Save
				$result1 = $wpdb->query($query_data);
				$result2 = $wpdb->query($query_summary);

			} else {
				$result1 = $result2 = true;
			}
		} 
		// Live update to the DB
		else {
			// Update all-time table
			$result1 = $wpdb->query($wpdb->prepare(
				"INSERT INTO {$table}data
				(postid, day, last_viewed, pageviews) VALUES (%d, %s, %s, %d)
				ON DUPLICATE KEY UPDATE pageviews = pageviews + %d, last_viewed = %s;",
				$post_ID,
				$now,
				$now,
				$views,
				$views,
				$now
			));

			// Update range (summary) table
			$result2 = $wpdb->query($wpdb->prepare(
				"INSERT INTO {$table}summary
				(postid, pageviews, view_date, view_datetime) VALUES (%d, %d, %s, %s)
				ON DUPLICATE KEY UPDATE pageviews = pageviews + %d, view_datetime = %s;",
				$post_ID,
				$views,
				$curdate,
				$now,
				$views,
				$now
			));
		}

		if (!$result1 || !$result2) {
			return false;
		}

		return true;
	}
}
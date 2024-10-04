<?php

namespace Sphere\PostViews\Query;
use Sphere\PostViews\Helper;

/**
 * Uses and modified the WP_Query SQL to include post views.
 * 
 * @author ThemeSphere
 */
class Query
{
	protected $orderby = false;

	/**
	 * @var string[]
	 */
	protected $clauses = [];

	/**
	 * @var \WP_Query
	 */
	protected $query;

	/**
	 * Query vars set via 'views_query' => [] in original query.
	 *
	 * @var array|null
	 */
	protected $views_query = null;

	/**
	 * Whether there's a date range for post views.
	 *
	 * @var boolean
	 */
	protected $has_range = false;

	public function __construct(\WP_Query $query, $options = [])
	{
		$this->query = $query;
		$this->views_query = $this->query->query['views_query'] ?? [];

		foreach ($options as $key => $value) {
			$this->$key = $value;
		}

		$this->has_range = !empty($this->views_query['range']);
	}

	/**
	 * Modifies the WP Query to add additional sorting and post views query.
	 *
	 * @param array $clauses
	 * @param \WP_Query $query
	 * @return array
	 */
	public function modify_query(array $clauses = [], $query = null)
	{
		// Not the same query instance.
		if (!$query || $this->query !== $query) {
			return $clauses;
		}

		$this->clauses = $clauses;

		$this->do_joins();
		$this->do_fields();
		$this->do_orderby();

		return $this->clauses;
	}

	/**
	 * Add in the joins for the view query.
	 *
	 * @global object $wpdb
	 */
	public function do_joins()
	{
		global $wpdb;

		/**
		 * No range defined, fallback to all time.
		 */
		$totals_query = " LEFT JOIN `{$wpdb->prefix}popularpostsdata` spv ON spv.postid = {$wpdb->posts}.ID";
		if (!$this->has_range) {
			$this->clauses['join'] .= $totals_query;
			return;
		}

		/**
		 * Date range available.
		 * 
		 * @author Hector Cabrera
		 */
		$start_date = new \DateTime(
			Helper::now(), 
			new \DateTimeZone(Helper::get_timezone())
		);

		$time_units = ["MINUTE", "HOUR", "DAY", "WEEK", "MONTH"];

		// Valid time unit
		if (
			isset($this->views_query['time_unit'])
			&& in_array(strtoupper($this->views_query['time_unit']), $time_units)
			&& isset($this->views_query['time_quantity'])
			&& filter_var($this->views_query['time_quantity'], FILTER_VALIDATE_INT)
			&& $this->views_query['time_quantity'] > 0
		) {
			$time_quantity = $this->views_query['time_quantity'];
			$time_unit = strtoupper($this->views_query['time_unit']);

			if ('MINUTE' == $time_unit) {
				$start_date = $start_date->sub(new \DateInterval('PT' . (60 * $time_quantity) . 'S'));
				$start_datetime = $start_date->format('Y-m-d H:i:s');
				$views_time_range = "view_datetime >= '{$start_datetime}'";
			} elseif ('HOUR' == $time_unit) {
				$start_date = $start_date->sub(new \DateInterval('PT' . ((60 * $time_quantity) - 1) . 'M59S'));
				$start_datetime = $start_date->format('Y-m-d H:i:s');
				$views_time_range = "view_datetime >= '{$start_datetime}'";
			} elseif ('DAY' == $time_unit) {
				$start_date = $start_date->sub(new \DateInterval('P' . ($time_quantity - 1) . 'D'));
				$start_datetime = $start_date->format('Y-m-d');
				$views_time_range = "view_date >= '{$start_datetime}'";
			} elseif ('WEEK' == $time_unit) {
				$start_date = $start_date->sub(new \DateInterval('P' . ((7 * $time_quantity) - 1) . 'D'));
				$start_datetime = $start_date->format('Y-m-d');
				$views_time_range = "view_date >= '{$start_datetime}'";
			} else {
				$start_date = $start_date->sub(new \DateInterval('P' . ((30 * $time_quantity) - 1) . 'D'));
				$start_datetime = $start_date->format('Y-m-d');
				$views_time_range = "view_date >= '{$start_datetime}'";
			}
		} 
		// Invalid time unit, default to 30 days
		else {
			$start_date = $start_date->sub(new \DateInterval('P29D'));
			$start_datetime = $start_date->format('Y-m-d H:i:s');
			$views_time_range = "view_datetime >= '{$start_datetime}'";
		}

		$this->clauses['join'] .= " LEFT JOIN (
				SELECT SUM(pageviews) AS pageviews, postid FROM `{$wpdb->prefix}popularpostssummary` WHERE {$views_time_range} GROUP BY postid
			) spvr ON spvr.postid = {$wpdb->posts}.ID";

		// Add total view counts as well.
		if (!empty($this->views_query['add_totals'])) {
			$this->clauses['join'] .= $totals_query;
		}
	}

	/**
	 * Order posts by post views.
	 *
	 * @global object $wpdb
	 */
	public function do_orderby()
	{
		global $wpdb;

		if (!$this->orderby) {
			return;
		}

		$order = $this->query->get('order');
		$this->clauses['orderby'] = "post_views {$order}, {$wpdb->posts}.post_date {$order}";
	}

	/**
	 * Add in the post_views counter field.
	 */
	public function do_fields()
	{
		if ($this->clauses['fields']) {
			$prefix = $this->has_range ? 'spvr' : 'spv';
			$this->clauses['fields'] .=  ", COALESCE({$prefix}.pageviews, 0) AS post_views";

			if (!empty($this->views_query['add_totals'])) {
				$this->clauses['fields'] .= ', COALESCE(spv.pageviews, 0) AS total_views';
			}
		}
	}
}

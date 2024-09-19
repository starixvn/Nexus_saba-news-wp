<?php

namespace Bunyad\Core\AdminNotices;

/**
 * An admin notice.
 */
class Notice
{
	protected $id;

	protected $message  = '';
	protected $title    = '';
	
	/**
	 * Whether it's per-user notice or global (affects the dismissal).
	 *
	 * @var string
	 */
	protected $scope    = 'user';

	/**
	 * Extra classes to add.
	 *
	 * @var array
	 */
	protected $base_classes = ['bunyad-admin-notice', 'notice'];
	protected $type       = '';
	protected $classes    = [];
	protected $sticky     = false;
	protected $capability = true;
	protected $callback;

	protected $screens      = [];
	protected $screens_skip = [];

	/**
	 * @var array[] {
	 *   @type string $class
	 *   @type string $label
	 *   @type string $link
	 *   @type string $target
	 * }
	 * 
	 * Example usage:
	 * <code>
	 *   [
	 *     'dismiss' => true,
	 *     'custom-1' => [
	 *       'class' => 'button-primary',
	 *       'label' => 'My Custom Button
	 *     ]
	 *   ]
	 * </code>
	 */
	protected $buttons = [];

	/**
	 * Expire time in seconds from current time for dismissals.
	 * 
	 * Note: Better to use remind button instead.
	 *
	 * @var int
	 */
	protected $dismiss_expiry;

	/**
	 * When the button 'remind' is used, a remind time is required in seconds.
	 *
	 * @var int
	 */
	protected $remind_time = 86400;

	protected $nonce = '';

	/**
	 * @var Module
	 */
	protected $notices;

	/**
	 * A callback to test if a notice should be rendered.
	 * 
	 * @var callable|null
	 */
	protected $should_render_callback;

	public function __construct($id, Module $module, $message, $options = [])
	{
		$this->id      = $id;
		$this->notices = $module;
		$this->message = $message;

		array_walk($options, function($value, $key) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		});
	}

	public function set_nonce($value)
	{
		$this->nonce = $value;
	}

	/**
	 * Print the notice after performing relevant checks.
	 *
	 * @return void
	 */
	public function render()
	{
		if (!$this->should_show()) {
			return false;
		}

		echo $this->start_wrapper() // phpcs:ignore WordPress.Security.EscapeOutput -- Escaped HTML internally formed.
			. wp_kses_post($this->title)
			. wp_kses_post($this->message)
			. $this->render_buttons();

		if ($this->callback && is_callable($this->callback)) {
			call_user_func($this->callback);
		}

		echo $this->end_wrapper(); // phpcs:ignore WordPress.Security.EscapeOutput -- Escaped HTML internally formed.

		return true;
	}

	protected function should_show()
	{
		if ($this->capability && !current_user_can($this->capability)) {
			return false;
		}

		if ($this->is_dismissed()) {
			return false;
		}

		if (
			is_callable($this->should_render_callback)
			&& !call_user_func($this->should_render_callback)
		) {
			return false;
		}

		if (!$this->is_valid_screen()) {
			return false;
		}

		return true;
	}

	protected function is_valid_screen()
	{
		if (!$this->screens && !$this->screens_skip) {
			return true;
		}

		// Make sure the get_current_screen function exists.
		if (!function_exists('get_current_screen')) {
			require_once ABSPATH . 'wp-admin/includes/screen.php';
		}

		/** @var $screen WP_Screen */
		$screen = get_current_screen();
		if (!($screen instanceof \WP_Screen)) {
			return true;
		}

		if ($this->screens && !in_array($screen->id, $this->screens)) {
			return false;
		}

		if ($this->screens_skip && in_array($screen->id, $this->screens_skip)) {
			return false;
		}

		return true;
	}

	protected function render_buttons()
	{
		if (!$this->buttons) {
			return;
		}
		
		$button_data = [
			'dismiss' => [
				'label' => esc_html('Dismiss', 'bunyad-admin'),
				'class' => 'button-secondary ts-notice-dismiss',
			],
			'remind'  => [
				'label' => esc_html('Remind Later', 'bunyad-admin'),
				'class' => 'button-secondary ts-notice-dismiss ts-notice-remind',
			],
			'dismiss-link' => [
				'label' => esc_html('Dismiss', 'bunyad-admin'),
				'class' => 'ts-notice-dismiss',
			]
		];
		
		$html = [];
		foreach ($this->buttons as $key => $button) {
			
			// Use default configs as base for this button.
			if (isset($button_data[$key])) {
				$button = array_replace($button_data[$key], (array) $button);
			}
			
			$button['class'] = $button['class'] ?? 'button-primary';
			if (strpos($button['class'], 'button') !== false) {
				$button['class'] .= ' is-button';
			}
			
			$html[] = sprintf(
				'<a href="%1$s" class="%2$s" target="%3$s" data-id="%4$s">%5$s</a>%6$s',
				esc_url($button['link'] ?? '#'),
				esc_attr($button['class']),
				esc_attr($button['target'] ?? ''),
				esc_attr($key),
				esc_attr($button['label']),
				!empty($button['separator']) ? '<span class="sep">|</span>' : ''
			);
		}

		return sprintf(
			'<div class="ts-notice-buttons">%s</div>',
			implode(' ', $html)
		);
	}

	/**
	 * Whether a notice was dismissed.

	 * @return boolean
	 */
	public function is_dismissed()
	{
		$dismissed = $this->notices->get_dismissed($this->scope);
		$dismissed = $dismissed[$this->id] ?? false;

		// -1 is never expiring dismiss.
		if ($dismissed && ($dismissed === -1 || time() < $dismissed)) {
			return true;
		}

		return false;
	}

	public function dismiss($remind_later = false)
	{
		$dismissed = $this->notices->get_dismissed($this->scope);

		if (!$remind_later) {
			$dismissed[$this->id] = $this->dismiss_expiry ? time() + $this->dismiss_expiry : -1;
		}
		else {
			$dismissed[$this->id] = $this->remind_time ? time() + $this->remind_time : -1;
		}

		$this->notices->set_dismissed($dismissed, $this->scope);
	}

	public function start_wrapper() 
	{
		$attrs = [
			'class' => array_merge(
				$this->base_classes,
				$this->classes
			),
			'data-notice-id' => $this->id,
		];

		if ($this->type) {
			$attrs['class'][] = 'notice-' . $this->type;
		}

		if (!$this->sticky) {
			if (!$this->nonce) {
				$this->nonce = $this->notices->get_nonce();
			}

			$attrs['data-nonce'] = $this->nonce;
			$attrs['class'][]  = 'is-dismissible';
		}

		return sprintf(
			'<div %1$s>',
			// Safe attributes escaped by the attribs method.
			\Bunyad::markup()->attribs('admin-notice', $attrs, ['echo' => false])
		);
	}

	public function end_wrapper()
	{
		return '</div>';
	}
}
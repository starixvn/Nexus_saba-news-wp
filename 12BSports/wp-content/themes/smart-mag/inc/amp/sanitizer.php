<?php

class Bunyad_Theme_Amp_Sanitizer extends AMP_Base_Sanitizer 
{

	protected $xpath;
	protected $body;
	
	protected $bind_attr_prefix;

	/**
	 * Called via core plugin - before DOM is active and class init
	 */
	public static function add_buffering_hooks($args = array())
	{
		//add_filter('walker_nav_menu_start_el', array(__CLASS__, 'add_menu_chevrons'), 10, 4);
	}

	/**
	 * @inheritDoc
	 */
	public function sanitize() 
	{
		$this->body = $this->dom->getElementsByTagName('body')->item(0);
		if (!$this->body) {
			return;
		}

		$this->xpath = new DOMXPath($this->dom);

		// Add amp class to root
		$this->body->setAttribute(
			'class', 
			$this->body->getAttribute('class') . ' ' 
				. join(' ', Bunyad::amp()->get_min_class(['amp']))
		);

		// Add off-canvas navigation toggles
		$this->off_canvas_menu();

		// Add header search
		$this->add_header_search();

	}

	/**
	 * Fix navigation
	 */
	public function off_canvas_menu()
	{
		$button_els = $this->xpath->query('//*[contains(@class, "offcanvas-toggle")]');
		$nav_el     = $this->dom->getElementById('off-canvas');
		$close_el   = $this->xpath->query('//div[@id="off-canvas"]//a[contains(@class, "close")]')->item(0);

		if (!$nav_el || !$button_els) {
			return;
		}

		$state_id = 'navMenuToggledOn';
		$expanded = false;

		$this->body->setAttribute(
			$this->get_bind_attr_prefix() . 'class',
			sprintf(
				"%s + ($state_id ? %s : '')",
				wp_json_encode($this->body->getAttribute('class')),
				wp_json_encode(' '. Bunyad::amp()->get_min_class('off-canvas-active'))
			)
		);

		$state_el = $this->dom->createElement('amp-state');
		$state_el->setAttribute('id', $state_id);

			$script_el = $this->dom->createElement('script');
			$script_el->setAttribute('type', 'application/json');
			$script_el->appendChild(
				$this->dom->createTextNode(wp_json_encode($expanded))
			);

		$state_el->appendChild($script_el);

		$nav_el->parentNode->insertBefore($state_el, $nav_el);

		$nav_el->setAttribute('aria-expanded', 'false');
		$nav_el->setAttribute(
			$this->get_bind_attr_prefix() . 'aria-expanded', 
			"$state_id ? 'true' : 'false'"
		);

		AMP_DOM_Utils::add_attributes_to_node($close_el, array(
			'on'       => "tap:AMP.setState({ $state_id: ! $state_id })",
			'role'     => 'button',
			'tabindex' => 0
		));

		foreach ($button_els as $button) {
			AMP_DOM_Utils::add_attributes_to_node($button, [
				'on'       => "tap:AMP.setState({ $state_id: ! $state_id })",
				'role'     => 'button',
				'tabindex' => 0
			]);
	
			$button->setAttribute(
				$this->get_bind_attr_prefix() . 'class',
				sprintf(
					"%s + ($state_id ? %s : '')", 
					wp_json_encode($button->getAttribute('class')),
					wp_json_encode(' ' . Bunyad::amp()->get_min_class('active'))
				)
			);
		}
	}

	/**
	 * Get bind data attributes prefix. Account for older versions of AMP Plugin.
	 *
	 * @return string
	 */
	protected function get_bind_attr_prefix()
	{
		if (defined('AmpProject\Amp::BIND_DATA_ATTR_PREFIX')) {
			return AmpProject\Amp::BIND_DATA_ATTR_PREFIX;
		}

		if (defined('AmpProject\Dom\Document::AMP_BIND_DATA_ATTR_PREFIX')) {
			return AmpProject\Dom\Document::AMP_BIND_DATA_ATTR_PREFIX;
		}

		// Older version or changed in future.
		return 'data-amp-bind-';
	}

	/**
	 * Add header search lightbox
	 */
	public function add_header_search()
	{
		// Get search link handler
		$links = $this->xpath->query('//*[@id="smart-head" or @id="smart-head-mobile"]//a[contains(@class, "search-icon")]');
		
		foreach ($links as $search_link) {
			$search_link->setAttribute('on', 'tap:search-modal-lightbox');
			$search_link->removeAttribute('href');
		}
	}
}
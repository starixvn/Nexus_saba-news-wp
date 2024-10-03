<?php

namespace Bunyad\Blocks\LoopPosts;

/**
 * Overlay Loop Post Class
 */
class OverlayPost extends BasePost
{
	public $id = 'overlay';
	
	/**
	 * Default props for this post
	 * 
	 * @return array
	 */
	public function get_default_props()
	{
		$props = parent::get_default_props();

		return array_replace($props, [
			'show_excerpts' => false,
			'class_wrap'    => ['grid-overlay overlay-post grid-overlay-a'],
			'meta_props'    => [
				'add_class' => 'meta-contrast',
			],
			'title_style'   => '',
			'content_wrap'  => true,
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function _pre_render()
	{
		// Unsupported post format positions.
		if (in_array($this->props['post_formats_pos'], ['bot-left', 'bot-right'])) {
			$this->props['post_formats_pos'] = 'top-right';
		}

		// Post title style.
		if ($this->props['title_style'] === 'bg') {
			$this->props['class_wrap'][] = ' l-post-title-bg';
		}

		parent::_pre_render();
	}
}
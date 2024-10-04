<?php

namespace Bunyad\Blocks\LoopPosts;

/**
 * Small Loop Post Class
 */
class SmallPost extends BasePost
{
	public $id = 'small';

	/**
	 * Default props for this post
	 * 
	 * @return array
	 */
	public function get_default_props()
	{
		$props = parent::get_default_props();

		return array_merge($props, [
			'meta_props' => [
				'align' => 'left',
			],
			'image' => 'bunyad-thumb',
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function _pre_render()
	{
		parent::_pre_render();

		// Disable media and media for style b.
		if ($this->props['style'] == 'b') {
			$this->props['show_media'] = false;
		}

		// Only center position for small posts.
		if ($this->props['show_post_formats']) {
			$this->props['post_formats_pos'] = 'center';
		}
	}
}
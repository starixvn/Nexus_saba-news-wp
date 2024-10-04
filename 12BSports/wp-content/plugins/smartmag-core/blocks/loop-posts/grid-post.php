<?php

namespace Bunyad\Blocks\LoopPosts;

/**
 * Grid Loop Post Class
 */
class GridPost extends BasePost
{
	public $id = 'grid';

	/**
	 * Default props for this post
	 * 
	 * @return array
	 */
	public function get_default_props()
	{
		$props = parent::get_default_props();

		return array_replace($props, [
			'image'  => 'bunyad-grid',
		]);
	}
}
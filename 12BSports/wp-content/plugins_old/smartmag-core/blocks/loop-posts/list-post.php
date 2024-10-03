<?php

namespace Bunyad\Blocks\LoopPosts;

/**
 * Overlay Loop Post Class
 */
class ListPost extends BasePost
{
	public $id = 'list';

	public function _pre_render()
	{
		$this->props['class_wrap_add'] = (array) $this->props['class_wrap_add'];

		// Vertically centered content.
		if ($this->props['content_vcenter']) {
			$this->props['class_wrap_add'][] = 'list-post-v-center';
		}

		if ($this->props['media_vpos']) {
			array_push(
				$this->props['class_wrap_add'],
				'l-post-media-v-' . $this->props['media_vpos']
			);
		}

		// Grid style on small devices.
		if (!empty($this->props['grid_on_sm'])) {
			$this->props['class_wrap_add'][] = 'grid-on-sm';
		}
		else {
			$this->props['class_wrap_add'][] = 'list-post-on-sm';
		}

		parent::_pre_render();
	}
}
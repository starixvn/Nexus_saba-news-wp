<?php
namespace Bunyad\Blocks\Base;

/**
 * Common base data.
 */
class OptionsData implements \ArrayAccess
{

	protected $data = [];

	public function __construct()
	{
		$this->data = [
			'cat_labels_pos_options'       => [],
			'ratio_options'                => [],
			'read_more_options'            => [],
			'meta_options'                 => [],
			'reviews_options'              => [],
			'heading_tags'                 => [],
			'block_headings'               => [],
			'load_more_options'            => [],
			'featured_grid_options'        => [],
			'featured_type_options'        => [],
			'post_format_pos_options'      => [],
			'supports_bhead_line_width'    => [],
			'supports_bhead_line_color'    => [],
			'supports_bhead_border_color'  => [],
			'supports_bhead_line_weight'   => [],
			'supports_bhead_border_weight' => [],
			'supports_bhead_roundness'     => [],
			'post_title_styles'            => [],
		];

		return $this;
	}

	#[\ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		return $this->offsetExists($offset) ? $this->data[$offset] : null;
	}

	public function offsetSet($offset, $value): void
	{
		$this->data[$offset] = $value;
	}

	public function offsetExists($offset): bool
	{
		return isset($this->data[$offset]);
	}

	public function offsetUnset($offset): void
	{
		if ($this->offsetExists($offset)) {
			unset($this->data[$offset]);
		}
	}

	public function append($array)
	{
		$this->data = array_replace($this->data, $array);
		return $this->data;
	}
}

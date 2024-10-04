<?php
/**
 * All the widgets to be used as blocks
 */
namespace Bunyad\Widgets\Loops {
	use Bunyad\Widgets\BlockWidget;

	if (!function_exists('esc_html')) {
		return;
	}

	class Grid_Block extends BlockWidget {
		public $conf = [
			'title' => 'SmartMag Block - Grid',
		];
	}

	// class Large_Block extends BlockWidget {
	// 	public $conf = [
	// 		'title' => 'SmartMag Block - Large',
	// 	];
	// }

	class Overlay_Block extends BlockWidget {
		public $conf = [
			'title' => 'SmartMag Block - Overlay',
		];
	}

	// class FocusGrid_Block extends BlockWidget {
	// 	public $conf = [
	// 		'title' => 'SmartMag Block - Focus Grid',
	// 	];
	// }

	// class NewsFocus_Block extends BlockWidget {
	// 	public $conf = [
	// 		'title' => 'SmartMag Block - News Focus',
	// 	];
	// }

	class Highlights_Block extends BlockWidget {
		public $conf = [
			'title' => 'SmartMag Block - Highlights',
		];
	}

	// class PostsList_Block extends BlockWidget {
	// 	public $conf = [
	// 		'title' => 'SmartMag Block - List',
	// 	];
	// }

	class PostsSmall_Block extends BlockWidget {
		public $conf = [
			'title'       => 'SmartMag - Latest Posts',
			'description' => 'Widget to show latest posts. Uses Small Posts block.',
		];
	}

	// class PostsGallery_Block extends BlockWidget {
	// 	public $conf = [
	// 		'title' => 'SmartMag - Posts Gallery',
	// 	];
	// }
}

namespace Bunyad\Widgets {

	class Newsletter_Block extends BlockWidget {
		public $conf = [
			'title' => 'SmartMag - Newsletter',
			'description' => 'Newsletter subscribe widget.',
		];
	}

	class Codes_Block extends BlockWidget {
		public $conf = [
			'title' => 'SmartMag - Ads / Code',
			'description' => 'Advertisements or codes widget.',
		];
	}
}
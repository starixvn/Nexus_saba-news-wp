<?php

/**
 * Generate a list of widgets classes concat in one file to prevent generation of 
 * unnecessary elementor.php files.
 */

// Loop blocks.
namespace Bunyad\Blocks\Loops {
	use Bunyad\Elementor\LoopWidget;

	class Grid_Elementor extends LoopWidget {}
	class Large_Elementor extends LoopWidget {}
	class Overlay_Elementor extends LoopWidget {}
	class FeatGrid_Elementor extends LoopWidget {}
	class FocusGrid_Elementor extends LoopWidget {}
	class NewsFocus_Elementor extends LoopWidget {}
	class Highlights_Elementor extends LoopWidget {}
	class PostsList_Elementor extends LoopWidget {}
	class PostsSmall_Elementor extends LoopWidget {}
	class PostsGallery_Elementor extends LoopWidget {}
}

namespace Bunyad\Blocks {
	use Bunyad\Elementor\BaseWidget;
	
	// Other blocks.
	class Newsletter_Elementor extends BaseWidget {}
	class Heading_Elementor extends BaseWidget {}
	class Codes_Elementor extends BaseWidget {}
	class Breadcrumbs_Elementor extends BaseWidget {}
	class SocialIcons_Elementor extends BaseWidget {}
}
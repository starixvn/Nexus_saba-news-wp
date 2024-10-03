<?php
/**
 * Page template that's full width with header and footer.
 */
use Sphere\Core\Elementor\Layouts\Module;

if (class_exists('Bunyad') && Bunyad::core()) {
	Bunyad::core()->set_sidebar('none');
}

get_header();
?>

<div class="main-full">
	<?php Module::instance()->template->render_content(); ?>
</div>

<?php get_footer(); ?>

<?php
/**
 * Partial: Cart Icon for header.
 */

if (!function_exists('WC')) {
	return;
}

?>

<div class="cart-icon">
	<?php
		echo Bunyad::get('woocommerce')->cart_menu_link(); // Safe output generated from method.
	?>
</div>
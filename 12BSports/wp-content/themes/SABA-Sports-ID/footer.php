<?php
/**
 * Footer Template.
 */

do_action('bunyad_footer_before');
$do_footer = apply_filters('bunyad_do_partial_footer', true);

if ($do_footer) {
	// Fallback to classic footer if we're here, implying custom is missing.
	$footer_layout = Bunyad::options()->footer_layout ?? 'classic';

	$classes = [
		'main-footer',
		'cols-gap-lg',
		'footer-' . $footer_layout,
		Bunyad::options()->footer_scheme === 'dark' ? 's-dark' : '',
	];
}

?>
	<?php if ($do_footer): ?>
		<footer <?php Bunyad::markup()->attribs('main-footer', [
			'class' => $classes
		]); ?>>

			<?php
				get_template_part('partials/footer/' . $footer_layout);
			?>
		</footer>
	<?php endif; ?>
	
	<?php do_action('bunyad_footer_after'); ?>

</div><!-- .main-wrap -->

<?php get_template_part('partials/search-modal'); ?>

<?php wp_footer(); ?>
<script src="https://www.google.com/recaptcha/api.js?render=6LdPTJcpAAAAAE8x28uDUOzV_Is3boVMcCMG_zj_"></script>
	<?php

	$full_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	// 登入
	if (strpos($full_url, '/login') !== false) {
		echo '<script src="/wp-content/themes/SABA-Sports-ID/login.js?n=' . time() .'"></script>';
	}
	
	// 註冊
	if (strpos($full_url, '/sign-up') !== false) {
		echo '<script src="/wp-content/themes/SABA-Sports-ID/reg.js?n=' . time() .'"></script>';
	}

	// 註冊 Social
	if (strpos($full_url, '/social-sign-up') !== false) {
		echo '<script src="/wp-content/themes/SABA-Sports-ID/reg-social.js?f=' . time() .'"></script>';
	}

	// 忘記密碼
	if (strpos($full_url, '/forget-password') !== false) {
		echo '<script src="/wp-content/themes/SABA-Sports-ID/forgetpw.js?n=' . time() .'"></script>';
	}

	// 會員中心
	if (strpos($full_url, '/my-account') !== false) {	
		echo '<script src="/wp-content/themes/SABA-Sports-ID/my-account.js?n=' . time() .'"></script>';
	} 

	?>

<!-- 弹出窗口代码 -->
<!--<div class="popoutbg modal-container show-modal">-->
<div class="popoutbg modal-container  ">
    <div class="modal-content">
        <main>
            <div class="popad-wrapper">
<?php /*
                <a target="_self" href="https://chat.whatsapp.com/BekpiBTaBoG0xLcJ3aSTR0" class="imgwhd ad-bnimg gxr">
                    <img class="popad-img" src="https://i.imgur.com/jnBjZRz.png">
                </a>
                <div class="banner-button-wrap-text">
                    <div class="cookie-message">Don't show this</div>
                    <div class="close-btn">Close</div>
                </div>
*/ ?>
            </div>
        </main>
    </div>
</div>

<script>
    // 获取弹出窗口元素
    var modal = document.querySelector('.modal-container');

    // 获取弹出窗口内部内容元素
    var modalContent = document.querySelector('.modal-content');

    // 显示弹出窗口
    function showModal() {
        //modal.style.display = 'block';
    }

    // 隐藏弹出窗口


    // 监听点击事件
    document.addEventListener('click', function (event) {
        // 如果点击的元素不在弹出窗口内部，则隐藏弹出窗口
        if (!modalContent.contains(event.target)) {
            hideModal();
        }
    });

    // 防止点击弹窗内容时冒泡触发隐藏事件
    modalContent.addEventListener('click', function (event) {
        event.stopPropagation();
    });

 

</script>
<input id="goto" type="hidden" value="/">
</body>
</html>
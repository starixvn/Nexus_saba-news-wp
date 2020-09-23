<article <?php post_class( 'post_item_single post_item_404 post_item_none_search' ); ?>>
	<div class="post_content">
<!-- 		<h1 class="page_title"><?php esc_html_e( 'No results', 'fcunited' ); ?></h1> -->
		<div class="page_info">
			<h3 class="page_subtitle">
			<?php
				// Translators: Add the query string
				echo esc_html( sprintf( esc_html__( "Rất tiếc, chúng tôi không tìm thấy bài viết có chứa nội dung \"%s\" ", 'fcunited' ), get_search_query() ) );
			?>
			</h3>
			<p class="page_description" style="font-size:16px;">
			<?php
				// Translators: Add the site URL to the link
				echo wp_kses_data( sprintf( __( "Vui lòng thử lại với từ khoá khác, hoặc trở về <a href='%s'>Trang chủ</a>.", 'fcunited' ), esc_url( home_url( '/' ) ) ) );
			?>
			</p>
			<?php
			do_action(
				'fcunited_action_search',
				array(
					'style' => 'normal',
					'class' => 'page_search',
					'ajax'  => false
				)
			);
			?>
		</div>
	</div>
</article>

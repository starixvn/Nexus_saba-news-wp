<?php

if (!function_exists('bunyad_smartmag_comment')):

	/**
	 * Callback for displaying a comment
	 * 
	 * @todo eventually move to bunyad templates with auto-generated functions as template containers
	 * 
	 * @param mixed   $comment
	 * @param array   $args
	 * @param integer $depth
	 */
	function bunyad_smartmag_comment($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		
		switch ($comment->comment_type):
			case 'pingback':
			case 'trackback':
			?>
			
			<li class="post pingback">
				<p><?php esc_html_e('Pingback:', 'bunyad'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'bunyad'), '<span class="edit-link">', '</span>'); ?></p>
			<?php
				break;


			default:
			?>
		
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment">
				
					<div class="comment-avatar">
					<?php
						echo get_avatar($comment, apply_filters('bunyad_comment_avatar_size', 45));
					?>
					</div>
					
					<div class="comment-meta">					
						<span class="comment-author"><?php comment_author_link(); ?></span> <?php esc_html_e('on', 'bunyad'); ?> 
						<a href="<?php comment_link(); ?>" class="comment-time" title="<?php comment_date(); esc_html_e(' at ', 'bunyad'); comment_time(); ?>">
							<time pubdate datetime="<?php comment_time('c'); ?>"><?php comment_date(); ?> <?php comment_time(); ?></time>
						</a>
		
						<?php edit_comment_link(__( 'Edit', 'bunyad' ), '<span class="edit-link"> &middot; ', '</span>' ); ?>
					</div> <!-- .comment-meta -->
		
					<div class="comment-content">
						<?php comment_text(); ?>
						
						<?php if ($comment->comment_approved == '0'): ?>
							<em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'bunyad'); ?></em>
						<?php endif; ?>
						
			
						<div class="reply">
							<?php
							comment_reply_link(array_merge($args, array(
								'reply_text' => esc_html__('Reply', 'bunyad') . ' <i class="tsi tsi-angle-right"></i>',
								'depth'      => $depth,
								'max_depth'  => $args['max_depth']
							))); 
							?>
							
						</div><!-- .reply -->
						
					</div>
				</article><!-- #comment-N -->
	
		<?php
				break;
		endswitch;
		
	}
	
endif;

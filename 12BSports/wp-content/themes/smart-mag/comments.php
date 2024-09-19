<?php 
/**
 * Template to output comment form - called via single.php
 * 
 * @see comments_template()
 */

// Comment form disabled.
if (!Bunyad::options()->single_comments) {
	return;
}

$comments_button = Bunyad::options()->single_comments_button && !Bunyad::amp()->active();

$wrap_classes = [
	'comments-area',
	$comments_button ? 'ts-comments-hidden' : ''
];

?>

	<?php if (post_password_required()): ?>
		<p class="nocomments"><?php esc_html_e('This post is password protected. Enter the password to view comments.', 'bunyad'); ?></p>
	<?php return; endif; ?>


	<?php if ($comments_button): ?>
		<div class="ts-comments-show">
			<a href="#" class="ts-button ts-button-b">
				<?php

				echo get_comments_number_text(
					esc_html__('Add A Comment', 'bunyad'),
					esc_html__('View 1 Comment', 'bunyad'),
					esc_html__('View % Comments', 'bunyad')
				);

				?>
			</a>
		</div>
	<?php endif; ?>


	<div id="comments" class="<?php echo esc_attr(join(' ', $wrap_classes)); ?>">

	<?php if (have_comments()) : ?>

		<?php
		Bunyad::blocks()->load(
			'Heading',
			[
				'heading'   => preg_replace('/(\d+)\s/', '*\\1* ', get_comments_number_text()),
				// 'align'     => $this->props['heading_align'],
				'type'      => Bunyad::options()->single_section_head_style,
				'html_tag'  => Bunyad::options()->single_section_head_tag,
			]
		)
		->render();
		?>

		<ol class="comments-list">
			<?php
				get_template_part('partials/comment');
				wp_list_comments(array('callback' => 'bunyad_smartmag_comment', 'max-depth' => 4));
			?>
		</ol>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')): // are there comments to navigate through ?>
		<nav class="comment-nav">
			<div class="nav-previous"><?php previous_comments_link(__( '&larr; Older Comments', 'bunyad')); ?></div>
			<div class="nav-next"><?php next_comments_link(__( 'Newer Comments &rarr;', 'bunyad')); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php elseif (!comments_open() && ! is_page() && post_type_supports(get_post_type(), 'comments')):	?>
		<p class="nocomments"><?php esc_html_e('Comments are closed.', 'bunyad'); ?></p>
	<?php endif; ?>
	
	
	<?php

	/**
	 * Output the comment form
	 */
	
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$html_req  = ($req ? " required='required'" : '');

	// Consent checked?
	$consent  = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';

	$fields = array(
		'author' => sprintf(
			'<p class="form-field comment-form-author">%s</p>',
			sprintf(
				'<input id="author" name="author" type="text" placeholder="%s" value="%s" size="30" maxlength="245"%s />',
				esc_html__('Name', 'bunyad') . ($req ? ' *' : ''),
				esc_attr($commenter['comment_author']),
				$html_req
			)
		),

		'email'  => sprintf(
			'<p class="form-field comment-form-email">%s</p>',
			sprintf(
				'<input id="email" name="email" type="email" placeholder="%s" value="%s" size="30" maxlength="100"%s />',
				esc_html__('Email', 'bunyad') . ($req ? ' *' : ''),
				esc_attr($commenter['comment_author_email']),
				$html_req
			)
		),

		'url'    => sprintf(
			'<p class="form-field comment-form-url">%s</p>',

			// Using type="text" to prevent input validation but inputmode to compensate.
			sprintf(
				'<input id="url" name="url" type="text" inputmode="url" placeholder="%s" value="%s" size="30" maxlength="200" />',
				esc_html__('Website', 'bunyad'),
				esc_attr( $commenter['comment_author_url'] )
			)
		),

		'cookies' => '
		<p class="comment-form-cookies-consent">
			<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />
			<label for="wp-comment-cookies-consent">' 
				. esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'bunyad') .'
			</label>
		</p>',
	);

	// Not supported before 4.9.6
	if (version_compare($GLOBALS['wp_version'], '4.9.6', '<')) {
		unset($fields['cookies']);
	}

	// Apply default filter
	$fields = apply_filters('comment_form_default_fields', $fields);
	
	comment_form(array(
		'title_reply'          => '<span class="heading">' . esc_html__('Leave A Reply', 'bunyad') . '</span>',
		'title_reply_to'       => '<span class="heading">' . esc_html__('Reply To %s', 'bunyad') . '</span>',
		'comment_notes_before' => '',
		'comment_notes_after'  => '',
	
		'logged_in_as' => '<p class="logged-in-as">' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'bunyad'), 
									admin_url('profile.php'), $user_identity, wp_logout_url(get_permalink())) . '</p>',
	
		'comment_field' => '
			<p>
				<textarea name="comment" id="comment" cols="45" rows="8" aria-required="true" placeholder="'. esc_attr__('Your Comment', 'bunyad') .'"  maxlength="65525" required="required"></textarea>
			</p>',
	
		'id_submit' => 'comment-submit',
		'label_submit' => esc_html__('Post Comment', 'bunyad'),
	
		'cancel_reply_link' => esc_html__('Cancel Reply', 'bunyad'),
	

		'fields' => $fields,

		// Get rid of 'novalidate'.
		'format' => '',
		
	)); ?>

	</div><!-- #comments -->

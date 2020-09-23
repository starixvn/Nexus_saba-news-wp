<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package videoly
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
  if ( post_password_required() ) {
    return;
  }
?>

<!-- Comments -->
<section class="coment-item">
  <!--<section class="post-comment" id="comments">-->
  <?php if(have_comments()): ?>
  <h4 class="tt-title-block-2 size-2 color-2"><?php echo get_comments_number(); ?> <?php esc_html_e('Comments', 'videoly'); ?></h4>
  <div class="empty-space marg-lg-b20"></div>

    <ol class="tt-comment commentlist">
      <?php
        wp_list_comments( array(
          'callback'     => 'videoly_comment',
          'end-callback' => 'videoly_close_comment',
          'style'        => 'ol',
          'short_ping'   => true,
        ) );
      ?>
    </ol>
    <div class="empty-space marg-lg-b60 marg-sm-b50 marg-xs-b30"></div>
    <div class="tt-devider"></div>
    <div class="empty-space marg-lg-b55 marg-sm-b50 marg-xs-b30"></div>

  <?php endif; ?>
  <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
    <nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
      <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'videoly' ); ?></h2>
      <div class="nav-links">

        <div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'videoly' ) ); ?></div>
        <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'videoly' ) ); ?></div>

      </div><!-- .nav-links -->
    </nav><!-- #comment-nav-above -->
  <?php endif; // check for comment navigation ?>

  <!--</section>-->

  <!-- Add Comment -->
  <div class="tt-comment-form tt-comment-form clearfix">
    

    

      <?php
        $commenter = wp_get_current_commenter();
        $req       = get_option( 'require_name_email' );
        $aria_req  = ( $req ? " aria-required='true'" : '' );

        $args = array(
          'id_form'           => 'commentform',
          'id_submit'         => 'comment_submit',
          'title_reply'       => esc_html__( 'Leave a Comment' ,'videoly'),
          'title_reply_to'    => esc_html__( 'Leave a Comment to %s'  ,'videoly'),
          'cancel_reply_link' => esc_html__( 'Cancel Comment'  ,'videoly'),
          'label_submit'      => esc_html__( 'Post Comment'  ,'videoly'),
          'comment_field'     => '
            <textarea name="comment" id="text" ' . $aria_req . ' class="c-area type-2 form-white placeholder" rows="10" placeholder="Your Comment"  maxlength="400"></textarea>
            ',
          'must_log_in'          => '<div class="simple-text font-poppins color-3"><p class="must-log-in">' .  wp_kses_post(sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ,'videoly' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) )) . '</p></div>',
          'logged_in_as'         => '<div class="simple-text font-poppins color-3"><p class="logged-in-as">' . wp_kses_post(sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'  ,'videoly'), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) ). '</p></div>',
          'comment_notes_before' => '<div class="simple-text size-5 font-poppins color-3"><p>Your email address will not be published. Required fields are marked *</p>',
          'comment_notes_after'  => '',
          'class_submit'         => '',
          'fields' => apply_filters( 'comment_form_default_fields',
            array(
              'author' => '
                  <div class="row"><div class="col-sm-6">
                    <!-- Name -->
                    <input type="text" name="author" id="name" ' . $aria_req . ' class="c-input placeholder" placeholder="Name" maxlength="100">',

              'email' => '
                  <input type="email" name="email" id="email" placeholder="Email" class="c-input placeholder" maxlength="100">',

              'url' => '
                <input type="text" name="url" id="website" placeholder="Website" class="c-input placeholder" maxlength="100"></div></div></div>',
            )
          )
        );
        comment_form($args);
      ?>
  

  </div>
  <!-- End Add Comment -->
</section>
<!--end of comments-->

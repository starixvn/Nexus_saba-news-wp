<?php
/**
 * Header Template file
 *
 * @package videoly
 * @since 1.0
 */
?>

<header class="tt-header left <?php echo videoly_get_opt('page-layout'); ?>">
  <div class="tt-header-wrapper">
    <div class="top-inner clearfix">
      <div class="container">
        <?php videoly_logo('logo', get_template_directory_uri().'/img/header/logo.png'); ?>
        <div class="cmn-toggle-switch"><span></span></div>
        <div class="cmn-mobile-switch"><span></span></div>
        <a class="tt-s-popup-btn"><i class="fa fa-search" aria-hidden="true"></i></a>
      </div>
    </div>
    <div class="toggle-block">
      <div class="toggle-block-container">


        <nav class="main-nav clearfix">
         <?php videoly_main_menu('menu'); ?>
        </nav>


        <div class="nav-more">
					<?php videoly_sponsor_ads('style1'); ?>
        </div>

        <?php if(videoly_get_opt('top-header-enable-switch')): ?>
        <div class="top-line clearfix">
          <div class="container">
            <div class="top-line-left">
              <div class="top-line-entry">
                <ul class="top-menu">
                  <?php
                    if (has_nav_menu('top-menu')):
                      wp_nav_menu(array(
                        'theme_location' => 'top-menu',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'depth'          => 1,
                      ));
                    endif;
                  ?>
                </ul>
              </div>
            </div>
            <div class="top-line-right">
              <div class="top-line-entry">
                <ul class="top-social">
                  <?php videoly_social_links('%s', videoly_get_opt('top-social-icons-category')); ?> 
                </ul>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
      </div>
    </div>
  </div>
</header>
<?php videoly_header_height(videoly_get_opt('header-height-switch')); ?>

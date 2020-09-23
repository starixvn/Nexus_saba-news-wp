<?php
/**
 * Template Name: Landing 
 *
 * @package marketing
 * @since 1.0
 */
?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class('landing-page'); ?>>

  <?php videoly_loader(); ?>
  <?php videoly_popup(); ?>
  <?php wp_enqueue_style('landing'); ?>

  <header class="tt-header clearfix">
      <div class="tt-header-inner">
          <a class="logo" href="#">
              <img src="<?php echo get_template_directory_uri(); ?>/img/header/logo.png" alt="">
          </a>
          <a class="c-btn btn-landing hvr-sweep-to-top" target="_blank" href="#">Get Videoly Now (35% Off)</a>
      </div>
  </header>

  <div id="content-wrapper">
      

    <!-- TT-BANNER -->
    <div class="tt-banner">
        <div class="tt-banner-inner">
            <div class="container">
              <div class="row">
                  <div class="col-md-12 text-center">
                      <div class="tt-banner-info">
                          <h1 class="tt-banner-title">Everything you need<br>for Blogging and Vlogging</h1>
                          <a class="c-btn btn-landing style-2 hvr-sweep-to-top">Get Videoly Now</a>
                      </div>
                  </div>
              </div>
            </div>
            <img src="<?php echo get_template_directory_uri(); ?>/img/pages/hero.png" alt="" class="tt-hero-img" />

        </div>
    </div>
      
    <div class="empty-space  marg-lg-b135 marg-sm-b50"></div>
    <div class="container">


      <div class="row" id="explore-demos">
          <div class="col-md-8 col-md-offset-2">
              <div class="tt-title">
                  <h3 class="tt-title-name">9 Pre-made Layouts to Kickstart</h3>
                  <div class="tt-title-description">
                    All layouts are one click importable. You can drag & drop elements.<br>You can combine different elements from different layouts.
                </div>
              </div>
          </div>
      </div>



        <div class="row" >
            <div class="isotope isotope-content">
                <!-- <div class="grid-sizer col-xs-6 col-sm-4"></div> -->
                <div class="isotope-item col-xs-6 col-sm-4">
                    <div class="tt-page">
                        <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/home/">
                            <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/1.png" height="400" width="370" alt="">
                        </a>
                        <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/home/">Home V1</a>
                    </div>
                </div>
                <div class="isotope-item col-xs-6 col-sm-4">
                    <div class="tt-page">
                        <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/home-2/">
                            <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/2.png" height="400" width="370" alt="">
                        </a>
                        <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/home-2/">Home V2</a>
                    </div>
                </div>
                <div class="isotope-item col-xs-6 col-sm-4">
                    <div class="tt-page">
                        <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/home_3/">
                            <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/3.png" height="400" width="370" alt="">
                        </a>
                        <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/home_3/">Home V3</a>
                    </div>
                </div>
                <div class="isotope-item col-xs-6 col-sm-4">
                    <div class="tt-page">
                        <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/home_4/">
                            <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/4.png" height="400" width="370" alt="">
                        </a>
                        <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/home_4/">Home V4</a>
                    </div>
                </div>
                <div class="isotope-item col-xs-6 col-sm-4">
                    <div class="tt-page">
                        <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/home_5/">
                            <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/5.png" height="400" width="370" alt="">
                        </a>
                        <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/home_5/">Home V5</a>
                    </div>
                </div>
                <div class="isotope-item col-xs-6 col-sm-4">
                    <div class="tt-page">
                        <a class="tt-page-img"  target="_blank" href="http://www.themebubble.com/demo/videoly/home_6/">
                            <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/6.png" height="400" width="370" alt="">
                        </a>
                        <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/home_6/">Home V6</a>
                    </div>
                </div>
                <div class="isotope-item col-xs-6 col-sm-4">
                    <div class="tt-page">
                        <a class="tt-page-img"  target="_blank" href="http://www.themebubble.com/demo/videoly/home_7/">
                            <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/7.png" height="400" width="370" alt="">
                        </a>
                        <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/home_7/">Home V7</a>
                    </div>
                </div>
                <div class="isotope-item col-xs-6 col-sm-4">
                    <div class="tt-page">
                        <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/home_8/">
                            <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/8.png" height="400" width="370" alt="">
                        </a>
                        <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/home_8/">Home V8</a>
                    </div>
                </div>
                <div class="isotope-item col-xs-6 col-sm-4">
                    <div class="tt-page">
                        <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/home_9/">
                            <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/9.png" height="400" width="369" alt="">
                        </a>
                        <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/home_9/">Home V9</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="empty-space  marg-lg-b110 marg-sm-b50"></div>


        <div class="row">
          <div class="col-md-8 col-md-offset-2">
              <div class="tt-title">
                  <h3 class="tt-title-name">6 Article Page Layouts</h3>
                  <div class="tt-title-description">
                    You can choose your single post layout while posting content.<br> You can embed from social sites (Twitter, Pinterest, etc.) too!
                </div>
              </div>
          </div>
        </div>

        <div class="row">
          <div class="isotope-content">
              <!-- <div class="grid-sizer col-xs-6 col-sm-4"></div> -->
              <div class="isotope-item col-xs-6 col-sm-4">
                  <div class="tt-page">
                      <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/fashion-week-entry-created-buzz/">
                          <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/10.png" height="400" width="369" alt="">
                      </a>
                      <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/fashion-week-entry-created-buzz/">Big Hero</a>
                  </div>
              </div>
              <div class="isotope-item col-xs-6 col-sm-4">
                  <div class="tt-page">
                      <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/a-classic-example-of-video-type-post-on-wordpress-theme-2/">
                          <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/11.png" height="400" width="369" alt="">
                      </a>
                      <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/a-classic-example-of-video-type-post-on-wordpress-theme-2/">Video Hero</a>
                  </div>
              </div>
              <div class="isotope-item col-xs-6 col-sm-4">
                  <div class="tt-page">
                      <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/the-guide-to-everyday-beauty/">
                          <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/12.png" height="400" width="369" alt="">
                      </a>
                      <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/the-guide-to-everyday-beauty/">No Hero</a>
                  </div>
              </div>


              <div class="isotope-item col-xs-6 col-sm-4">
                  <div class="tt-page">
                      <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/the-long-lost-art-of-melancholy-suicidal-depression/">
                          <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/13.png" height="400" width="369" alt="">
                      </a>
                      <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/the-long-lost-art-of-melancholy-suicidal-depression/">Left Sidebar</a>
                  </div>
              </div>

              <div class="isotope-item col-xs-6 col-sm-4">
                  <div class="tt-page">
                      <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/club-fight-1998/">
                          <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/14.png" height="400" width="369" alt="">
                      </a>
                      <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/club-fight-1998/">Right Sidebar</a>
                  </div>
              </div>

              <div class="isotope-item col-xs-6 col-sm-4">
                  <div class="tt-page">
                      <a class="tt-page-img" target="_blank" href="http://www.themebubble.com/demo/videoly/it-all-started-when-we-used-to-talk-sleep/">
                          <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/15.png" height="400" width="369" alt="">
                      </a>
                      <a class="tt-page-title" target="_blank" href="http://www.themebubble.com/demo/videoly/it-all-started-when-we-used-to-talk-sleep/">No Sidebar</a>
                  </div>
              </div>
          </div>
        </div>


          <div class="empty-space  marg-lg-b110 marg-sm-b50"></div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="empty-space  marg-lg-b80 marg-sm-b40"></div>
                <div class="tt-title text-left">
                    <h3 class="tt-title-name">One Click Demo Import</h3>
                    <div class="tt-title-description">
                      Import all the layouts just with one click. All you need to do is click on <strong>“import demo”.</strong> Bang! All layouts and pages are imported. You can kickstart your site with these layouts and tweak according to your need. 
                    </div>
                    <div class="empty-space  marg-lg-b25 marg-sm-b15"></div>
                    <a href="https://www.youtube.com/embed/TxvzpaVveO0?autoplay=1" class="tt-text-link tt-video-open"><i class="fa fa-play-circle"></i>Watch Video</a>
                </div>
            </div>
            <div class="col-md-6">
              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/one-click.png" alt="">
            </div>
          </div>          


          <div class="empty-space  marg-lg-b110 marg-sm-b50"></div>
          
          <div class="row">
            <div class="col-md-6 col-xs-12">

              <div class="mac-book-holder">
                <img class="holder-img" src="<?php echo get_template_directory_uri(); ?>/img/pages/drag-drop.gif" alt="">
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="empty-space  marg-lg-b80 marg-sm-b30"></div>
              <div class="tt-title text-left">
                  <h3 class="tt-title-name">Drag &amp Drop Visual Composer</h3>
                  <div class="tt-title-description">
                    Making websites has never been easier! After importing demos, just drag and drop to switch place of elements or keep them as it is. Change texts and images with ease. You can, of course, start from scratch. Thanks to Videoly elements/shortcodes. 
                  </div>
              </div>
            </div>
          </div>

          <div class="empty-space  marg-lg-b110 marg-sm-b50"></div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="empty-space  marg-lg-b80 marg-sm-b40"></div>
                <div class="tt-title text-left">
                    <h3 class="tt-title-name">Responsive &amp Retina Ready</h3> <div class="tt-title-description">
                      Videoly will make sure, your website looks great in mobile or any devices. From large screen to tiny screen, your audience will fall in love with your site &amp content. Get your website a sharper look, today!  
                    </div>
                </div>
            </div>
            <div class="col-md-6">
              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/responsive.png" alt="">
            </div>
          </div>      


          <div class="empty-space  marg-lg-b110 marg-sm-b50"></div>
          
          <div class="row">
            <div class="col-md-6">
              <img src="<?php echo get_template_directory_uri(); ?>/img/pages/premium-pack.png" alt="">
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="empty-space  marg-lg-b70 marg-sm-b30"></div>
              <div class="tt-title text-left">
                  <h3 class="tt-title-name">Premium Plugin & Bonus Pack</h3>
                  <div class="tt-title-description">
                    Each copy of Videoly comes with popular "Visual Composer" & a premium Photoshop toolkit to create social media imagery (Facebook, Twitter, Pinterest, Instagram) of your blog posts.
                  </div>
              </div>
            </div>
          </div>
      

          <div class="empty-space  marg-lg-b135 marg-sm-b50"></div>
          
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tt-title">
                    <h3 class="tt-title-name">Videoly Exclusive Features</h3>
                    <div class="tt-title-description">
                      Videoly comes with some of the exclusive features that will blow your mind. <br> These built-in features will make your site light-weight with less plugins!
                  </div>
                </div>
            </div>
          </div>


          <div class="row">
              <div class="isotope-content">
                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/16.png" alt="">
                          </a>
                          <a class="tt-page-title" target="_blank" href="#">Video Lightbox On/Off</a>
                      </div>
                  </div>
                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/17.png"  alt="">
                          </a>
                          <a class="tt-page-title" target="_blank" href="#">Sticky Video</a>
                      </div>
                  </div>
                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/18.png" alt="">
                          </a>
                          <a class="tt-page-title" target="_blank" href="#">Sticky Social Share</a>
                      </div>
                  </div>


                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/19.png" alt="">
                          </a>
                          <a class="tt-page-title" target="_blank" href="#">Different Video Formats</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/20.png" alt="">
                          </a>
                          <a class="tt-page-title" target="_blank" href="#">Gallery Post Type</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/21.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">Review Shortcode</a>
                      </div>
                  </div>

              </div>
          </div>


          <div class="empty-space  marg-lg-b110 marg-sm-b50"></div>
          
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tt-title">
                    <h3 class="tt-title-name">Advanced Features &amp Options</h3>
                    <div class="tt-title-description">
                      Videoly is packed with industry standard features. <br> With Videoly, the power is truely yours! 
                  </div>
                </div>
            </div>
          </div>


          <div class="row">
              <div class="isotope-content">

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/22.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">Made by Envato Elite</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/23.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">A Grade Performance</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/24.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">Smart Option Panel</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/25.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">Different Header Types</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/26.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">700+ Google Fonts</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/27.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">Translation Ready</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/28.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">Highly Customizable</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/29.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">SEO Optimized</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/30.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">Fast Loading</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/31.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">Video Tutorials</a>
                      </div>
                  </div>

                  <div class="isotope-item col-xs-6 col-sm-2">
                      <div class="tt-page small-thumb">
                          <a class="tt-page-img" target="_blank" href="#">
                              <img class="img-responsive" src="<?php echo get_template_directory_uri(); ?>/img/pages/32.png" alt="">
                          </a>
                          <a class="tt-page-title with-border" target="_blank" href="#">Dedicated Support</a>
                      </div>
                  </div>






              </div>
          </div>
          <!--end-->

      </div>



  </div>

  <div class="empty-space  marg-lg-b155 marg-sm-b50"></div>

  <footer class="tt-footer-landing">
      
    <div class="tt-footer-inner">
        <div class="row">
            <div class="col-md-12">
              <div class="text-center">
                <h4 class="tt-footer-title">Limited Time Discount (<span style="text-decoration:line-through;">$60</span> $39 Only!)</h4>
                <div class="tt-footer-text">Get all demos. Premium plugins for free. Free lifetime updates.</div>
                <a class="c-btn btn-landing style-3 hvr-sweep-to-top" href="#">Get Videoly Now</a>
              </div>
            </div>
        </div>
    </div>
      
  </footer>

<?php wp_footer(); ?>
</body>
</html>

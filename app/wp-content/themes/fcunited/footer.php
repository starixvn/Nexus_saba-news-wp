<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage FCUNITED
 * @since FCUNITED 1.0
 */

						// Widgets area inside page content
						fcunited_create_widgets_area( 'widgets_below_content' );
						?>
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					$fcunited_body_style = fcunited_get_theme_option( 'body_style' );
					if ( 'fullscreen' != $fcunited_body_style ) {
						?>
						</div><!-- </.content_wrap> -->
						<?php
					}

					// Widgets area below page content and related posts below page content
					$fcunited_widgets_name = fcunited_get_theme_option( 'widgets_below_page' );
					$fcunited_show_widgets = ! fcunited_is_off( $fcunited_widgets_name ) && is_active_sidebar( $fcunited_widgets_name );
					$fcunited_show_related = is_single() && fcunited_get_theme_option( 'related_position' ) == 'below_page';

					if ( $fcunited_show_widgets || $fcunited_show_related ) {
						if ( 'fullscreen' != $fcunited_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $fcunited_show_related ) {
							do_action( 'fcunited_action_related_posts' );
						}

						// Widgets area below page content
						if ( $fcunited_show_widgets ) {
							fcunited_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $fcunited_body_style ) {
							?>
							</div><!-- </.content_wrap> -->
							<?php
						}
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Single posts banner before footer
			if ( is_singular( 'post' ) ) {
				fcunited_show_post_banner('footer');
			}
			// Footer
			$fcunited_footer_type = fcunited_get_theme_option( 'footer_type' );
			if ( 'custom' == $fcunited_footer_type && ! fcunited_is_layouts_available() ) {
				$fcunited_footer_type = 'default';
			}
			get_template_part( apply_filters( 'fcunited_filter_get_template_part', "templates/footer-{$fcunited_footer_type}" ) );
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php wp_footer(); ?>

</body>
</html>
<script>
	
	var setUp = {
		months: {
			January:"Tháng Một",
			February:"Tháng Hai",
			March:"Tháng Ba",
			April:"Tháng Tư",
			May: "Tháng Năm",
			June:"Tháng Sáu",
			July:"Tháng Bảy",
			August:"Tháng Tám",
			September: "Tháng Chín",
			October: "Tháng Mười",
			November: "Tháng Mười Một",
			December: "Tháng Mười Hai"
		},
		setTimeVN: function(d) {
			d.each(function(){
				var date  = jQuery(this).html();
				var mapObj = {
					agos:"trước",
					ago:"trước",
					hours:"giờ",
					hour:"giờ",	
					mins:"phút",
					min:"phút",
					seconds:"giây",
					second:"giây",
					days:"ngày",
					day:"ngày",
					months:"tháng",
					month:"tháng",
					years:"năm",
					year:"năm"
				};
				var str = date.replace(/agos|ago|hours|hour|mins|min|seconds|second|days|day|months|month|years|year/gi, function (x) {
				  return mapObj[x];
				});
				jQuery(this).html(str);
			});
		},
		changeCalendar: function(){
			var  week = {
				Monday:"T2",
				Tuesday:"T3",
				Wednesday:"T4",
				Thursday:"T5",
				Friday: "T6",
				Saturday:"T7",
				Sunday:"CN"
			};

			var y = new Date().getFullYear();
			var m =  new Date().getMonth() + 1;
			jQuery('.wp-calendar-table caption').html('Tháng '+m+' Năm '+y);
			var calendar = jQuery('.wp-calendar-table').find('thead tr th');
			calendar.each(function(){
				var w = jQuery(this).attr('title');
				jQuery(this).html(week[w]);
			});
			jQuery('.wp-calendar-nav-prev a').html("« Th"+ new Date().getMonth());
		},
		changeComment: function()  {
			//change comments
			jQuery('.comment_label_author').html('bởi ');
			var comment_title = jQuery('.comments_list_title').html();
			if(typeof comment_title != 'undefined') {
				var str = comment_title.replace(/Comments|Comment/gi, function (x) {
					return "Bình luận";
				});
				jQuery('.comments_list_title').html(str);
				jQuery('.comment-reply-link').html('Trả lời');
				jQuery('.comment-reply-link').css('letter-spacing','0px');
			}
			jQuery('.form-submit .submit').removeAttr('disabled');
			jQuery('.form-submit .submit').prop('disabled',false);
			jQuery('.comment-form-cookies-consent').find('label').html('Lưu thông tin của tôi trong trình duyệt cho lần bình luận tiếp theo ');
			jQuery('.wpgdprc-checkbox').find('label').html('Tôi đồng ý rằng dữ liệu này sẽ được Saba Sports thu thập và lưu trữ. *');
			jQuery('.comment_not_approved').html('Bình luận của bạn đang chờ kiểm duyệt');

			//hide sub comment
			var cmt_list = jQuery('.comments_list .depth-1');
			jQuery.each(cmt_list, function(){
				var child =  jQuery(this).find('.comment');
				if(child.length > 0) {
					var id_cmt = jQuery(this).attr("id");
					jQuery(this).find('.children').hide();
					jQuery(this).find('.comment_body').first().append('<a class="show_comment" data-show=true data-id="'+id_cmt+'" href="javascript:void(0)">'+ child.length + ' trả lời</a>')
				}
			});

			jQuery('.show_comment').on('click',function(){
				var cmt = jQuery(this).data('id');
				var show = jQuery(this).data('show');
				if(show) {
					jQuery('#'+cmt+' .children').show();
					jQuery(this).data('show',false);
				} else {
					jQuery('#'+cmt+' .children').hide();
					jQuery(this).data('show',true);
				}
			});
		},
		changeBreadcrumbAndTitle: function(){
			//change title calendar
			if(typeof jQuery('.sc_layouts_title_title .sc_layouts_title_caption').html() != "undefined") {
				var title = jQuery('.sc_layouts_title_title .sc_layouts_title_caption').html().trim();
				var str = title.split(':');
				if(str[0] == "Daily Archives") {
					jQuery('.sc_layouts_title_title .sc_layouts_title_caption').html("Bài viết ngày " +  str[1]);
				} else if(str[0] == "Monthly Archives") {
					var month_spl = str[1].trim().split(" ");
					jQuery('.sc_layouts_title_title .sc_layouts_title_caption').html("Bài viết " + months[month_spl[0]] + " " + month_spl[1]);
					document.title = months[month_spl[0]] + " " + month_spl[1] + " - Saba Sports";
				} else if(str[0] == "Yearly Archives") {
					jQuery('.sc_layouts_title_title .sc_layouts_title_caption').html("Bài viết năm " +  str[1]);
				} else if(str[0] == "Search"){
					jQuery('.sc_layouts_title_title .sc_layouts_title_caption').html("Kết quả tìm kiếm cho từ khoá: " + str[1]);
				}
			}


			//change breadcrumb calendar
			if(typeof jQuery('.sc_layouts_title_breadcrumbs .current').html() != "undefined") {
				var breadcrumb = jQuery('.sc_layouts_title_breadcrumbs .current').html().split(':');
				if(breadcrumb[0] == "Daily Archives") {
					var date = breadcrumb[1].split('/');
					var d = date[0];
					var m = date[1];
					jQuery('.sc_layouts_title_breadcrumbs .cat_parent').last().html(m);
					jQuery('.sc_layouts_title_breadcrumbs .current').html(d);
				} else if(breadcrumb[0] == "Monthly Archives") {
					var month_spl = str[1].trim().split(" ");
					jQuery('.sc_layouts_title_breadcrumbs .current').html("Bài viết " + months[month_spl[0]] + " " + month_spl[1]);
				} else if(breadcrumb[0] == "Yearly Archives") {
					jQuery('.sc_layouts_title_breadcrumbs .current').html(str[1]);
				} else if(breadcrumb[0] == "Search") {
					jQuery('.sc_layouts_title_breadcrumbs .current').html("Tìm kiếm: " + str[1]);
				}
			}
		},
		changeOthers: function() {
			//redirect Tin nóng, tin mới nhật to tin tức
			jQuery('.sc_item_title_text, .sc_item_filters_title').on('click', function(){
				var location = "<?php echo get_category_link(175)?>";
				window.location.href = location;
			});

			jQuery('.sc_item_filters_title').on('click', function(){
				var location = "<?php echo get_category_link(111)?>";
				window.location.href = location;
			});
			
			jQuery('#custom_html-2 h5').on('click', function(){
				var location = "<?php echo get_page_link(5155)?>";
				window.location.href = location;
			})

			//nav-links
			var nav_text = jQuery('.nav-links').find('.screen-reader-text');
			jQuery.each(nav_text, function(){
				jQuery(this).removeClass('screen-reader-text');
				jQuery(this).addClass('creen-nav-text');
			});
			
			//remove humberger menu
// 			jQuery('.sc_layouts_menu').css('display','block !important');
			
			//Add Text to copy/paste action
			function addLink() {
				//Get the selected text and append the extra info
				var selection = window.getSelection(),
				pagelink = '<br /><br /> Xem thêm tại: ' + document.location.href,
				copytext = selection + pagelink,
				newdiv = document.createElement('div');

				//hide the newly created container
				newdiv.style.position = 'absolute';
				newdiv.style.left = '-99999px';

				//insert the container, fill it with the extended text, and define the new selection
				document.body.appendChild(newdiv);
				newdiv.innerHTML = copytext;
				selection.selectAllChildren(newdiv);
				window.setTimeout(function () {
				document.body.removeChild(newdiv);
				}, 100);
			}
			document.addEventListener('copy', addLink);
		},
		init: function() {
			this.changeCalendar();
			this.changeComment();
			this.changeBreadcrumbAndTitle();
			this.changeOthers();
		}
	};
	
	jQuery(document).ready(function(){
		setUp.init();
	});
	
	
	
</script>
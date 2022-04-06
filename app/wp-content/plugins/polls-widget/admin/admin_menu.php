<?php 

/*############  Poll Admin Menu Class ################*/

class poll_admin_menu{
	
	private $menu_name;	
		
	private $databese_parametrs;
	
	private $plugin_url;
	
	private $plugin_path;
	
	private $text_parametrs;

    /*############  Construct function  ################*/	
	
	function __construct($param){
		$this->menu_name='Polls';
		$this->text_parametrs=array(
			''=>'',
		);
		if(isset($param['databese_parametrs']))
			$this->databese_parametrs=$param['databese_parametrs']; //databese parameters
		else
			$this->databese_parametrs=array();
		
		
		// Set plugin url
		if(isset($param['plugin_url']))
			$this->plugin_url=$param['plugin_url'];
		else
			$this->plugin_url=trailingslashit(dirname(plugins_url('',__FILE__)));
		// Set plugin path
		if(isset($param['plugin_path']))
			$this->plugin_path=$param['plugin_path'];
		else
			$this->plugin_path=trailingslashit(dirname(plugin_dir_path(__FILE__)));
		// Admin style
		add_action('admin_head',array($this,'include_all_admin_styles'));
		//// Add editor new button
		add_filter('mce_external_plugins', array($this,"poll_button_register"));
		add_filter('mce_buttons',  array($this,'poll_add_button'), 0);
		add_action('wp_ajax_poll_window_manager',array($this,'poll_create_window'));
		$this->gutenberg();


	}
	/// Function for gutenberg
	function gutenberg()
	{
		require_once($this->plugin_path.'includes/gutenberg/gutenberg.php');
		$gutenberg=new wpda_polls_gutenberg($this->plugin_url);	
	}
	/// Function for add new button
	function poll_add_button($buttons)
	{
		array_push($buttons, "poll_mce");
		return $buttons;
	}
	
	
	/// Function for register new button
	function poll_button_register($plugin_array)
	{
		$url = $this->plugin_url.'admin/scripts/editor_plugin.js';
		$plugin_array["poll_mce"] = $url;
		return $plugin_array;
	
	}

	    /*############ Admin styles function ##################*/
		
	public function include_all_admin_styles(){
		?><style>#toplevel_page_Poll img{padding-top:4px !important;}</style>
		<script>var poll_admin_url = "<?php echo $this->plugin_url; ?>";</script>
        <script>var poll_admin_ajax= '<?php echo admin_url("admin-ajax.php"); ?>';</script>
		<?php
	}
	
    /*############  Create window function  ################*/		
	
	public function poll_create_window(){
		?>
		<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Polls</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/jquery/jquery.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>        
        <script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
        <base target="_self">
        <style>
            #link .panel_wrapper,
            #link div.current {
                height: 160px !important;
            }

        </style>
    </head>
    <body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="" dir="ltr"  class="forceColors">
    <?php
		global $wpdb;
		$defaults = array( 'title' => '', calendar => '0', theme => '0');
		$instance = wp_parse_args( (array) $instance, $defaults );
		$poll_answers=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polls_question');
		$poll_themes=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polls_templates');
		?>
		<table width="100%" class="paramlist admintable" cellspacing="1" style="margin-bottom: 40px;">
            <tbody>
                <tr>
                    <td class="paramlist_key">
                    	<span class="editlinktip">
                        	<label style="font-size:14px" id="paramsstandcatid-lbl" for="Category" class="hasTip">Select Poll: </label>
                        </span>
                    </td>
                    <td class="paramlist_value" >
                    <select id="poll_answer_id" style="font-size:12px;width:100%" class="inputbox">
                    <option value="0">Select poll</option>
                    <?php                    
                    foreach($poll_answers as $poll_answer)
                    {
                       ?><option value="<?php echo $poll_answer->id?>" ><?php echo $poll_answer->name ?></option><?php
                    }
                    ?>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td class="paramlist_key">
                    	<span class="editlinktip">
                        	<label style="font-size:14px" id="paramsstandcatid-lbl" for="Category" class="hasTip">Select Poll Theme: </label>
                        </span>
                    </td>
                    <td class="paramlist_value" >
                    <select name="" id="poll_theme_id" style="font-size:12px;width:100%" class="inputbox">
                    <option value="0">Select poll theme</option>
                    <?php                    
                    foreach($poll_themes as $poll_theme)
                    {                 
                        ?><option value="<?php echo $poll_theme->id?>"><?php echo $poll_theme->name ?></option><?php                        
                    }
                    ?>
                    </select>
                    </td>
                </tr>
                <tr>
                	<td><br /></td>
                </tr>
            </tbody>
		</table>
   <div class="mceActionPanel">
            <div style="float: left">
                <input type="button" id="cancel" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();"/>
            </div>

            <div style="float: right">
                <input type="submit" id="insert" name="insert" value="Insert" onClick="insert_poll();"/>
                <input type="hidden" name="iden" value="1"/>
            </div>
        </div>
	

    <script type="text/javascript">
        function insert_poll() {
              
			if(jQuery('#poll_answer_id').val()!='0'){
                var tagtext;
                tagtext = '[wpdevart_poll id="' + jQuery('#poll_answer_id').val() + '" theme="' +  jQuery('#poll_theme_id').val() + '"]';
                window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
                tinyMCEPopup.close();
            }
			else{
				tinyMCEPopup.close();
			}
        }

    </script>
    </body>
    </html>
    <?php
	die();
	}
	
	/*############ Menu function ##################*/	
	
	public function create_menu(){
		//Include classes
		require_once($this->plugin_path.'admin/answers_page.php');
		require_once($this->plugin_path.'admin/themplate_page.php');
		require_once($this->plugin_path.'admin/uninstall.php');
		require_once($this->plugin_path.'admin/hire_expert.php');
		global $submenu;
		$polls_sub_slug=str_replace( ' ', '-', $this->menu_name);
		
		//Initial class objects
		$answers_class  =new poll_manager_answers( array( 'plugin_url'=> $this->plugin_url, 'plugin_path' => $this->plugin_path));
		$template_class =new poll_manager_design( array( 'plugin_url' => $this->plugin_url, 'plugin_path' => $this->plugin_path));
		$uninstall_class=new poll_uninstall( array( 'plugin_url' => $this->plugin_url, 'plugin_path' => $this->plugin_path));
		$hire_class		=new wpdevart_polls_hire_expert( array( 'plugin_url' => $this->plugin_url, 'plugin_path' => $this->plugin_path));
		
		
		$manage_page_main = add_menu_page( $this->menu_name, $this->menu_name, 'manage_options', str_replace( ' ', '-', $this->menu_name), array($answers_class, 'controller_page'),$this->plugin_url.'admin/images/icon-polling.png');
							add_submenu_page( str_replace( ' ', '-', $this->menu_name), 'Polls manager', 'Polls manager', 'manage_options', str_replace( ' ', '-', $this->menu_name), array($answers_class, 'controller_page'));
		$page_design	  = add_submenu_page( str_replace( ' ', '-', $this->menu_name), 'Polls design', 'Polls design', 'manage_options', 'Polls-design', array($template_class, 'controller_page'));
		add_submenu_page( str_replace( ' ', '-', $this->menu_name), 'Featured Plugins', 'Featured Plugins', 'manage_options', 'wpdevart-polls-featured-plugins', array($this, 'featured_plugins'));
		$uninstall		  = add_submenu_page( str_replace( ' ', '-', $this->menu_name), 'Uninstall'  , 'Uninstall', 'manage_options', 'Polls-uninstall', array($uninstall_class, 'controller_page'));
		$page_hire	  = add_submenu_page( str_replace( ' ', '-', $this->menu_name), 'Hire an Expert', '<span style="color:#00ff66" >Hire an Expert</span>', 'manage_options', 'Polls-hire-expert', array($hire_class, 'controller_page'));
		
		add_action('admin_print_styles-' .$manage_page_main, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_design, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_hire, array($this,'menu_hire_expert_requeried_scripts'));
		add_action('admin_print_styles-' .$uninstall, array($this,'menu_requeried_scripts'));
		if(isset($submenu[$polls_sub_slug]))
			add_submenu_page( $polls_sub_slug, "Support or Any Ideas?", "<span style='color:#00ff66' >Support or Any Ideas?</span>", 'manage_options',"wpdevar_polls_any_ideas",array($this, 'any_ideas'),156);
		if(isset($submenu[$polls_sub_slug]))
			$submenu[$polls_sub_slug][5][2]=wpdevart_polls_support_url;
	}
	
/*############  Any ideas function  ################*/	
	
	public function any_ideas() {
		
	}
/*############  Required scripts function  ################*/
	
	public function menu_requeried_scripts(){
		wp_enqueue_script('jquery-ui-style');+
		wp_enqueue_script('jquery');	
		wp_enqueue_script('angularejs');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script("jquery-ui-widget");
		wp_enqueue_script("jquery-ui-mouse");
		wp_enqueue_script("jquery-ui-slider");
		wp_enqueue_script("jquery-ui-sortable");
		wp_enqueue_script('wp-color-picker');	
		wp_enqueue_style("jquery-ui-style");
		wp_enqueue_style("admin_style");		
		wp_enqueue_style( 'wp-color-picker' );
			
	}
	public function menu_hire_expert_requeried_scripts(){
		wp_enqueue_style("wpdevart_polls_hire_expert",$this->plugin_url.'admin/styles/hire_expert.css');
	}
	/*###################### Featured plugin function ##################*/	
	public function featured_plugins(){
		$plugins_array=array(
			'gallery_album'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/gallery-album-icon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-gallery-plugin',
						'title'			=>	'WordPress Gallery plugin',
						'description'	=>	'The gallery plugin is a useful tool that will help you to create Galleries and Albums. Try our nice Gallery views and awesome animations.'
						),		
			'countdown-extended'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/icon-128x128.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-countdown-extended-version/',
						'title'			=>	'WordPress Countdown Extended',
						'description'	=>	'Countdown extended is a fresh and extended version of the countdown timer. You can easily create and add countdown timers to your website.'
						),							
			'coming_soon'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/coming_soon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-coming-soon-plugin/',
						'title'			=>	'Coming soon and Maintenance mode',
						'description'	=>	'Coming soon and Maintenance mode plugin is an awesome tool to show your visitors that you are working on your website to make it better.'
						),
			'Contact forms'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/contact_forms.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-contact-form-plugin/',
						'title'			=>	'Contact Form Builder',
						'description'	=>	'Contact Form Builder plugin is a handy tool for creating different types of contact forms on your WordPress websites.'
						),	
			'Booking Calendar'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/Booking_calendar_featured.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-booking-calendar-plugin/',
						'title'			=>	'WordPress Booking Calendar',
						'description'	=>	'WordPress Booking Calendar plugin is an awesome tool to create a booking system for your website. Create booking calendars in a few minutes.'
						),
			'Pricing Table'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/Pricing-table.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-pricing-table-plugin/',
						'title'			=>	'WordPress Pricing Table',
						'description'	=>	'WordPress Pricing Table plugin is a nice tool for creating beautiful pricing tables. Use WpDevArt pricing table themes and create tables just in a few minutes.'
						),
			'chart'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/chart-featured.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-organization-chart-plugin/',
						'title'			=>	'WordPress Organization Chart',
						'description'	=>	'WordPress organization chart plugin is a great tool for adding organizational charts to your WordPress websites.'
						),						
			'youtube'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/youtube.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-youtube-embed-plugin',
						'title'			=>	'WordPress YouTube Embed',
						'description'	=>	'YouTube Embed plugin is a convenient tool for adding videos to your website. Use YouTube Embed plugin for adding YouTube videos in posts/pages, widgets.'
						),
            'facebook-comments'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/facebook-comments-icon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-comments-plugin/',
						'title'			=>	'Wpdevart Social comments',
						'description'	=>	'WordPress Facebook comments plugin will help you to display Facebook Comments on your website. You can use Facebook Comments on your pages/posts.'
						),						
			'countdown'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/countdown.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-countdown-plugin/',
						'title'			=>	'WordPress Countdown plugin',
						'description'	=>	'WordPress Countdown plugin is a nice tool for creating countdown timers for your website posts/pages and widgets.'
						),
			'lightbox'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/lightbox.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-lightbox-plugin',
						'title'			=>	'WordPress Lightbox plugin',
						'description'	=>	'WordPress Lightbox Popup is a highly customizable and responsive plugin for displaying images and videos in the popup.'
						),
			'vertical_menu'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/vertical-menu.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-vertical-menu-plugin/',
						'title'			=>	'WordPress Vertical Menu',
						'description'	=>	'WordPress Vertical Menu is a handy tool for adding nice vertical menus. You can add icons for your website vertical menus using our plugin.'
						),						
			'facebook'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/facebook.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-like-box-plugin',
						'title'			=>	'Social Like Box',
						'description'	=>	'Facebook like box plugin will help you to display Facebook like box on your website, just add Facebook Like box widget to the sidebar or insert it into posts/pages and use it.'
						),
			'duplicate_page'=>array(
						'image_url'		=>	$this->plugin_url.'admin/images/featured_plugins/featured-duplicate.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-duplicate-page-plugin-easily-clone-posts-and-pages/',
						'title'			=>	'WordPress Duplicate page',
						'description'	=>	'Duplicate Page or Post is a great tool that allows duplicating pages and posts. Now you can do it with one click.'
						),						
					
			
		);
		?>
        <style>
         .featured_plugin_main{
			background-color: #ffffff;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			float: left;
			margin-right: 30px;
			margin-bottom: 30px;
			width: calc((100% - 90px)/3);
			border-radius: 15px;
			box-shadow: 1px 1px 7px rgba(0,0,0,0.04);
			padding: 20px 25px;
			text-align: center;
			-webkit-transition:-webkit-transform 0.3s;
			-moz-transition:-moz-transform 0.3s;
			transition:transform 0.3s;   
			-webkit-transform: translateY(0);
			-moz-transform: translateY0);
			transform: translateY(0);
			min-height: 344px;
		 }
		.featured_plugin_main:hover{
			-webkit-transform: translateY(-2px);
			-moz-transform: translateY(-2px);
			transform: translateY(-2px);
		 }
		.featured_plugin_image{
			max-width: 128px;
			margin: 0 auto;
		}
		.blue_button{
    display: inline-block;
    font-size: 15px;
    text-decoration: none;
    border-radius: 5px;
    color: #ffffff;
    font-weight: 400;
    opacity: 1;
    -webkit-transition: opacity 0.3s;
    -moz-transition: opacity 0.3s;
    transition: opacity 0.3s;
    background-color: #7052fb;
    padding: 10px 22px;
    text-transform: uppercase;
		}
		.blue_button:hover,
		.blue_button:focus {
			color:#ffffff;
			box-shadow: none;
			outline: none;
		}
		.featured_plugin_image img{
			max-width: 100%;
		}
		.featured_plugin_image a{
		  display: inline-block;
		}
		.featured_plugin_information{	

		}
		.featured_plugin_title{
	color: #7052fb;
	font-size: 18px;
	display: inline-block;
		}
		.featured_plugin_title a{
	text-decoration:none;
	font-size: 19px;
    line-height: 22px;
	color: #7052fb;
					
		}
		.featured_plugin_title h4{
			margin: 0px;
			margin-top: 20px;		
			min-height: 44px;	
		}
		.featured_plugin_description{
			font-size: 14px;
				min-height: 63px;
		}
		@media screen and (max-width: 1460px){
			.featured_plugin_main {
				margin-right: 20px;
				margin-bottom: 20px;
				width: calc((100% - 60px)/3);
				padding: 20px 10px;
			}
			.featured_plugin_description {
				font-size: 13px;
				min-height: 63px;
			}
		}
		@media screen and (max-width: 1279px){
			.featured_plugin_main {
				width: calc((100% - 60px)/2);
				padding: 20px 20px;
				min-height: 363px;
			}	
		}
		@media screen and (max-width: 768px){
			.featured_plugin_main {
				width: calc(100% - 30px);
				padding: 20px 20px;
				min-height: auto;
				margin: 0 auto 20px;
				float: none;
			}	
			.featured_plugin_title h4{
				min-height: auto;
			}	
			.featured_plugin_description{
				min-height: auto;
					font-size: 14px;
			}	
		}

        </style>
      
		<h1 style="text-align: center;font-size: 50px;font-weight: 700;color: #2b2350;margin: 20px auto 25px;line-height: 1.2;">Featured Plugins</h1>
		<?php foreach($plugins_array as $key=>$plugin) { ?>
		<div class="featured_plugin_main">
			<div class="featured_plugin_image"><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><img src="<?php echo $plugin['image_url'] ?>"></a></div>
			<div class="featured_plugin_information">
				<div class="featured_plugin_title"><h4><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><?php echo $plugin['title'] ?></a></h4></div>
				<p class="featured_plugin_description"><?php echo $plugin['description'] ?></p>
				<a target="_blank" href="<?php echo $plugin['site_url'] ?>" class="blue_button">Check The Plugin</a>
			</div>
			<div style="clear:both"></div>                
		</div>
		<?php } 
	
	}
	
	
}
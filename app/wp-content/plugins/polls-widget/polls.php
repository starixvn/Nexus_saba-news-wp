<?php
/**
 * Plugin Name: Polls
 * Plugin URI: https://wpdevart.com/wordpress-polls-plugin/
 * Description: WordPress Polls plugin is an nice tool for creating polls and survey forms. You can use our polls on widgets, posts and pages. WordPress Polls plugin have user-friendly admin panel, so you can create polls and survey forms easily and quickly.   
 * Version: 1.6.3
 * Author: wpdevart
 * Author URI:    https://wpdevart.com
 * License URI: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
 

class polls{
	// required variables
	
	private $plugin_url;
	
	private $plugin_path;
	
	private $version;
	
	public $options;
	
	
	function __construct(){
		
		$this->plugin_url  = trailingslashit( plugins_url('', __FILE__ ) );
		$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
		//
		define("wpdevart_polls_support_url","https://wordpress.org/support/plugin/polls-widget");
		
		$this->version     = 1.0;
		$this->call_base_filters();
		$this->install_databese();
		$this->create_admin_menu();	
		$this->front_end();
		
	}

    /*############  Menu function  ################*/	
	
	private function create_admin_menu(){
		
		require_once($this->plugin_path.'admin/admin_menu.php');
		
		$admin_menu = new poll_admin_menu(array('plugin_url' => $this->plugin_url,'plugin_path' => $this->plugin_path));
		
		add_action('admin_menu', array($admin_menu,'create_menu'));
		
	}

    /*############  Install database function  ################*/	
	
	private function install_databese(){	
		
		require_once($this->plugin_path.'includes/install_database.php');
		
		$poll_install_databese = new polls_install_database(array('plugin_url' => $this->plugin_url,'plugin_path' => $this->plugin_path));
	}
	
    /*############  Front end function  ################*/		
	
	public function front_end(){
				
		require_once($this->plugin_path.'fornt_end/front_end.php');
		global $poll_front_end;
		$poll_front_end = new poll_front_end(array('menu_name' => 'Polls','plugin_url' => $this->plugin_url,'plugin_path' => $this->plugin_path));
		require_once($this->plugin_path.'fornt_end/fornt_end_widget.php');
		
		
		
	}

    /*############  Function for registering the required scripts ################*/
	
	public function registr_requeried_scripts(){
		
		wp_register_script('angularejs',$this->plugin_url.'admin/scripts/angular.min.js');
		wp_register_script('poll_front_end_script',$this->plugin_url.'fornt_end/scripts/scripts_front_end_poll.js');
		wp_register_style('admin_style',$this->plugin_url.'admin/styles/admin_themplate.css');
		wp_register_style('front_end_poll',$this->plugin_url.'fornt_end/styles/baze_styles_for_poll.css');
		wp_register_style('jquery-ui-style',$this->plugin_url.'admin/styles/jquery-ui.css');
		wp_register_script('wpda_polls_gutenberg_js',$this->plugin_url.'includes/gutenberg/block.js',array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore' ));
		wp_register_style('wpda_polls_gutenberg_css',$this->plugin_url.'includes/gutenberg/style.css');
	}

    /*############  Call base filters function  ################*/	
	
	public function call_base_filters(){
		add_action( 'init',  array($this,'registr_requeried_scripts') );
		//for_upgrade
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this,'plugin_activate_sublink') );
		add_action('widgets_init',array($this,"registr_widget"));
		
	}
	
    /*############  Register a widget function  ################*/		
	
	public function registr_widget(){
		return register_widget("poll_widget");
	}
  	public function plugin_activate_sublink($links){
		$plugin_submenu_added_link=array();		
		 $added_link = array(
		 '<a target="_blank" style="color: #7052fb; font-weight: bold; font-size: 13px;" href="https://wpdevart.com/wordpress-polls-plugin">Upgrade to Pro</a>',
		 );
		$plugin_submenu_added_link=array_merge( $plugin_submenu_added_link, $added_link );
		$plugin_submenu_added_link=array_merge( $plugin_submenu_added_link, $links );
		return $plugin_submenu_added_link;
	}
}
$poll = new polls();

?>
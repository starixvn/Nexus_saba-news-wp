<?php
class wpda_polls_gutenberg{	
	private $plugins_url;
	function __construct($plugins_url){
		$this->plugins_url=$plugins_url;
		$this->hooks_for_gutenberg();
	}
	private function hooks_for_gutenberg(){
		add_action( 'init', array($this,'guthenberg_init') );
	}
	public function guthenberg_init(){
		if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
		}
		register_block_type( 'wpdevart-polls/polls', array(
			'style' => 'wpda_polls_gutenberg_css',
			'editor_script' => 'wpda_polls_gutenberg_js',
		) );
		wp_add_inline_script(
			'wpda_polls_gutenberg_js',
			sprintf('var wpda_polls_gutenberg = { polls: %s, themes: %s,other_data: %s};', json_encode($this->get_polls(),JSON_PRETTY_PRINT), json_encode($this->get_themes(),JSON_PRETTY_PRINT), json_encode($this->other_dates(),JSON_PRETTY_PRINT)),
			'before'
		);
	}
	
    /*############  Get polls function  ################*/		
	
	private function get_polls(){		
		global $wpdb;
		$polls=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polls_question');
		$array=array();
		foreach($polls as $poll){
			$array[$poll->id]=$poll->name;
		}
		return $array;
	}
	private function other_dates(){
		$array=array('icon_src'=>$this->plugins_url."admin/images/icon-polling.png");
		return $array;
	}
	
    /*############  Get themes function  ################*/		
	
	private function get_themes(){
		global $wpdb;
		$poll_themes=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polls_templates');
		foreach($poll_themes as $theme){
			$array[$theme->id]=$theme->name;
		}
		return $array;
	}
	
}


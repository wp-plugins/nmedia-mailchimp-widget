<?php
/*
Plugin Name: Simple MailChimp Email List Subscriber
Plugin URI: http://www.najeebmedia.com/wordpress-mailchimp-plugin-2-0-released/
Description: Form to capture email subscriptions and send them to your MailChimp account list
Version: 2.0
Author: Najeeb Ahmad
Author URI: http://www.najeebmedia.com/
*/

/*ini_set('display_errors',1);
error_reporting(E_ALL);*/

class nmMailChimp extends WP_Widget {
	
	 
	
	/*
	** constructor
	*/	
	function nmMailChimp() {
		parent::WP_Widget(  'nmedia_mail_chimp', 
							'MailChimp Widget',
							array('description' => 'MailChimp Widget by najeebmedia.com.'));	
		
	}
	
	
	/*
	** loading js/jquery stuff
	*/
	public function load_js()
	{
		wp_deregister_script( 'jquery' );
    	wp_register_script( 'jquery', plugins_url('js/jquery-1.4.4.min.js', __FILE__));
		wp_enqueue_script( 'jquery' );
	
		wp_register_script( 'nm_mailchimp_custom_script', 
							plugins_url('js/nm_mc_js.js', __FILE__), 
							'jquery');
							
							
		wp_enqueue_script('nm_mailchimp_custom_script');
	
	}
	
	/*
	** setting option page in wp admin
	*/  
  	public function set_up_admin_page () {
		
	  	add_submenu_page(   'options-general.php', 
							'MailChimp Widget Options', 
						 	'MailChimp Widget', 
							'activate_plugins', 
							__FILE__, 
							array('nmMailChimp', 'admin_page'));		
	}
	
	
	public function admin_page()
	{
		$file = dirname(__FILE__).'/options.php';
		include($file);
	}
	
  
  	/*
	** display widget
	*/	 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['nm_mc_title']) ? '&nbsp;' : apply_filters('widget_title', $instance['nm_mc_title']);
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		
		nmMailChimp::nm_load_form(	$instance['nm_mc_list_id'], 
									$instance['nm_mc_show_names'],
									$instance['nm_mc_box_title']);
		
		echo $after_widget;
	}
	
	
	/*
	** update/save function
	*/	 	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['nm_mc_title'] = strip_tags($new_instance['nm_mc_title']);
		$instance['nm_mc_box_title'] = strip_tags($new_instance['nm_mc_box_title']);
		$instance['nm_mc_list_id'] = strip_tags($new_instance['nm_mc_list_id']);
		$instance['nm_mc_show_names'] = strip_tags($new_instance['nm_mc_show_names']);
		return $instance;
	}
	
	/*
	** admin control form
	*/	 	
	function form($instance) {
		$default = 	array( 'nm_mc_title' 		=> __('MailChimp Widget'),
						   'nm_mc_box_title'	=> __('Your Caption Here'),
						   'nm_mc_list_id' 		=> 0,
						   'nm_mc_show_names' 	=> 0,
						   );
						   
		$instance = wp_parse_args( (array) $instance, $default );
		
		$field_id_title = $this->get_field_id('nm_mc_title');
		$field_name_title = $this->get_field_name('nm_mc_title');
		
		$field_id_box_title = $this->get_field_id('nm_mc_box_title');
		$field_name_box_title = $this->get_field_name('nm_mc_box_title');
		
		$field_id_list = $this->get_field_id('nm_mc_list_id');
		$field_name_list = $this->get_field_name('nm_mc_list_id');
		
				
		$field_id_names = $this->get_field_id('nm_mc_show_names');
		$field_name_names = $this->get_field_name('nm_mc_show_names');
		
		
				
		$api_dir = dirname(__FILE__).'/api_mailchimp/mcapi_lists.php';
		include($api_dir);
		
		$file = dirname(__FILE__).'/control.php';
		include($file);
		
		
	}
  
 
  function nm_load_form($list_id, $show_names, $boxTitle)
  {
  	
	$file = dirname(__FILE__).'/mc-box.php';
	include($file);
  }
  
  
  /*
  ** Unistalling the plugin
  */
  
  function nm_mc_unistall()
  {
		global $nm_mc_options;
		
		foreach ($nm_mc_options as $value) {
	    	delete_option( $value['id'] );
    	}
  }
  
  
  /*
  ** Enqueue style-file, if it exists.
  */
  function add_nm_stylesheet() {
	$myStyleFile = dirname(__FILE__).'/mc-box.css';	
     if ( file_exists($myStyleFile) ) {
        wp_register_style('nm_mailchimp_stylesheet', plugins_url('mc-box.css', __FILE__));
   		wp_enqueue_style( 'nm_mailchimp_stylesheet');
	 }        
  }
  
  // plugin localization
	function nm_mc_textdomain() {
		load_plugin_textdomain('nm_mailchimp_plugin', false, dirname(plugin_basename( __FILE__ )) . '/locale/');
	}
  
  
}

// activate textdomain for translations
add_action('init', array('nmMailChimp', 'nm_mc_textdomain'));


/* book: register widget when loading the WP core */
add_action('widgets_init', 'nm_mc_register_widgets');
/* hook: loading js stuff */
add_action('wp_enqueue_scripts', array('nmMailChimp', 'load_js'));
/* hook: styilng */
add_action('wp_print_styles', array('nmMailChimp', 'add_nm_stylesheet'));
/* hook deactivating plugin */
register_deactivation_hook(__FILE__, array('nmMailChimp', 'nm_mc_unistall'));

$options_file = dirname(__FILE__).'/plugin-options.php';
include ($options_file);
/*$options_file = dirname(__FILE__).'/get-plugin-options.php';
include ($options_file);*/

function nm_mc_register_widgets(){
	// curl need to be installed
	register_widget('nmMailChimp');
}

?>
<?php 
/**
 * Plugin Name: Mailchimp Subscription Form
 * Plugin URI: 
 * Description: Create subscriptions Forms with Mailchimp Lists and Create Campaigns with Wordpress Posts and Pages (PRO).
 * Version: 4.3
 * Author: nmedia
 * Author URI: http://najeebmedia.com
 * Text Domain: nm-mailchimp
 */
 
/*ini_set('display_errors',1);
error_reporting(E_ALL);*/
 

/*
 * loading plugin config file
 */
$_config = plugin_dir_path( __FILE__ ) . 'config.php';
if( file_exists($_config))
	include_once($_config);
else
	die('Reen, Reen, BUMP! not found '.$_config);


/* ======= the plugin main class =========== */
$_plugin = plugin_dir_path( __FILE__ ) . 'classes/plugin.class.php';
if( file_exists($_plugin))
	include_once($_plugin);
else
	die('Reen, Reen, BUMP! not found '.$_plugin);

/*
 * [1]
 * TODO: just replace class name with your plugin
 */
 
$nm_mailchimp = NMMailChimp::get_instance();
NMMailChimp::init();

if( is_admin() ){

	$_admin = dirname(__FILE__).'/classes/admin.class.php';
	if( file_exists($_admin))
		include_once($_admin );
	else
		die('file not found! '.$_admin);

	$nm_mailchimp_admin = new NMMailChimp_Admin();
}

/*
 * activation/install the plugin data
*/
register_activation_hook( __FILE__, array('NMMailChimp', 'activate_plugin'));
register_deactivation_hook( __FILE__, array('NMMailChimp', 'deactivate_plugin'));

/**
 * delete options, tables or anything else
 */
 if(defined('WP_UNINSTALL_PLUGIN') ){
 
  //delete options, tables or anything else
   
}



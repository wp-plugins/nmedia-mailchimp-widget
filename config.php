<?php
/*
 * this file contains pluing meta information and then shared
 * between pluging and admin classes
 * 
 */

/*
 * TODO: change the function name
*/

$plugin_meta = array();
function get_plugin_meta_nm_mailchimp(){
	
	$plugin_meta = array(
							'name'				=> 'Mailchimp Campaign',
							'shortname'		=> 'nm_mailchimp',
							'path'			=> plugin_dir_path( __FILE__ ),
							'url'			=> plugin_dir_url( __FILE__ ),
							'plugin_version'=> 4.0,
							'logo'			=> plugin_dir_url( __FILE__ ) . 'images/logo.png',
							'menu_position' => '66');
	
	//print_r($plugin_meta);
	
	return $plugin_meta;
}


/**
 * printing the formatted array
 */
 function pa_nm_mailchimp( $arr ){
 	
	echo '<pre>';
		print_r( $arr );
	echo '</pre>';
 }
 


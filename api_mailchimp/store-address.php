<?php
/*ini_set('display_errors',1);
error_reporting(E_ALL);*/

$path = $_POST['abs_path'] . 'wp-load.php';
//echo $path; die;
require( $path );



function storeAddress($api_key, $thanks){
	$email   	= sanitize_text_field( $_POST['email'] );
	$list_id 	= sanitize_text_field( $_POST['list_id'] );
	$full_name  = sanitize_text_field( $_POST['fname'] );	
	
	// Validation
	if(!$email){ return "No email address provided"; } 

	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $_POST['email'])) {
		return "Email address is invalid"; 
	}

	require_once('MCAPI.class.php');
	// grab an API Key from http://admin.mailchimp.com/account/api/
	//$api = new MCAPI('1c75426364cad9ce8ec46a6aae650a87-us2');		//Nmedia mailchimp account
	if($api_key == '')
	{
		return 'No API key found, please set API from Widget section';
	}
	
	$api = new MCAPI($api_key);		
			
	// grab your List's Unique Id by going to http://admin.mailchimp.com/lists/
	if($list_id == '')
	{
		return 'No List ID found, please set List ID from Widget section';
	}
	
	
	// Merge variables are the names of all of the fields your mailing list accepts
	// Ex: first name is by default FNAME
	// You can define the names of each merge variable in Lists > click the desired list > list settings > Merge tags for personalization
	// Pass merge values to the API in an array as follows
	
	//now geting box names
	$names = explode(',', $full_name);
	
	$mergeVars = array( 'FNAME'	=> $names['0'],
						'LNAME'	=> $names['1']);
	
	if($api->listSubscribe($list_id, $_POST['email'], $mergeVars) === true) {
		// It worked!
		if($thanks == '')
			return 'Success! Check your email to confirm sign up.';
		else
			return $thanks;
	}else{
		// An error ocurred, return error message	
		return 'Error: ' . $api->errorMessage;
	}
	
}

// If being called via ajax, autorun the function
//if($_POST['ajax']){ echo storeAddress(); }
$api_key = get_option('nm_mc_api_key');
$thanksMessage = get_option('nm_mc_thanks_message');
echo storeAddress($api_key, $thanksMessage);
?>
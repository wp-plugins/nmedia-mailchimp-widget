<?php
/*
This script is written by Najeeb Ahmad, NajeebMedia.com

postToMailChimp class is interface class between NMedia Mailchimp plugin and Mailchimp API class.
Do not modify this.
*/


$path = $_POST['abs_path'] . 'wp-load.php';
//echo $path; die;
require( $path );

class postToMailChimp
{
	var $email;
	var $list_id;
	var $full_name;
	var $address;
	
	var $api_key;
	var $thanks;
	
	var $err;
	
	/*
	** following function is saving subscriber data to mailchimp list
	*/
	function saveToList()
	{
		
		require_once('MCAPI.class.php');
	
		$comm = new MCAPI($this -> api_key);		

		
		$names 	 = explode(',', $this -> full_name);
		$address = explode(',' $this -> address);
		
		$mergeVars = array( 'FNAME'	=> $names['0'],
							'LNAME'	=> $names['1']);
		
		if($comm->listSubscribe($this -> list_id, $this -> email, $mergeVars) === true) {
			if($this -> thanks == '')
				return 'Success! Check your email to confirm sign up.';
			else
				return $this -> thanks;
		}else{
			// An error ocurred, return error message	
			return 'Error: ' . $comm->errorMessage;
		}
	}
	
	
	/*
	** setting data posted here
	*/
	function setPostedData($email1, $list_id1, $fname1, $api_key1, $thanks1, $addr1='')
	{
		if(!$email1)
		{ 
			$this -> err = "Email is required";
			return false;
		} 

		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $email1)) 
		{
			$this -> err =  "Email address is invalid"; 
			return false;
		}
		
		if($api_key1 == '')
		{
			$this -> err =  'No API key found, please set API from Widget section';
			return false;
		}
		
		if($list_id1 == '')
		{
			$this -> err =  'No List ID found, please set List ID from Widget section';
			return false;
		}
		
		
		$this -> email   	= sanitize_text_field( $email1 );
		$this -> list_id 	= sanitize_text_field( $list_id1 );
		
		if($fname1)
			$this -> full_name = sanitize_text_field( $fname1 );
		
		if( $addr1 )
			$this -> address = sanitize_text_field( $addr1 );
			
		$this -> api_key	= sanitize_text_field( $api_key1 );
		$this -> thanks	= sanitize_text_field( $thanks1 );
		
		return true;
		
		
	}
}


$api_key = get_option('nm_mc_api_key');
$thanksMessage = get_option('nm_mc_thanks_message');

$mailchimp = new postToMailChimp();

if($mailchimp -> setPostedData( $_POST['email'], 
								$_POST['list_id'], 
								$_POST['fname'], 
								$api_key, 
								$thanksMessage,
								$_POST['address']))
	echo $mailchimp -> saveToList();
else
	echo $mailchimp -> err;

?>
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
	var $vars = array();
	
	var $api_key;
	var $thanks;
	
	var $err;
	var $errMessage;
	
	/*
	** following function is saving subscriber data to mailchimp list
	*/
	function saveToList()
	{
		require_once('MCAPI.class.php');
	
		$comm = new MCAPI($this -> api_key);		

		
		$names 	 = explode(',', $this -> full_name);
				
		//var_dump($mergeVars); exit;
		
		if($comm->listSubscribe($this -> list_id, $this -> email, $this -> vars) === true) {
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
	** validating posted data
	*/
	function validateCredentials()
	{
		global $arrVars;
		
		
		$i = 0;
		$this -> errMessage = '<div class="nm_mc_error">';
		foreach($arrVars as $key => $val):
		
			$tag = $val -> tag;
			
			if($val -> req == 1 and $_POST['form_meta'][$i] == '')
			{
				$this -> err = true;
				$this -> errMessage .= '<p>'.$val -> tag." is required<p>";
			}
			
			$this -> vars [$tag] = sanitize_text_field($_POST['form_meta'][$i]);
			$i++;
		endforeach;
		
		$this -> email = $this -> vars['EMAIL'];
		
		
		/* populating checking validation for vars, email, list id and api key */
		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $this -> email)) 
		{
			$this -> errMessage .=  '<p>Email address is invalid '.$this -> email.'</p>'; 
			$this -> err = true;
		}
		
		if($this -> api_key == '')
		{
			$this -> errMessage .= '<p>No API key found, please set API from Widget section</p>';
			$this -> err = true;
		}
		
		if($this -> list_id == '')
		{
			$this -> errMessage .=  '<p>No List ID found, please set List ID from Widget section</p>';
			$this -> err = true;
		}
		/* checking validation for email, list id and api key */
		
		$this -> errMessage .= '</div>';
				
	}
}


$mailchimp = new postToMailChimp();

$form = nmMailChimp::getForm($_POST['form_id']);
/*print_r($_POST['form_meta']);
exit;*/

$meta = json_decode($form -> form_meta);
$arrVars = $meta -> vars;

$mailchimp -> list_id = $meta -> list_id;
$mailchimp -> api_key = get_option('nm_mc_api_key');
$mailchimp -> thanks  = get_option('nm_mc_thanks_message');

$mailchimp -> validateCredentials();

/* pushing list interest/groups into VARS array */
$interest = array();
foreach($meta -> interest as $grouping)
{
	$temp['id'] 	= $grouping -> id;
	$temp['groups']	= $grouping -> groups;
	
	array_push($interest, $temp);
	$mailchimp -> vars ['GROUPINGS'] = $interest;
}
/* pushing list interest/groups into VARS array */


if($mailchimp -> err)
{
	$mailchimp -> err = false;
	echo $mailchimp -> errMessage;
}
else
{
	echo $mailchimp -> saveToList();
}

?>
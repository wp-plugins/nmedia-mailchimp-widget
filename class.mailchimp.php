<?php
/*
** This class is connector b/w Nmedia mailchimp plugin and MCAPI
Author: ceo@najeebmedia.com (Najeeb Ahmad)
*/

$parent = dirname(__FILE__) . '/api_mailchimp/MCAPI.class.php';
include ($parent);

class clsMailchimp extends MCAPI
{
	var $mc;
	
	function __construct()
	{
		$this -> mc = new MCAPI(get_option('nm_mc_api_key'));
		
	}
	
	
	/*
	** Getting Mailchimp account list
	*/
	function getAccountLists()
	{
		$retval = $this -> mc -> lists();
		
		if ($this -> mc -> errorCode){
		  	_e("You did not enter API Keys please enter your API Keys from Nmedia Mailchimp Setting area");
		 }
		 else
		 {
			 return $retval['data'];
		 }
		 
	}
	
	
	/*
	** Getting List vars
	*/
	function getMergeVars($list_id)
	{
		$retval = $this -> mc -> listMergeVars($list_id);
		
		if ($this -> mc -> errorCode){
		  	echo $this -> mc -> errorMessage;
		 }
		 else
		 {
			 return $retval;
		 }
		 
	}
	
	
	/*
	** Getting List ineterst gruops
	*/
	function getListGroups($list_id)
	{
		$retval = $this -> mc -> listInterestGroupings($list_id);
		
		if ($this -> mc -> errorCode){
		  	return $this -> mc -> errorMessage;
		 }
		 else
		 {
			 return $retval;
		 }
	}
}
?>
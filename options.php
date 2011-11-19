<?php
//print_r(get_option('widget_nmedia_mail_chimp'));
if ( count($_POST) > 0 && isset($_POST['btn_options']) )
	{
		/*$data['nm_mc_apikey'] = attribute_escape($_POST['api_key']);
		$data['nm_mc_list_id'] = attribute_escape($_POST['list_id']);
		$data['nm_mc_title'] = attribute_escape($_POST['title']);
		update_option('widget_nmedia_mail_chimp', $data);*/	
		delete_option('nm_mc_apikey');
		add_option('nm_mc_apikey', $_POST['api_key']);	 
}

$api_key = get_option('nm_mc_apikey');
//$data = get_option('widget_nmedia_mail_chimp');


?>

<h1>Nmedia MailChimp Widget Settings</h1>
<form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])?>" method="post">
<table width="800" class="xet_settings_tbl">
  <tr>
    <td valign="middle">API Key</td>
    <td valign="top"><input type="text" name="api_key" id="api_key" size="63" value="<?php echo $api_key?>" /><br>
	<span class="nm_help">e.g. <a href="http://www.najeebmedia.com/2011/07/18/where-can-i-find-my-mailchimp-api-key/" target="_new">How to get API key?</a></span>
    </td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td valign="top"><input type="submit" value="Save Settings" name="btn_options" /></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
</table>
</form>
<br /><br />

<h2>Donation</h2>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="FSTT6UVR5KFN6">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
<br />

<a href="http://www.najeebmedia.com/"><img src="http://www.najeebmedia.com/logo.png" alt="Nmedia Logo" border="0" width="175" /></a>
<p>
Nmedia providing Web Application Development and Designing services with a team of Skilled and Professional Buddies. We have developed many E-commerce, Wordpress, Bespoke web projects at very reasonable prices. Must see our projects and feedbacks from our respected clients by visiting company site: <a href="http://www.najeebmedia.com/">Nmedia</a><br />
<br />
Thanks<br />
Najeeb Ahmad<br />
<a href="mailto:ceo@najeebmedia.com">ceo@najeebmedia.com</a>
</p>
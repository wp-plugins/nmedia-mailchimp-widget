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

echo '<link rel="stylesheet" type="text/css" href="'.plugins_url('nm_mailchimp_style.css', __FILE__).'"/>';

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
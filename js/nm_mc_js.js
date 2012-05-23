// JavaScript Document
String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}



function postToMailChimp(post_url, abspath, widget_id, redirect_to)
{
	//var img_loading = '<img src="http://theproductionarea.net/wp-content/plugins/nmedia-mailchimp-pro-v3/images/loading.gif">';
	jQuery("#nm-mc-loading").show();
	
	var e 		 = jQuery('#nm_mc_email-'+widget_id).val();
	var formid	 = jQuery('#nm_mc_form_id-'+widget_id).val();
	
	var formmeta = Array();
	jQuery("input[name^=nm-form-meta]").each(function (index)
	{
	    //alert(jQuery(this).val());
		formmeta.push(jQuery(this).val());
	});
		
	jQuery('#nm_mc_email-'+widget_id).val('Subscribing...');
	
	//alert(post_url);
	jQuery.post(post_url, 	{email: e,
							form_id: formid,
							form_meta: formmeta,
							abs_path: abspath}, function(data){
			//alert(data);
			if(data.status == 'success' && redirect_to != '')
			{
				window.location = redirect_to;
			}
			else
			{
				jQuery("#nm-mc-loading").hide();
				jQuery("#mc-response-area").html(data.message);
				jQuery('#nm_mc_email-'+widget_id).val('');
				jQuery('#nm_mc_fullname-'+widget_id).val('');
			}
	},'json');
}

// JavaScript Document
String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}



function postToMailChimp(post_url, abspath, widget_id)
{
	jQuery("#mc-working-area").show();
	
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
			jQuery("#mc-working-area").html(data);
			jQuery('#nm_mc_email-'+widget_id).val('');
			jQuery('#nm_mc_fullname-'+widget_id).val('');
	});
}

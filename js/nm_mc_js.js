// JavaScript Document
jQuery(document).ready(function(){
	
	
});


function postToMailChimp(post_url, abspath, widget_id)
{
	var e 		 = jQuery('#nm_mc_email-'+widget_id).val();
	var listid	 = jQuery('#nm_mc_list_id-'+widget_id).val();
	var fullname = jQuery('#nm_mc_fullname-'+widget_id).val();	
	
	jQuery('#nm_mc_email-'+widget_id).val('Subscribing...');
	
	//alert(post_url);
	jQuery.post(post_url, 	{email: e,
							list_id: listid,
							fname: fullname,
							abs_path: abspath}, function(data){
			alert(data);
			jQuery('#nm_mc_email').val('');
			jQuery('#nm_mc_fullname').val('');
	});
}

/*
* setting the design based on options selected by user
*/
function nm_setDesign(widget_id, button_bg, button_border, button_text, bgImageURL, box_bg, box_border, title_color, title_size_type)
{
	
	var button 		= jQuery('#nm_mc_button-'+widget_id);
	var box 		= jQuery('#box-'+widget_id);
	var title 		= jQuery('#nm_mc_title-'+widget_id);
	
	button.css({'background-color':button_bg});
	button.css({'border':button_border});
	button.css({'color':button_text});
	
	//setting box bg image or color
	if(bgImageURL == '')
	{
		box.css({'background-color':box_bg});
		box.css({'border':box_border});
	}else
	{
		box.css('background-image', 'url(' + bgImageURL + ')');
	}
	
	title.css({'font':title_size_type});
	title.css({'color':title_color});
	
	nm_setLayout(widget_id);
		
}



/*
** setting elements layout/placing
*/

function nm_setLayout(widget_id)
{
	alert(widget_id);
	
	var mc_box		= jQuery('#box-'+widget_id);
	var title 		= jQuery('#nm_mc_title-'+widget_id);
	var email 		= jQuery('#nm_mc_email-'+widget_id);
	var fullname 	= jQuery('#nm_mc_fullname-'+widget_id);
	var button 		= jQuery('#nm_mc_button-'+widget_id);
	
	var buttonTop 	= button.css('top');
	var emailTop 	= email.css('top');
	var fullnameTop = fullname.css('top');
	var titleTop  	= title.css('top');
	var boxHeight 	= mc_box.height();

	/*
	** adjusting the box height and width based on elements off/on
	*/
	
	if(title.width() == null && fullname.width() == null )
	{
		emailTop = '15px';

	}
	else if(title.width() == null )
	{
		fullname.css({'top':'15px'});
		emailTop = parseInt(fullname.css('top')) + fullname.height() + 10 + 'px';

	}
	else if(fullname.width() == null )
	{
		title.css({'top':'5px'});
		emailTop = parseInt(title.css('top')) + title.height() + 10 + 'px';
	}
	
	
	
	email.css({'top':emailTop});
	
	buttonTop = parseInt(emailTop) + email.height() + 10 + 'px';
	button.css({'top':buttonTop});
	
	boxHeight = parseInt(buttonTop) + button.height() + 20 + 'px';
	mc_box.css({'height':boxHeight});
}
	
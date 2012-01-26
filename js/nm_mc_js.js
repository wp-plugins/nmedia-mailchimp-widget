// JavaScript Document
jQuery(document).ready(function(){
	var mc_box		= jQuery('.nm_mc_box');
	var title 		= jQuery('#nm_mc_title');
	var email 		= jQuery('#nm_mc_email');
	var fullname 	= jQuery('#nm_mc_fullname');
	var button 		= jQuery('#nm_mc_button');
	
	var buttonTop 	= jQuery('#nm_mc_button').css('top');
	var emailTop 	= jQuery('#nm_mc_email').css('top');
	var fullnameTop = jQuery('#nm_mc_fullname').css('top');
	var titleTop  	= jQuery('#nm_mc_title').css('top');
	var boxHeight 	= jQuery('#nm_mc_button').height();

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
	
});


function postToMailChimp(post_url, abspath)
{
	var e 		 = jQuery('#nm_mc_email').val();
	var listid	 = jQuery('#nm_mc_list_id').val();
	var fullname = jQuery('#nm_mc_fullname').val();	
	
	jQuery('#nm_mc_email').val('Subscribing...');
	
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
function nm_setDesign(button_bg, button_border, button_text, bgImageURL, box_bg, box_border, title_color, title_size_type)
{
	var button 		= jQuery('#nm_mc_button');
	var box 		= jQuery('.nm_mc_box');
	var title 		= jQuery('#nm_mc_title');
	
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
		
}
	
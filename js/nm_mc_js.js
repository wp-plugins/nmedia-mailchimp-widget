// JavaScript Document
jQuery(document).ready(function(){
	
	
});

String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}



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
							addr1: jQuery('#addr1-'+widget_id).val(),
							addr2: jQuery('#addr2-'+widget_id).val(),
							city: jQuery('#city-'+widget_id).val(),
							state: jQuery('#state-'+widget_id).val(),
							zip: jQuery('#zip-'+widget_id).val(),
							country: jQuery('#country-'+widget_id).val(),
							abs_path: abspath}, function(data){
			alert(data);
			jQuery('#nm_mc_email-'+widget_id).val('');
			jQuery('#nm_mc_fullname-'+widget_id).val('');
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
	
	fix_placeHolder(widget_id);
		
}


/*
** setting elements layout/placing
*/

function nm_setLayout(widget_id)
{
	//alert(widget_id);
	
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
	

/*
** place holder fixing for ie 
*/

function fix_placeHolder(widget_id)
{
	var email 		= jQuery('#nm_mc_email-'+widget_id);
	var fullname 	= jQuery('#nm_mc_fullname-'+widget_id);
	
	if (!testAttribute("input", "placeholder")) 
	{

		//var text_content = jQuery("#placehoder_ie_fix_email").html();
	    putPlaceHolder(email, jQuery("#placehoder_ie_fix_email").html());
		putPlaceHolder(fullname, jQuery("#placehoder_ie_fix_fname").html());
	}
	    
}


function putPlaceHolder(element, text_content)
{
		element.css("color", "gray");
	    element.val(text_content);
	    element.focus(function() {
		if (jQuery(this).val().trim() == text_content.trim())
	    { 
			//alert('focused');
			jQuery(this).val(''); 
			jQuery(this).css("color","black");
		}
	    });
	
	    element.blur(function() {
	    if (jQuery(this).val() == "")
	    { 
			jQuery(this).css("color", "gray");
			jQuery(this).val(text_content);
			}
	    });
	  
}

function testAttribute(element, attribute)
{
  var test = document.createElement(element);
  if (attribute in test) 
    return true; 
	//alert('supported');
  else 
    return false;
	//alert('not');
}



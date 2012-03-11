<?php

//wp_enqueue_script('jquery');

$bgURL = plugins_url('/images/'.$bgbox, __FILE__);

//fixing some layout issue
$paddTop = ((int)$width < 190) ? '18px' : '5px';
?>
<div id="email-subscription-box" style="background-image: url(<?php echo $bgURL?>); width:<?php echo $width?>">
	<span style="color:#fff"><?php echo $boxTitle?></span>
    
    <?php if($show_names):?>
    	<input type="text" id="fname" class="field_names" onclick="nm_clickclear(this, 'First Name')" onblur="nm_clickrecall(this,'First Name')" value="First Name" />
        <!--<input type="text" id="lname" class="field_names" onclick="nm_clickclear(this, 'Last Name')" onblur="nm_clickrecall(this,'Last Name')" value="Last Name" />-->
    <?php endif?>
        
       	<input type="text" id="subsc_email" class="field_email" onclick="nm_clickclear(this, 'Email')" onblur="nm_clickrecall(this,'Email')" value="Email" />
        <input type="hidden" value="<?php echo get_option('nm_mc_apikey');?>" id="nm_mailchimp_api_key" />
        <input type="hidden" value="<?php echo $list_id;?>" id="nm_mailchimp_list_id" />
        <input type="button" class="btn_email" onclick="postToMailChimp()" />
        
        
    </p>
    
    
    
    <div style="clear:both"></div>
</div>	


<script type="text/javascript">
$.noConflict();
function postToMailChimp()
{
	var e 		= jQuery('#subsc_email').val();
	var apikey 	= jQuery('#nm_mailchimp_api_key').val();
	var listid	= jQuery('#nm_mailchimp_list_id').val();
	var f		= jQuery('#fname').val();
	var l		= jQuery('#lname').val();	
	var thanks	= '<?php echo $thanksMessage?>';
	
	//alert(e);
	jQuery('#subsc_email').val('Subscribing...');
	
	var url_api_mailchimp = "<?php echo plugins_url('api_mailchimp/postToMailChimp.php', __FILE__)?>";
	//alert(url_api_mailchimp);
	
	jQuery.post(url_api_mailchimp, {email: e,
								api_key: apikey,
								list_id: listid,
								fname: f,
								lname: l}, function(data){
			alert(thanks);
			jQuery('#subsc_email').val('Thanks');
	});
}


function nm_clickclear(thisfield, defaulttext, color) {
	if (thisfield.value == defaulttext) {
		thisfield.value = "";
		if (!color) {
			color = "666666";
		}
		thisfield.style.color = "#" + color;
	}
}
function nm_clickrecall(thisfield, defaulttext, color) {
	if (thisfield.value == "") {
		thisfield.value = defaulttext;
		if (!color) {
			color = "666666";
		}
		thisfield.style.color = "#" + color;
	}
}
</script>


	
<?php
$options_file = dirname(__FILE__).'/get-plugin-options.php';
include ($options_file);
$shortname = "nm_mc";

$post_url = plugins_url('api_mailchimp/postToMailChimp.php', __FILE__);

/*
* Designs options
*/
$button_bg 		= get_option($shortname .'_button_bg');
$button_border 	= get_option($shortname . '_button_border');
$button_text 	= get_option($shortname . '_button_text');

if(get_option($shortname . '_bg_image') != 'none.png')
	$box_bg_image = plugins_url('images/'.get_option($shortname . '_bg_image'), __FILE__);
	
$box_bg			= get_option($shortname . '_box_bg');
$box_border     = get_option($shortname . '_box_border');
$title_color    = get_option($shortname . '_title_color');
$title_size_type= get_option($shortname . '_title_size_type');

?>

<div class="nm_mc_box" id="box-<?php echo $widget_id?>">
	<?php
	if($boxTitle)	echo '<div class="nm_mc_title" id="nm_mc_title-'.$widget_id.'">'.$boxTitle.'</div>';
	
	if($show_names) 
		echo '<input type"text" class="nm_mc_fullname" id="nm_mc_fullname-'.$widget_id.'" placeholder="'.__('Firstname, Lastname', 'nm_mailchimp_plugin').'" / >';	
	?>
    
    
	<input type"text" id="nm_mc_email-<?php echo $widget_id?>" placeholder="<?php _e('Email', 'nm_mailchimp_plugin')?>" class="nm_mc_email" / >
    <input type="hidden" value="<?php echo $list_id;?>" id="nm_mc_list_id-<?php echo $widget_id?>" />
	<input type="button" class="button" value="<?php echo get_option($shortname . '_button_title')?>" id="nm_mc_button-<?php echo $widget_id?>" onclick="postToMailChimp('<?php echo $post_url?>', '<?php echo ABSPATH?>', '<?php echo $widget_id?>')" / >
    
    <span id="placehoder_ie_fix_email" style="display:none"><?php _e('Email', 'nm_mailchimp_plugin')?></span>
    <span id="placehoder_ie_fix_fname" style="display:none"><?php _e('Fristname, Lastname', 'nm_mailchimp_plugin')?></span>
</div>

<script type="text/javascript">
/*
** form elements
*/
/*var box   	= jQuery('#nm_mc_box-<?php echo $widget_id?>').val();
var title 	= jQuery('#nm_mc_title-<?php echo $widget_id?>').val();
var fname   = jQuery('#nm_mc_fullname-<?php echo $widget_id?>').val();
var email   = jQuery('#nm_mc_email-<?php echo $widget_id?>').val();
var list    = jQuery('#nm_mc_list_id-<?php echo $widget_id?>').val();
var button  = jQuery('#nm_mc_button-<?php echo $widget_id?>').val();

var formElements = [];
formElements.push(box);
formElements.push(title);*/



nm_setDesign('<?php echo $widget_id?>', '<?php echo $button_bg?>','<?php echo $button_border?>', '<?php echo $button_text?>', '<?php echo $box_bg_image?>', '<?php echo $box_bg?>', '<?php echo $box_border?>', '<?php echo $title_color?>', '<?php echo $title_size_type?>');
</script>
<?php
$options_file = dirname(__FILE__).'/get-plugin-options.php';
include ($options_file);
$shortname = "nm_mc";

$post_url = plugins_url('api_mailchimp/store-address.php', __FILE__);

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

<div class="nm_mc_box">
	<?php
	if($boxTitle)	echo '<div id="nm_mc_title">'.$boxTitle.'</div>';
	
	if($show_names) 
		echo '<input type"text" id="nm_mc_fullname" placeholder="'.__('Firstname, Lastname', 'nm_mailchimp_plugin').'" / >';	
	?>
    
    
	<input type"text" id="nm_mc_email" placeholder="<?php _e('Email', 'nm_mailchimp_plugin')?>" / >
    <input type="hidden" value="<?php echo $list_id;?>" id="nm_mc_list_id" />
	<input type="button" class="button" value="<?php echo get_option($shortname . '_button_title')?>" id="nm_mc_button" onclick="postToMailChimp('<?php echo $post_url?>', '<?php echo ABSPATH?>')" / >
	<!--<p>Powered By: Nmedia</p>-->
    <!--<span class="tooltip">Here is the tooltip</span>-->
</div>

<script type="text/javascript">
nm_setDesign('<?php echo $button_bg?>','<?php echo $button_border?>', '<?php echo $button_text?>', '<?php echo $box_bg_image?>', '<?php echo $box_bg?>', '<?php echo $box_border?>', '<?php echo $title_color?>', '<?php echo $title_size_type?>');
</script>
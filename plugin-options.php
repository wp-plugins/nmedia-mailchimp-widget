<?php
$nm_mc_name = "NajeebMedia MailChimp List Subscriber Plugin";
$nm_mc_shortname = "nm_mc";


$categories = get_categories('hide_empty=0&orderby=name');
$wp_cats = array();
foreach ($categories as $category_list ) {
       $wp_cats[$category_list->cat_ID] = $category_list->cat_name;
}
array_unshift($wp_cats, "Choose a category");

//color array
$nm_bgs = array('Black'		=>	'bg-black.png', 
				'Green'		=> 	'bg-green.png',
				'Blue'		=> 	'bg-blue.png',
				'Magenta'	=> 'bg-magenta.png',
				'Pink'		=> 'bg-pink.png', 
				'None'		=> 'none.png'
				);


// Create Plugin nm_mc_options

$nm_mc_options = array (

array( "name" => $nm_mc_name." Options",
	"type" => "title"),

array( 	"name" => __("General Settings", "nm_mailchimp_plugin"),	
		"type" => "section"),	
		array( "type" => "open"),
		
		array(  "name" => __("API Key", "nm_mailchimp_plugin"),
				"desc" => __("Enter your MailChimp API Key, don't know where to get? please visit this link, <a href=http://www.najeebmedia.com/where-can-i-find-my-mailchimp-api-key/ target=_blank>Get API Key</a>", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_api_key",
				"type" => "text"),
		array( 	"name" => __("Thanks Message", "nm_mailchimp_plugin"),
		  		"desc" => __("Type a message here, it will be shown when user will submit the email/info for subscription", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_thanks_message",
				"type" => "textarea",
				"std" => ""),	
		
		array( 	"name" => __("Button Title", "nm_mailchimp_plugin"),
				"desc" => __("Enter button title text e.g: Subscribe", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_button_title",
				"type" => "text",
				"std" => "Subscribe"),
		
		
		array( "type" => "close"),
		
		array( "name" => __("Design Settings", "nm_mailchimp_plugin"),
				"type" => "section"),
		array( "type" => "open"),
		
		array( 	"name" => __("Select Background Image", "nm_mailchimp_plugin"),
				"desc" => __("Select background image", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_bg_image",
				"type" => "bgs",
				"std" => ""),
		
		array( 	"name" => __("Container Background Color", "nm_mailchimp_plugin"),
				"desc" => __("If no background image is selected then you can enter background color code like: #999", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_box_bg",
				"type" => "text",
				"std" => ""),
	  
	  	array( 	"name" => __("Container Border Color", "nm_mailchimp_plugin"),
				"desc" => __("If no background image is selected then you can enter border color code like: #999 1px solid", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_box_border",
				"type" => "text",
				"std" => ""),
		
		array( 	"name" => __("Title Text Color", "nm_mailchimp_plugin"),
				"desc" => __("Enter title text color code like: #999", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_title_color",
				"type" => "text",
				"std" => "#fff"),
				
		array( 	"name" => __("Title Text Size/Type", "nm_mailchimp_plugin"),
				"desc" => __("Enter title text size and type like: 16px arial,sans-serif", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_title_size_type",
				"type" => "text",
				"std" => "16px arial,sans-serif"),
				
				
		array( 	"name" => __("Button Background Color", "nm_mailchimp_plugin"),
				"desc" => __("Enter Background color code like: #999", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_button_bg",
				"type" => "text",
				"std" => "#093"),
				
		
		
		array( 	"name" => __("Button Border Color", "nm_mailchimp_plugin"),
				"desc" => __("Enter Background color code like: #999 1px solid", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_button_border",
				"type" => "text",
				"std" => ""),
				
		array( 	"name" => __("Button Text Color", "nm_mailchimp_plugin"),
				"desc" => __("Enter Background color code like: #999", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_button_text",
				"type" => "text",
				"std" => "#000"),
		
		array( "type" => "close"),
		
		array( "name" => __("Get More Cool Backgrounds in Pro Vesion for just $8 USD", "nm_mailchimp_plugin"),
				"type" => "section"),
		array( "type" => "open"),
		
		array( 	"name" => __("Background Image", "nm_mailchimp_plugin"),
				"desc" => __("Once you get Pro Version of this plugin, you can use all these Pro Backgrounds with Gradiant Colors. For Buy Pro Version click <a href=http://www.najeebmedia.com/wordpress-mailchimp-plugin-2-0-released/>here</a>", "nm_mailchimp_plugin"),
				"id" => $nm_mc_shortname."_bg_image",
				"type" => "bgs_pro",
				"std" => ""),
		
		array( "type" => "close"),

);	//end of nm_mc_options array
											
											

function nm_plugin_add_admin() {

    global $nm_mc_name, $nm_mc_shortname, $nm_mc_options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($nm_mc_options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($nm_mc_options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: plugins.php?page=plugin-options.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($nm_mc_options as $value) {
                delete_option( $value['id'] ); }

            header("Location: plugins.php?page=plugin-options.php&reset=true");
            die;

        } 
    }

    //add_plugins_page($nm_mc_name." Options", "Mailchimp Options", 'edit_plugins', basename(__FILE__), 'nm_mc_admin');
	add_menu_page($nm_mc_name, "Nmedia MailChimp", 'edit_plugins', basename(__FILE__), 'nm_mc_admin', plugin_dir_url(__FILE__ ).'images/option.png');

}


function nm_plugin_add_init() {
  	wp_register_style('nm_mc_option_style', plugins_url('css/options.css', __FILE__));
	wp_enqueue_style( 'nm_mc_option_style');
	
	wp_enqueue_script("nm_mc_script", plugins_url('js/nm_plugin_option.js', __FILE__), false, "1.0"); 
	
}


function nm_mc_admin() {

    global $nm_mc_name, $nm_mc_shortname, $nm_mc_options, $nm_bgs;
	//print_r($nm_mc_options);
	

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$nm_mc_name.' '.__('Settings saved.','nm_mailchimp_plugin').'</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$nm_mc_name.' '.__('Settings reset.','nm_mailchimp_plugin').'</strong></p></div>';
    if ( $_REQUEST['reset_widgets'] ) echo '<div id="message" class="updated fade"><p><strong>'.$nm_mc_name.' '.__('Widgets reset.','nm_mailchimp_plugin').'</strong></p></div>';
    
?>
<div class="wrap rm_wrap">
<h2><?php echo $nm_mc_name; ?> Settings</h2>

<div class="nm_opts">
<form method="post">

<?php foreach ($nm_mc_options as $value) {
switch ( $value['type'] ) {

case "open":
?>

<?php break;

case "close":
?>

</div>
</div>
<br />

<?php break;

case "title":
?>

<?php break;

case 'text':
?>

<div class="rm_input rm_text">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_mailchimp_plugin') ?></label>
 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <small><?php _e($value['desc'], 'nm_mailchimp_plugin') ?></small><div class="clearfix"></div>

 </div>
<?php
break;

case 'textarea':
?>

<div class="rm_input rm_textarea">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_mailchimp_plugin') ?></label>
 	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?></textarea>
 <small><?php _e($value['desc'], 'nm_mailchimp_plugin') ?></small><div class="clearfix"></div>

 </div>

<?php
break;

case 'bgs'		//custom field set by Najeeb
?>

<div class="rm_input">
	<div style="float:left; width:200px;">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_mailchimp_plugin') ?></label>
    </div>
    <div class="nm_bgs">
    <?php foreach($nm_bgs as $bg => $val):
	$bg_img_name = 'images/'.$val;
	?>
    <div class="item">
        	<img src="<?php echo plugins_url($bg_img_name, __FILE__)?>" alt="<?php echo $bg ?>" width="75" /><br />
			<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $val?>" <?php if (get_settings( $value['id'] ) == $val) { echo 'checked="checked"'; } ?> />
            <?php echo $bg ?>
        </div>
    <?php endforeach;?>
        
        <div class="clearfix"></div>
        </div>
 
    <small><?php _e($value['desc'], 'nm_mailchimp_plugin') ?></small>
 	<div class="clearfix"></div>

 </div>

<?php
break;



case 'bgs_pro'		//custom field set by Najeeb for Pro Backgrounds
?>

<div class="rm_input">
	<div style="float:left; width:200px;">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_mailchimp_plugin') ?></label>
    </div>
    <div class="nm_bgs">
    <?php 
	for($i=1; $i<=26; $i++):
	$bg_img_name = 'images/'.$i.'.jpg';
	$bg_title = 'Pro-'.$i;
	$val = $i.'.jpg';
	?>
    <div class="item">
        	<img src="<?php echo plugins_url($bg_img_name, __FILE__)?>" alt="<?php $bg_title?>" width="75" /><br />
			<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $val?>" <?php if (get_settings( $value['id'] ) == $val) { echo 'checked="checked"'; } ?> />
            <?php echo $bg_title ?>
        </div>
    <?php endfor;?>
        
        <div class="clearfix"></div>
        </div>
 
    <small><?php _e($value['desc'], 'nm_mailchimp_plugin') ?></small>
 	<div class="clearfix"></div>

 </div>

<?php
break;

case 'select':
?>

<div class="rm_input rm_select">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_mailchimp_plugin') ?></label>

<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['nm_mc_options'] as $option) { ?>
		<option <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select>

	<small><?php _e($value['desc'], 'nm_mailchimp_plugin') ?></small><div class="clearfix"></div>
</div>
<?php
break;

case "checkbox":
?>

<div class="rm_input rm_checkbox">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_mailchimp_plugin') ?></label>

<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />

	<small><?php _e($value['desc'], 'nm_mailchimp_plugin') ?></small><div class="clearfix"></div>
 </div>
<?php break;
case "section":

$i++;
?>

<div class="nm_section">
<div class="rm_title"><h3><img src="<?php plugins_url('css/images/trans.gif', __FILE__)?>" class="inactive" alt="""><?php _e($value['name'], 'nm_mailchimp_plugin') ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="<?php _e('Save Changes', 'nm_mailchimp_plugin')?>" />
</span><div class="clearfix"></div></div>
<div class="nm_options">

<?php break;

}
}
?>

<input type="hidden" name="action" value="save" />
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="<?php _e('Reset', 'nm_mailchimp_plugin')?>" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
<div style="font-size:9px; margin-bottom:10px;">2012 © <a href="http://www.najeebmedia.com">Nmedia</a></div>
 </div> 

<?php
// get company ad
$file = dirname(__FILE__).'/nmedia-ad.php';
include($file);
}
/*add_action('admin_menu', 'mytheme_add_admin');*/
add_action('admin_init', 'nm_plugin_add_init');
add_action('admin_menu' , 'nm_plugin_add_admin');

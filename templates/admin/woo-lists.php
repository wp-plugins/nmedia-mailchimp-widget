<?php 
/*
 * Listing all users with file stats
 */
 global $nm_mailchimp;
 $mc_lists_array = $nm_mailchimp -> mc -> lists -> getList();
 $list_val = get_option('nm_woo_mailchimp_list_val');
 
 if ( is_array ( $mc_lists_array ) ) {
	 echo '<select id="woo_mc_list_select">';
	 foreach($mc_lists_array['data'] as $mc_list){
		$selected = '';
		if ( $list_val == $mc_list['id'] )
			$selected = 'selected';

	 	echo '<option value="'. $mc_list['id'] .'"' . $selected . '>'. $mc_list['name'] .'</option>';	 
	 }
	 echo '</select>';
 }
 //pa_nm_mailchimp($mc_lists_array['data']);
?>

<script type="text/javascript">
    <!--
	jQuery('#woo_mc_list_select').on('change', function() {
	  //alert( this.value ); // or $(this).val()
	  do_action = 'nm_mailchimp_woo_mc_save_list_val';

	var server_data = {
		action 	 : do_action,
		list_val : this.value
	}
	
	jQuery.post(ajaxurl, server_data, function(resp) {

		//console.log(resp);
	   //alert( resp ); // or $(this).val()

	});
	  
	  
	});


    //-->
</script>
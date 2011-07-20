<?php
$selected = 'selected = "selected"';
?>

<p>
	<label>Title<br>
    <input type="text" class="widefat" id="<?php echo $field_id_title?>" name="<?php echo $field_name_title?>" value="<?php echo attribute_escape( $instance['nm_mc_title'] )?>" />
    </label>
    
</p>

<p>
	<label>Select List<br>
    <select name="<?php echo $field_name_list?>" id="<?php echo $field_id_list?>">
    <option value="">Select List</option>
    <?php foreach($retval['data'] as $list):
			$selected = ($list['id'] == $instance['nm_mc_list_id']) ? 'selected = "selected"' : '';
	?>
    <option value="<?php echo $list['id']?>" <?php echo $selected?> ><?php echo $list['name']?></option>
    <?php endforeach;?>
    </select>
    </label>
</p>

<p>
	<label>Show F/L Names [Optional]<br>
     <select name="<?php echo $field_name_names?>" id="<?php echo $field_id_names?>">
    <option value="">Select Option</option>
    <option value="1" <?php echo ($instance['nm_mc_show_names'] == 1) ? $selected : ''?>>Yes</option>
    <option value="0" <?php echo ($instance['nm_mc_show_names'] == 0) ? $selected : ''?> >No</option>
    </select>
    </label>
    
</p>
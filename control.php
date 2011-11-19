<?php
$selected = 'selected = "selected"';
?>

<p>
	<label>Title<br>
    <input type="text" class="widefat" id="<?php echo $field_id_title?>" name="<?php echo $field_name_title?>" value="<?php echo attribute_escape( $instance['nm_mc_title'] )?>" />
    </label>
   
</p>


<p>
	<label>Box Title<br>
    <input type="text" class="widefat" id="<?php echo $field_id_box_title?>" name="<?php echo $field_name_box_title?>" value="<?php echo attribute_escape( $instance['nm_mc_box_title'] )?>" />
    </label>
   
</p>


<p>
	<label>Widget Width<br>
    <select name="<?php echo $field_name_width?>" id="<?php echo $field_id_width?>">
    <option value="">Select Width</option>
    <?php foreach(nmMailChimp::$widgetWidth as $width):
			$selected = ($width == $instance['nm_mc_width']) ? 'selected = "selected"' : '';
	?>
    <option value="<?php echo $width?>" <?php echo $selected?> ><?php echo $width?></option>
    <?php endforeach;?>
    </select>
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
	<label>Show First Name [Optional]<br>
     <select name="<?php echo $field_name_names?>" id="<?php echo $field_id_names?>">
    <option value="">Select Option</option>
    <option value="1" <?php echo ($instance['nm_mc_show_names'] == 1) ? $selected : ''?>>Yes</option>
    <option value="0" <?php echo ($instance['nm_mc_show_names'] == 0) ? $selected : ''?> >No</option>
    </select>
    </label>
</p>

<p>
	<label>Select Background Color Box<br>
     <select name="<?php echo $field_name_bg?>" id="<?php echo $field_id_bg?>">
    <option value="">Select</option>
    <option value="bg-black.png" <?php echo ($instance['nm_mc_bg'] == 'bg-black.png') ? $selected : ''?>>Black</option>
    <option value="bg-blue.png" <?php echo ($instance['nm_mc_bg'] == 'bg-blue.png') ? $selected : ''?>>Blue</option>
    <option value="bg-green.png" <?php echo ($instance['nm_mc_bg'] == 'bg-green.png') ? $selected : ''?>>Green</option>
    <option value="bg-magenta.png" <?php echo ($instance['nm_mc_bg'] == 'bg-magenta.png') ? $selected : ''?>>Magenta</option>
    <option value="bg-pink.png" <?php echo ($instance['nm_mc_bg'] == 'bg-pink.png') ? $selected : ''?>>Pink</option>
    <option value="bg-w-1.jpg" <?php echo ($instance['nm_mc_bg'] == 'bg-w-1.jpg') ? $selected : ''?>>White1</option>
    <option value="bg-w-2.jpg" <?php echo ($instance['nm_mc_bg'] == 'bg-w-2.jpg') ? $selected : ''?>>White2</option>
    <option value="bg-w-3.jpg" <?php echo ($instance['nm_mc_bg'] == 'bg-w-3.jpg') ? $selected : ''?>>White3</option>
    <option value="bg-w-4.jpg" <?php echo ($instance['nm_mc_bg'] == 'bg-w-4.jpg') ? $selected : ''?>>White4</option>
    </select>
    </label>
</p>

<p>
  <label>Thanks Message<br>
    <textarea name="<?php echo $field_name_thanks?>" class="widefat" id="<?php echo $field_id_thanks?>"><?php echo attribute_escape( $instance['nm_mc_thanks'] )?></textarea>
    </label>
   
</p>
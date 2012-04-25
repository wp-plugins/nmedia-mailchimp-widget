<?php 
/*
** this file is generting shortcode
*/
?>

<h2>Shortcode Wizard</h2>

<div id="shortcode-container">

<div id="shortcode-left">
<h3>Select List(s)</h3>
<ul>
	<li><label for="list-1"><input type="checkbox" id="list-1" value="aaa"> List A</label></li>
    <li><label for="list-2"><input type="checkbox" id="list-2" value="bbb"> List B</label></li>
    <li><label for="list-3"><input type="checkbox" id="list-3" value="ccc"> List C</label></li>
</ul>
</div>



<div id="shortcode-right">
<div class="detail" id="l-d-aaa">
<h3>Select Group(s)</h3>
<ul>
	<li><label for="g-1"><input type="checkbox" id="g-1"> Group A</label></li>
    <li><label for="g-2"><input type="checkbox" id="g-2"> Group B</label></li>
    <li><label for="g-3"><input type="checkbox" id="g-3"> Group C</label></li>
</ul>


<h3>Select Fields</h3>
<ul>
	<li><label for="f-1"><input type="checkbox" id="f-1"> Field A</label></li>
    <li><label for="f-2"><input type="checkbox" id="f-2"> Field B</label></li>
    <li><label for="f-3"><input type="checkbox" id="f-3"> Field C</label></li>
</ul>
</div>

<div class="detail" id="l-d-bbb">
<h3>Select Group(s)</h3>
<ul>
	<li><label for="g-1"><input type="checkbox" id="g-1"> Group A</label></li>
    <li><label for="g-2"><input type="checkbox" id="g-2"> Group B</label></li>
    <li><label for="g-3"><input type="checkbox" id="g-3"> Group C</label></li>
</ul>


<h3>Select Fields</h3>
<ul>
	<li><label for="f-1"><input type="checkbox" id="f-1"> Field A</label></li>
    <li><label for="f-2"><input type="checkbox" id="f-2"> Field B</label></li>
    <li><label for="f-3"><input type="checkbox" id="f-3"> Field C</label></li>
</ul>
</div>


<div class="detail" id="l-d-ccc">
<h3>Select Group(s)</h3>
<ul>
	<li><label for="g-1"><input type="checkbox" id="g-1"> Group A</label></li>
    <li><label for="g-2"><input type="checkbox" id="g-2"> Group B</label></li>
    <li><label for="g-3"><input type="checkbox" id="g-3"> Group C</label></li>
</ul>


<h3>Select Fields</h3>
<ul>
	<li><label for="f-1"><input type="checkbox" id="f-1"> Field A</label></li>
    <li><label for="f-2"><input type="checkbox" id="f-2"> Field B</label></li>
    <li><label for="f-3"><input type="checkbox" id="f-3"> Field C</label></li>
</ul>
</div>
</div>

</div> <!-- end shortcode-container -->


<script type="text/javascript">
jQuery("#list-1, #list-2, #list-3").click(function(){
	
	var list_id = jQuery(this).val();
	
	if(jQuery(this).is(':checked'))
	{
		//jQuery('.detail').hide();
		jQuery('#l-d-'+list_id).fadeIn(200);
	}
	else
	{
		jQuery('#l-d-'+list_id).fadeOut(200);
	}
});
</script>
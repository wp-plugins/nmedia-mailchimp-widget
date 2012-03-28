<?php
/*
** Listing Manager admin page
*/

$mailchimp = dirname(__FILE__) . '/class.mailchimp.php';
include ( $mailchimp );

$mc = new clsMailchimp();

/* ============== processing block ========================== */

  					/* ====> Adding new Merge Variable <====== */
if(isset($_POST['new-var']))
{
	if($mc -> addMergeVar($_GET['lid'], $_POST['new-var'], $_POST['var-detail']))
	{
		echo '<div class="updated"><p>Variable added into list</p></div>';
	}
	else
	{
		echo '<div class="error"><p>'.$mc -> mc -> errorMessage.'</p></div>';
	}
}

/* ====> Deleting new Merge Variable <====== */
if($_GET['act'] == 'del-var')
{
	if($mc -> XMergeVar($_GET['lid'], $_GET['tag']))
	{
		echo '<div class="updated"><p>Variable deleted from list</p></div>';
	}
	else
	{
		echo '<div class="error"><p>'.$mc -> mc -> errorMessage.'</p></div>';
	}
}					



/* ====> Adding new Group <====== */
if(isset($_POST['new-group']))
{
	if($mc -> addGroup($_GET['lid'], $_POST['new-group'], $_POST['new-interest']))
	{
		echo '<div class="updated"><p>Group added into list</p></div>';
	}
	else
	{
		echo '<div class="error"><p>'.$mc -> mc -> errorMessage.'</p></div>';
	}
}


/* ====> Deleting new Merge Variable <====== */
if($_GET['act'] == 'del-group')
{
	if($mc -> XGroup($_GET['lid'], $_GET['group']))
	{
		echo '<div class="updated"><p>Group deleted from list</p></div>';
	}
	else
	{
		echo '<div class="error"><p>'.$mc -> mc -> errorMessage.'</p></div>';
	}
}					

/* ============== processing block ========================== */


$arrList 	= $mc -> getAccountLists();
//print_r($arrList);
?>

<h2>Listing Manager</h2>

<div class="nm_mc_div">

<h3>Select a List</h3>
<ul>
<?php
foreach($arrList as $list):
$urlLoadDetail = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$list['id'].'&lname='.$list['name'];	

?>
	<li class="good-links"><a href="<?php echo $urlLoadDetail?>"><?php echo $list['name']?></a></li>
<?php
endforeach;
?>
</ul>
</div>


<?php
if(isset($_GET['lid'])):

$arrVars	= $mc -> getMergeVars($_GET['lid']);
$arrGroups	= $mc -> getListGroups($_GET['lid']);
?>

<h2>Selected List: <?php echo $_GET['lname']?></h2>

<div class="nm_mc_div lists_data">
<h3>List variables</h3>
<ul>
<li class="good-links">+ <a href="javascript:toggleArea('c-new-var')">Add new merge variable</a></li>
<li id="c-new-var" style="display:none">

<form action="" method="post" onsubmit="return validateMe('new-var')">
<input placeholder="Tag name e.g: PHONE" class="nm_mc_text" type="text" name="new-var" id="new-var" /><em>Must be ALL CAPS</em><br />
<input placeholder="Detail e.g: Phone Number" class="nm_mc_text" type="text" name="var-detail" /><br />
<input type="submit" value="+Add" class="button" />
</form>
</li>
</ul>

<table width="100%" class="wp-list-table widefat fixed pages">
<thead>
  <tr>
    <th width="13%">Sr No#</th>
    <th width="26%">Tag</th>
    <th width="39%">Detail</th>
    <th width="22%">Delete</th>
  </tr>
</thead>

<tbody>
<?php
$c=0;
foreach($arrVars as $var):
$urlDel = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$_GET['lid'].'&tag='.$var['tag'].'&act=del-var';	
?>

  <tr>
    <td><?php echo ++$c?></td>
    <td><?php echo $var['tag']?></td>
    <td><a href=""><?php echo $var['name']?></a></li></td>
    <td><a href="javascript:confirmDel('<?php echo $urlDel?>')">Delete</a></td>
  </tr>
   
<?php
endforeach;
?>

</tbody>
</table>

</div>


<div class="nm_mc_div lists_data">
<h3>List Groups</h3>
<ul>
<li class="good-links">+ <a href="javascript:toggleArea('c-new-group')">Add new group</a></li>

<li id="c-new-group" style="display:none">

<form action="" method="post" onsubmit="return validateMe('new-group')">
<input placeholder="Group title e.g: Students" class="nm_mc_text" type="text" name="new-group" id="new-group" /><br />
<input placeholder="Interest in (sub group)e.g: Seniors" class="nm_mc_text" type="text" name="new-interest" id="new-interest" /><br />
<input type="submit" value="+Add" class="button" />
</form>
</li>
</ul>

<table width="100%" class="wp-list-table widefat fixed pages">
<thead>
  <tr>
    <th width="13%">Sr No#</th>
    <th width="39%">Name</th>
    <th width="22%">Action</th>
  </tr>
</thead>

<tbody>
<?php
$c=0;
foreach($arrGroups as $group):
$urlDel = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$_GET['lid'].'&group='.$group['name'].'&act=del-group';

$urlGroups = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$_GET['lid'].'&group='.$group['name'].'&gid='.$group['id'];
?>

  <tr>
    <td><?php echo ++$c?></td>
    <td><a href=""><?php echo $group['name']?></a></li></td>
    <td><a href="javascript:confirmDel('<?php echo $urlDel?>')">Delete</a>
    | <a href="<?php echo $urlGroups?>">View groups</a></td>
  </tr>
   
<?php
endforeach;
?>

</tbody>
</table>


<!-- groups interest (sub groups) -->
<?php
if($_GET['gid'])
{
	echo '<pre>';
	print_r($arrGroups);
	echo '</pre>';
	
?>
<h3>Groups interest (sub group)</h3>
<ul>
<li class="good-links">+ <a href="javascript:toggleArea('c-new-group')">Add new group</a></li>

<li id="c-new-group" style="display:none">

<form action="" method="post" onsubmit="return validateMe('new-group')">
<input placeholder="Group title e.g: Students" class="nm_mc_text" type="text" name="new-group" id="new-group" /><br />
<input placeholder="Interest in (sub group)e.g: Seniors" class="nm_mc_text" type="text" name="new-interest" id="new-interest" /><br />
<input type="submit" value="+Add" class="button" />
</form>
</li>
</ul>

<table width="100%" class="wp-list-table widefat fixed pages">
<thead>
  <tr>
    <th width="13%">Sr No#</th>
    <th width="39%">Name</th>
    <th width="22%">Action</th>
  </tr>
</thead>

<tbody>
<?php
$c=0;
foreach($arrGroups as $group):
$urlDel = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$_GET['lid'].'&group='.$group['name'].'&act=del-group';

$urlGroups = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$_GET['lid'].'&group='.$group['name'].'&gid='.$group['id'];
?>

  <tr>
    <td><?php echo ++$c?></td>
    <td><a href=""><?php echo $group['name']?></a></li></td>
    <td><a href="javascript:confirmDel('<?php echo $urlDel?>')">Delete</a>
    | <a href="<?php echo $urlGroups?>">View groups</a></td>
  </tr>
   
<?php
endforeach;
?>

</tbody>
</table>
<?php }?>
</div>

<?php
endif;
?>

<script type="text/javascript">
function toggleArea(a)
{
	jQuery('#'+a).fadeToggle();
}

function validateMe(elementID)
{
	if(jQuery("#"+elementID).val() == ''){
		jQuery("#"+elementID).css({'border':'#f00 1px solid'});
		return false;
	}
	else
	{
		return true;
	}
}

function confirmDel(url)
{
	var a = confirm("Do you want to remove this variable?");
	
	if(a)
	{
		window.location = url;
	}
}
</script>

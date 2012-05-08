<?php
/*
** Listing Manager admin page
*/

$mailchimp = dirname(__FILE__) . '/class.mailchimp.php';
include ( $mailchimp );

$mc = new clsMailchimp();


$arrList 	= $mc -> getAccountLists();
/*echo '<pre>';
print_r($arrList);
echo '</pre>';*/
?>

<h2><?php _e('Mailchimp Account Lists', 'nm_mailchimp_plugin')?></h2>
<div class="get-pro">
<h3><?php _e('It is Not PRO Version, You cannot use these features', 'nm_mailchimp_plugin')?></h3>
<a href="http://www.najeebmedia.com/pro3">Buy here</a>
</div>

<div class="nm_mc_div">
<p>Following lists are attached to your Mailchimp Account</p>

<?php if($_REQUEST['lid'])
	echo '<a href="'.get_admin_url('', 'admin.php?page=lists-manager').'">Back to list</a>';
?>
    
    
<ul>
<?php
$current = '';
foreach($arrList as $list):
$urlLoadDetail = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$list['id'].'&lname='.$list['name'];	

if($_REQUEST['lid'])
	$current = (@$_REQUEST['lid'] == $list['id']) ? 'current-list' : 'hide-others';
?>
	<li class="good-links <?php echo $current?>">
    <h3><?php echo $list['name']?></h3>
    <br />
	<a href="javascript:toggleArea('list-detail-<?php echo $list['id']?>')">Detail</a> |
    <a href="<?php echo $urlLoadDetail?>">Add Variables and Groups</a>
    <?php 
	if(!isset($_GET['lid'])):
		$mc -> renderListStats($list['stats'], $list['id']);
	endif;
	?>
    </li>
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

<h2><?php _e('Click on list', 'nm_mailchimp_plugin')?>: <?php echo $_GET['lname']?></h2>

<div class="nm_mc_div lists_data">
<h3><?php _e('List Variables', 'nm_mailchimp_plugin')?></h3>
<ul>
<li class="good-links">+ <a href="javascript:toggleArea('c-new-var')"><?php _e('Add new Merge Var', 'nm_mailchimp_plugin')?></a></li>
<li id="c-new-var" style="display:none">

<form action="" method="post" onsubmit="return validateMe('new-var')">
<input placeholder="Tag name e.g: PHONE" class="nm_mc_text" type="text" name="new-var" id="new-var" /><em><?php _e('Must be all CAPS', 'nm_mailchimp_plugin')?></em><br />
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
<h3><?php _e('Groupings', 'nm_mailchimp_plugin')?></h3>
<ul>
<li class="good-links">+ <a href="javascript:toggleArea('c-new-group')"><?php _e('Add new grouping', 'nm_mailchimp_plugin')?></a></li>

<li id="c-new-group" style="display:none">

<form action="" method="post" onsubmit="return validateMe('new-group')">
<input placeholder="Group title e.g: Students" class="nm_mc_text" type="text" name="new-group" id="new-group" /><br />
<input placeholder="Interest in (sub group)e.g: Seniors" class="nm_mc_text" type="text" name="new-interest" id="new-interest-group" /><br />
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
$urlDel = get_admin_url('', 'admin.php?page=lists-manager').'&gid='.$group['id'].'&group='.$group['name'].'&act=del-interest-group&lid='.$_GET['lid'];

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
if($_GET['gid'] and $_GET['act'] != 'del-group')
{
	/*echo '<pre>';
	print_r($arrGroups);
	echo '</pre>';*/
	
//getting the active group
foreach($arrGroups as $grp)
{
	if($grp['id'] == $_GET['gid'])
	{
		$activeGroup = $grp['name'];
		$subGroups = $grp['groups'];
	}
}
	
?>
<h3><?php _e('Interest groups', 'nm_mailchimp_plugin')?> of <?php echo $activeGroup?></h3>
<ul>
<li class="good-links">+ <a href="javascript:toggleArea('c-new-interest')"><?php _e('Add interest', 'nm_mailchimp_plugin')?></a></li>

<li id="c-new-interest" style="display:none">

<form action="" method="post" onsubmit="return validateMe('new-interest')">
<input type="hidden" name="group-id" value="<?php echo $arrGroups[0]['id']?>" />
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
foreach($subGroups as $group):
$urlDel = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$_GET['lid'].'&group='.$group['name'].'&gid='.$_GET['gid'].'&act=del-group';
?>

  <tr>
    <td><?php echo ++$c?></td>
    <td><a href=""><?php echo $group['name']?></a></li></td>
    <td><a href="javascript:confirmDel('<?php echo $urlDel?>')">Delete</a></td>
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
	alert('It is Pro Feature, get Pro version to use these features for only $20.00 USD');
	return false
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

<?php
/*
** Listing Manager admin page
*/

$mailchimp = dirname(__FILE__) . '/class.mailchimp.php';
include ( $mailchimp );

$mc = new clsMailchimp();

$arrList = $mc -> getAccountLists();
//print_r($arrList);
?>

<h2>Listing Manager</h2>

<div class="nm_mc_div">

<h3>Select a List</h3>
<ul>
<?php
foreach($arrList as $list):
$urlLoadDetail = get_admin_url('', 'admin.php?page=lists-manager').'&lid='.$list['id'];	

?>
	<li><a href="<?php echo $urlLoadDetail?>"><?php echo $list['name']?></a></li>
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

<div class="nm_mc_div lists_data">
<h2>List variables</h2>

<ul>
<?php
foreach($arrVars as $var):
?>
	<li><a href=""><?php echo $var['name']?></a></li>
<?php
endforeach;
?>
</ul>
</div>


<div class="nm_mc_div lists_data">
<h2>List Groups</h2>
<ul>
<?php
if(!is_array($arrGroups))
{
	echo $arrGroups;
}else
{
foreach($arrGroups as $group):
?>
	<li><a href=""><?php echo $group['name']?></a></li>
<?php
endforeach;
}
?>
</ul>
</div>

<?php
endif;
?>

<?php
//This is required to get the international text strings dictionary
   // $urlExtra="../";
	$urlExtraName="footer.php";
	require 'internationalize.php';
?>

<div class="footer">
<i><?php echo $CopyRight;?> &copy; <?php echo date('Y'); ?>. 
<a href='http://hydroserverlite.codeplex.com/' target='_blank' class='reversed'><?php echo $SystemName;?></a>. 
<?php echo $AllRightsReserved;?><a href='http://hydroserverlite.codeplex.com/team/view' target='_blank' class='reversed'><?php echo $MeetDevelopers;?></a></i>
</div>
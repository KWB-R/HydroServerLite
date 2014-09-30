<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//check authority to be here
//require_once 'authorization_check.php';

//All queries go through a translator. 
require_once 'DBTranslator.php';
require_once 'fetchMainConfig.php';

$uname = $_SESSION['username'] ;

//Count the number of Sites
	$sql_sites ="SELECT COUNT(*) AS count FROM sites";

	$result_sites = transQuery($sql_sites,0,0);
	//Get the number of rows in the result set
	$num_sites = $result_sites[0]['count'];

//Count the number of Data Points
	$sql_datapts ="SELECT SUM(ValueCount) as count FROM seriescatalog";

	$result_datapts = transQuery($sql_datapts,0,0);

	//Get the number of rows in the result set
	$num_datapts =$result_datapts[0]['count'];
	
//Count the number of Variables
	$sql_vars ="SELECT COUNT(*) AS count FROM varmeth";

	$result_vars = transQuery($sql_vars,0,0);

	//Get the number of rows in the result set
	$num_vars = $result_vars[0]['count'];
	
//Count the number of Users
	$sql_users ="SELECT COUNT(*) AS count  FROM moss_users";

	$result_u = transQuery($sql_users,0,0);

	//Get the number of rows in the result set
	$num_u = $result_u[0]['count'];

//Check the cookie or set it (based on authority) if not already
// or redirect the user elsewhere if unauthorized

	require_once "_html_parts.php";
	HTML_Render_Head();
	
	echo $CSS_Main;
	
	//echo $JS_JQuery;

	//echo $JS_Maps;	
         
	HTML_Render_Body_Start(); 
	?>
	<div class='col-md-9' style='height:auto;min-height:500px'>
    <h2><?php echo $Welcome; ?> <?php echo "$uname"; ?> <?php echo $ToThe;?> <?php echo $_SITE_orgname; ?> <?php echo $DataPortal; ?></h2>
    <p><strong> <?php echo $ThisSystemRuns;?> <?php echo "$_SITE_HSLversion"; ?>  <?php echo $DatabaseContains; ?> <?php echo "$num_sites"; ?> <?php echo $Sites; ?> <?php echo "$num_datapts"; ?> <?php echo $DataPoints;?> <?php echo "$num_vars"; ?> <?php echo $Variables; ?> <?php echo "$num_u"; ?> <?php echo $users; ?></strong></p>
     <?php  require "map.php"; ?>
    </div></div>
	<?php HTML_Render_Body_End(); ?>

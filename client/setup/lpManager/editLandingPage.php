<?php
	//This is required to get the international text strings dictionary
	if(!isset($_SESSION['mainDir']))
	{	session_start();
	$mainDir=$_SESSION['mainDir'];
	}
	$lpManagerMode = true;
	 $setup="yes";
	require_once 'internationalize.php';
	
	//If coming from a login request
	
	if (isset($_POST['username']))
	{
	
	//Check if username password are correct
	
	include ("LpConfig.php");
	
	if ($_POST['username']!=$UserNameLP)
	{
		$_SESSION['loginError']="Invalid Username";
		header("Location: index.php");
	}
	if ($_POST['password']!=$PasswordLP)
	{
		$_SESSION['loginError']="Invalid Password";
		header("Location: index.php");
	}
	
	//Load the values from the file
	
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo $TitleInstall;?></title>
<link rel="shortcut icon" href="<?php echo $mainDir; ?>favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="<?php echo $mainDir; ?>favicon.ico" >

<link href="<?php echo $mainDir; ?>styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="baseTheme/style.css" type="text/css" media="all" />

 <style type="text/css">
  .ax-upload-all{
	  display:none;
    }
  </style>

<!-- JQuery JS -->
<script type="text/javascript" src="<?php echo $mainDir; ?>js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo $mainDir; ?>js/ajaxupload.js"></script>


</head>


<body background="<?php echo $mainDir; ?>images/bkgrdimage.jpg">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php include $mainDir."topBanner.php" ; ?></td>
  </tr>
  
  <tr>
    
    <td width="960" valign="top" bgcolor="#FFFFFF"><blockquote><br />
      <form name="form1" id="form1" method="post" action="">
<table width="720" border="0" align="center" style="background-color: #FFFFFF;">
  <tr>
    <td><table width="720" border="0" align="center" style="background-color: #FFFFFF;">
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><center>
      <h2 class="config"><?php echo $MainConfigTitle;?></h2></center></td>
  </tr>
  <tr>
    <td colspan="3"><h3><?php echo $AdminWelcome;?></h3></td>
  </tr>
  <tr>
    <td colspan="3"><?php echo $MainConfigDirections;?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="23">&nbsp;</td>
    <td><span class='confighead'><?php echo $CurrentUsername;?></span>&nbsp;</td>
    <td><input type="text" value="<?php echo $UserNameLP; ?>" id="username" name="username"/></td>
  </tr>
  <tr>
    <td width="23">&nbsp;</td>
    <td><span class='confighead'><?php echo $Password ;?></span>&nbsp;</td>
    <td><input type="text" id="password" value="<?php echo $PasswordLP; ?>"name="password" />
      &nbsp;</td>
  </tr>
  <tr>
    <td width="23">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    </tr>
 
  <tr>
    <td colspan="3"><span class='confighead'><?php echo $landingPageConfig;?></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $DatabaseTitle;?></td>
    <td width="404"><input type="text" id="Database Title" name="databasetitle" value="<?php echo $title; ?>" />&nbsp;</td>
    </tr>
  <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $GroupInput;?></td>
    <td><input type="text" id="Group name" name="groupname" value="<?php echo $group; ?>" />&nbsp;</td>
  </tr>
  <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $descriptioninput;?></td>
    <td><textarea name="description" cols="32" rows="6" id="description" ><?php echo $description; ?></textarea></td>
  </tr>
  <tr>
    <td colspan="3"><span class='confighead'><?php echo $citationHeading;?></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php
  
	if (isset($_POST['username']) && file_exists('citation.php'))
	{
	
		include("citation.php");
	}
  
  ?>
  <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo ($AuthorName . " 1 ". $firstName);?></td>
    <td width="404"><input type="text" id="Author1FirstName" name="auth1fn" value="<?php echo $AuthorFN; ?>" />&nbsp;</td>
    </tr>
    
     <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $AuthorName . " 1 ". $lastName;?></td>
    <td width="404"><input type="text" id="Author1LastName" name="auth1ln" value="<?php echo $AuthorL; ?>" />&nbsp;</td>
    </tr>
    
    <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $AuthorName . " 2 ". $firstName;?></td>
    <td width="404"><input type="text" id="Author2FirstName" name="auth2fn" value="<?php echo $AuthorFN2; ?>" />&nbsp;</td>
    </tr>
    
     <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $AuthorName . " 2 ". $lastName;?></td>
    <td width="404"><input type="text" id="Author2LastName" name="auth2ln" value="<?php echo $AuthorL2; ?>" />&nbsp;</td>
    </tr>
    
    
        <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $AuthorName . " 3 ". $firstName;?></td>
    <td width="404"><input type="text" id="Author3FirstName" name="auth3fn" value="<?php echo $AuthorFN3; ?>" />&nbsp;</td>
    </tr>
    
     <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $AuthorName . " 3 ". $lastName;?></td>
    <td width="404"><input type="text" id="Author3LastName" name="auth3ln" value="<?php echo $AuthorL3; ?>" />&nbsp;</td>
    </tr>
    
       <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $textyear?></td>
    <td width="404"><input type="text" id="citationYear" name="citeYear" value="<?php echo $Year; ?>" />&nbsp;</td>
    </tr>
    
    
          <tr>
    <td width="23">&nbsp;</td>
    <td width="272"><?php echo $etAltext?></td>
    <td width="404"><select name="etAl">
    <option value="TRUE">True</option>
 	   <option selected="true" value="FALSE">False</option>   
    </select>&nbsp;</td>
    </tr>
    
  <tr>
    <td colspan="3"><span class='confighead'>Custom top banner: </span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>
    
    <?php 
if (file_exists("../headerConfig.php"))
{
	echo '
    <a href="restoreBanner.php" target="_blank">Click here to restore default banner</a>';}?></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><div id="uploader_div"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  
    <td colspan="2"><input type="SUBMIT" id="submit" value="<?php echo $SaveSettings; ?>" class="button" style="width: auto" />&nbsp;&nbsp;<input type="reset" id="Reset" value="<?php echo $Cancel;?>" class="button" style="width: auto" /></td>
    <td width="3">&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</td>
  </tr>
</table>

</form></td>
  </tr>
  <tr>
    <script src="<?php echo $_SESSION['mainDir'];?>js/footer.js"></script>
  </tr>
</table>
<script type="text/javascript">
			$('#uploader_div').ajaxupload({
				url:'upload.php',
				remotePath:'topBanner/',
				maxFiles:1,
				allowExt:["jpg","png","jpeg","tiff"],
				autoStart:true
				
			});
			</script>
<script type="text/javascript">

//Validate installation form


$("form").submit(function(){
var fal=0;
//Iterate through each input field. 

	$("#form1 input[type=text]").each(function() {
	
	if (($(this).val())=="")
	{
	
	if ($(this).attr('id') == "Author3FirstName")
	return true;
	if ($(this).attr('id') == "Author2FirstName")
	return true;
	if ($(this).attr('id') == "Author2LastName")
	return true;
	if ($(this).attr('id') == "Author3LastName")
	return true;
	
		$(this).focus();
		//$(this).hide('slow',function(){$(this).show('slow');alert("Cannot Leave "+$(this).attr('id')+" Blank");});
		$(this).hide('fast',function(){$(this).show('slow');alert(<?php echo "'".$CannotLeave."'";?> + " " +$(this).attr('id')+" " + <?php echo "'".$Blank."'";?>);});
		fal=1;
		return false;
		
	}
	
	});

if (fal==1)
return false;


	$.ajax({
  type: "POST",
  url: "createLP.php",
  data: $("#form1").serialize()
  }).done(function( status ) {
   if(status==1)
  {
	    //redirect to final page
	  window.location.href = "landingPage_final.php";
	  return false
  }
  else
  {
	  alert(status);
	  alert(<?php echo "'".$ErrorMakingLP."'";?>);
	  return false
  }
  });

	return false;
});

</script>

</body>
</html>

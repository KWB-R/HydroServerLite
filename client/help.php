<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';


//check authority to be here
require_once 'authorization_check2.php';

?>

<html>
<head>
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient; ?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >
<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
<!-- JQuery JS -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script> 

</head>

<body background="images/bkgrdimage.jpg" onLoad="load()">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><?php include "topBanner.php" ; ?></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php require_once 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br />
         <!--<h1>Help Center (FAQ's)</h1>
         <p>If you need help with a part of the website, please review our Frequently Asked Questions below.</p>-->
         <h1><?php echo $FAQ; ?></h1>
         <p><?php echo $ReviewBelow; ?></p>
         <!--<p><a href="#Register">How do I register for an account?</a><br>
         <a href="#Bug">Need to report a bug?</a><br>
         <a href="#SourceVsSite">What's the difference between a source and a site?</a><br>
         <a href="#Method">Why can't I get a specific Method to delete?</a><br>
         <a href="#Successful">How do I know if my data was entered successfully?</a><br>
         <a href="#DeleteValues">Can I delete values if I realize I entered something incorrectly?</a><br>
         <a href="#AddMethod">How do I add a new method?</a><br>
         <a href="#database">Where can I find additional information about the database?</a><br>
<a href="#Questions">Still have questions?</a><br>
         <a href="#CreatSite">Ready to create a website of your own?</a> </p>
        <hr width="400">-->
<p><strong><a name="Register"></a><?php echo $Register; ?></strong><br>
<p><a href="#Register"><?php echo $Account; ?></a><br>
         <a href="#Bug"><?php echo $Bug; ?></a><br>
         <a href="#SourceVsSite"><?php echo $SourceSite; ?></a><br>
         <a href="#Method"><?php echo $DeleteMethod; ?></a><br>
         <a href="#Successful"><?php echo $DataSuccess; ?></a><br>
         <a href="#DeleteValues"><?php echo $ValueDelete; ?></a><br>
         <a href="#AddMethod"><?php echo $MethodAdd; ?></a><br>
         <a href="#database"><?php echo $DataInfo; ?></a><br>
<a href="#Questions"><?php echo $StillQuestions; ?></a><br>
         <a href="#CreatSite"><?php echo $CreateWebsite; ?></a> </p>
        <hr width="400">
<!--<p><strong><a name="Register"></a>How do I register for an account, so I can enter data?</strong><br>
          Creating an account 
        must be done by your direct supervisor. Please contact them to fulfill this request.</p>-->
<p><strong><a name="Register"></a><?php echo $Register; ?></strong><br>
          <?php echo $RegisterPara; ?></p>
<!--<p><strong><a name="Bug" id="Bug"></a>Need to report a bug?</strong><br>
If you are experiencing an issue with the website, you may share it with the programming team to have it resolved. Please visit  us <a href="http://hydroserverlite.codeplex.com/workitem/list/basic/" target="_blank">here</a>, register for an account, and post the request by clicking &quot;Create Issue&quot; in the &quot;Issue Tracker.&quot;</p>-->
<p><strong><a name="Bug" id="Bug"></a><?php echo $Bug; ?></strong><br>
<?php echo $BugPara1; ?> <a href="http://hydroserverlite.codeplex.com/workitem/list/basic/" target="_blank"><?php echo $Here; ?></a><?php echo $BugPara2; ?></p>
<!--<p><strong><a name="SourceVsSite" id="Bug3"></a>What's the difference between a Source and a Site?</strong><br>
  A &quot;Source&quot; is the institution, school, or other organization that is collecting data and coordinating the work being conducted. Sites, on the other hand, are locations or places where data is collected. Sites have a specific set of parameters (things like name, latitude, longitude, and elevation) which uniquely identify them from other Sites even when they are close to one another.</p>-->
<p><strong><a name="SourceVsSite" id="Bug3"></a><?php echo $SourceSite; ?></strong><br>
  <?php echo $SourceSitePara; ?></p>
<!--<p><strong><a name="Method" id="Bug6"></a>Why can't I get a specific Method to delete?</strong><br>
In order to delete an existing Method from the system, you must delete all data values associated with it in the database. Once you have deleted the data values, the Method can be  deleted.</p>-->
<p><strong><a name="Method" id="Bug6"></a><?php echo $DeleteMethod; ?></strong><br>
<?php echo $DeleteMethodPara; ?></p>
<!--<p><strong><a name="Successful" id="Bug2"></a>How do I know if my data was entered successfully?</strong><br>
   When data (or any other parameters) is submitted, the page will always display a success action message. The system is also built to notify you of missing or empty fields before the form may be submitted to the database.</p>-->
<p><strong><a name="Successful" id="Bug2"></a><?php echo $DataSuccess;?></strong><br>
   <?php echo $DataSuccessPara; ?></p>   
<!--<p><strong><a name="DeleteValues" id="Bug4"></a>Can I delete values if I realize I entered something incorrectly?</strong><br>
   If you enter a value  that is incorrect or needs to be changed, please contact your direct supervisor immediately. They have the authority  needed to edit or delete the  data from the table.</p>-->
<p><strong><a name="DeleteValues" id="Bug4"></a><?php echo $ValueDelete; ?></strong><br>
   <?php echo $ValueDeletePara; ?></p>
<!--<p><strong><a name="AddMethod" id="Bug5"></a>How do I add a new method?</strong><br>
   Only an Administrator has the authority to add new methods to the system. Please contact them with the necessary information to fulfill this request. They will associate the new method with the appropriate Types (or Variables) in the database.</p>-->
   <p><strong><a name="AddMethod" id="Bug5"></a><?php echo $MethodAdd; ?></strong><br>
   <?php echo $MethodAddPara; ?></p>
<!--<p><strong><a name="database" id="database"></a>Where can I find additional information about the database?</strong><br>
The database used for this software is modeled closely after those suggested by the Consortium of Universities for the Advancement of Hydrologic Sciences (CUAHSI). For additional information about the database structure, please refer to the <a href="http://his.cuahsi.org/documents/ODM1.1DesignSpecifications.pdf" target="_blank">Advanced Database Documentation</a> provided by them.</p>-->
<p><strong><a name="database" id="database"></a><?php echo $DataInfo; ?></strong><br>
<?php echo $DataInfoPara1; ?> <a href="http://his.cuahsi.org/documents/ODM1.1DesignSpecifications.pdf" target="_blank"><?php echo $DataInfoLink; ?></a> <?php echo $DataInfoPara2; ?></p>
<!--<p><strong><a name="Questions"></a>Still have questions?</strong><br>
        If you do not see your question or  answer you're seeking, please contact your direct supervisor about these additional  questions.</p>-->
     <p><strong><a name="Questions"></a><?php echo $StillQuestions; ?></strong><br>
        <?php echo $StillQuestionsPara; ?></p>
<!--     <p><strong><a name="CreatSite" id="CreatSite"></a>Ready to create a website of your own?</strong><br>
The HydroServer Lite Interactive Web Client is an open source software developed in connection with HydroDesktop through a grant  provided by <a href="http://idahoepscor.org/" target="_blank">Idaho EPSCoR</a>. HydroServer and HydroDesktop are part of the <a href="http://www.cuahsi.org/" title="Link to CUAHSI" target="_blank">Consortium of Universities for the Advancement of Hydrologic Sciences</a>, or commonly referred to as CUAHSI (pronounced &quot;kwä-ze&quot;). To learn more about this software or find out how your school or organization can get their own version of the HydroServer Lite Interactive Web Client, please visit us   <a href="http://hydroserverlite.codeplex.com/" target="_blank">here</a>.</p>-->
<p><strong><a name="CreatSite" id="CreatSite"></a><?php echo $CreateWebsite; ?></strong><br>
<?php echo $CreateWebsitePara1; ?> <a href="http://idahoepscor.org/" target="_blank">Idaho EPSCoR</a>. <?php echo $CreateWebsitePara2; ?> <a href="http://www.cuahsi.org/" title="Link to CUAHSI" target="_blank"><?php echo $Consortium; ?></a>, <?php echo $CreateWebsitePara3; ?>   <a href="http://hydroserverlite.codeplex.com/" target="_blank"><?php echo $Here; ?></a>.</p>
         <p>&nbsp;</p>
    </blockquote>
    </td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>  
</table>
</body>
</html>
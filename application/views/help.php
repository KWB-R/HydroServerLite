<?php
HTML_Render_Head($js_vars);

echo $CSS_Main;

echo $JS_JQuery;

HTML_Render_Body_Start(); ?>
<div class='col-md-9'>
<br />
<h1><?php echo getTxt('FAQ'); ?></h1>
         <p><?php echo getTxt('ReviewBelow'); ?></p>
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
<p><strong><a name="Register"></a><?php echo getTxt('Register'); ?></strong><br>
<p><a href="#Register"><?php echo getTxt('Account'); ?></a><br>
         <a href="#Bug"><?php echo getTxt('Bug'); ?></a><br>
         <a href="#SourceVsSite"><?php echo getTxt('SourceSite'); ?></a><br>
         <a href="#Method"><?php echo getTxt('DeleteMethod'); ?></a><br>
         <a href="#Successful"><?php echo getTxt('DataSuccess'); ?></a><br>
         <a href="#DeleteValues"><?php echo getTxt('ValueDelete'); ?></a><br>
         <a href="#AddMethod"><?php echo getTxt('MethodAdd'); ?></a><br>
         <a href="#database"><?php echo getTxt('DataInfo'); ?></a><br>
<a href="#Questions"><?php echo getTxt('StillQuestions'); ?></a><br>
         <a href="#CreatSite"><?php echo getTxt('CreateWebsite'); ?></a> </p>
        <hr width="400">
<!--<p><strong><a name="Register"></a>How do I register for an account, so I can enter data?</strong><br>
          Creating an account 
        must be done by your direct supervisor. Please contact them to fulfill this request.</p>-->
<p><strong><a name="Register"></a><?php echo getTxt('Register'); ?></strong><br>
          <?php echo getTxt('RegisterPara'); ?></p>
<!--<p><strong><a name="Bug" id="Bug"></a>Need to report a bug?</strong><br>
If you are experiencing an issue with the website, you may share it with the programming team to have it resolved. Please visit  us <a href="http://hydroserverlite.codeplex.com/workitem/list/basic/" target="_blank">here</a>, register for an account, and post the request by clicking &quot;Create Issue&quot; in the &quot;Issue Tracker.&quot;</p>-->
<p><strong><a name="Bug" id="Bug"></a><?php echo getTxt('Bug'); ?></strong><br>
<?php echo getTxt('BugPara1'); ?> <a href="http://hydroserverlite.codeplex.com/workitem/list/basic/" target="_blank"><?php echo getTxt('Here'); ?></a><?php echo getTxt('BugPara2'); ?></p>
<!--<p><strong><a name="SourceVsSite" id="Bug3"></a>What's the difference between a Source and a Site?</strong><br>
  A &quot;Source&quot; is the institution, school, or other organization that is collecting data and coordinating the work being conducted. Sites, on the other hand, are locations or places where data is collected. Sites have a specific set of parameters (things like name, latitude, longitude, and elevation) which uniquely identify them from other Sites even when they are close to one another.</p>-->
<p><strong><a name="SourceVsSite" id="Bug3"></a><?php echo getTxt('SourceSite'); ?></strong><br>
  <?php echo getTxt('SourceSitePara'); ?></p>
<!--<p><strong><a name="Method" id="Bug6"></a>Why can't I get a specific Method to delete?</strong><br>
In order to delete an existing Method from the system, you must delete all data values associated with it in the database. Once you have deleted the data values, the Method can be  deleted.</p>-->
<p><strong><a name="Method" id="Bug6"></a><?php echo getTxt('DeleteMethod'); ?></strong><br>
<?php echo getTxt('DeleteMethodPara'); ?></p>
<!--<p><strong><a name="Successful" id="Bug2"></a>How do I know if my data was entered successfully?</strong><br>
   When data (or any other parameters) is submitted, the page will always display a success action message. The system is also built to notify you of missing or empty fields before the form may be submitted to the database.</p>-->
<p><strong><a name="Successful" id="Bug2"></a><?php echo getTxt('DataSuccess');?></strong><br>
   <?php echo getTxt('DataSuccessPara'); ?></p>   
<!--<p><strong><a name="DeleteValues" id="Bug4"></a>Can I delete values if I realize I entered something incorrectly?</strong><br>
   If you enter a value  that is incorrect or needs to be changed, please contact your direct supervisor immediately. They have the authority  needed to edit or delete the  data from the table.</p>-->
<p><strong><a name="DeleteValues" id="Bug4"></a><?php echo getTxt('ValueDelete'); ?></strong><br>
   <?php echo getTxt('ValueDeletePara'); ?></p>
<!--<p><strong><a name="AddMethod" id="Bug5"></a>How do I add a new method?</strong><br>
   Only an Administrator has the authority to add new methods to the system. Please contact them with the necessary information to fulfill this request. They will associate the new method with the appropriate Types (or Variables) in the database.</p>-->
   <p><strong><a name="AddMethod" id="Bug5"></a><?php echo getTxt('MethodAdd'); ?></strong><br>
   <?php echo getTxt('MethodAddPara'); ?></p>
<!--<p><strong><a name="database" id="database"></a>Where can I find additional information about the database?</strong><br>
The database used for this software is modeled closely after those suggested by the Consortium of Universities for the Advancement of Hydrologic Sciences (CUAHSI). For additional information about the database structure, please refer to the <a href="http://his.cuahsi.org/documents/ODM1.1DesignSpecifications.pdf" target="_blank">Advanced Database Documentation</a> provided by them.</p>-->
<p><strong><a name="database" id="database"></a><?php echo getTxt('DataInfo'); ?></strong><br>
<?php echo getTxt('DataInfoPara1'); ?> <a href="http://his.cuahsi.org/documents/ODM1.1DesignSpecifications.pdf" target="_blank"><?php echo getTxt('DataInfoLink'); ?></a> <?php echo getTxt('DataInfoPara2'); ?></p>
<!--<p><strong><a name="Questions"></a>Still have questions?</strong><br>
        If you do not see your question or  answer you're seeking, please contact your direct supervisor about these additional  questions.</p>-->
     <p><strong><a name="Questions"></a><?php echo getTxt('StillQuestions'); ?></strong><br>
        <?php echo getTxt('StillQuestionsPara'); ?></p>
<!--     <p><strong><a name="CreatSite" id="CreatSite"></a>Ready to create a website of your own?</strong><br>
The HydroServer Lite Interactive Web Client is an open source software developed in connection with HydroDesktop through a grant  provided by <a href="http://idahoepscor.org/" target="_blank">Idaho EPSCoR</a>. HydroServer and HydroDesktop are part of the <a href="http://www.cuahsi.org/" title="Link to CUAHSI" target="_blank">Consortium of Universities for the Advancement of Hydrologic Sciences</a>, or commonly referred to as CUAHSI (pronounced &quot;kwä-ze&quot;). To learn more about this software or find out how your school or organization can get their own version of the HydroServer Lite Interactive Web Client, please visit us   <a href="http://hydroserverlite.codeplex.com/" target="_blank">here</a>.</p>-->
<p><strong><a name="CreatSite" id="CreatSite"></a><?php echo getTxt('CreateWebsite'); ?></strong><br>
<?php echo getTxt('CreateWebsitePara1'); ?> <a href="http://idahoepscor.org/" target="_blank">Idaho EPSCoR</a>. <?php echo getTxt('CreateWebsitePara2'); ?> <a href="http://www.cuahsi.org/" title="Link to CUAHSI" target="_blank"><?php echo getTxt('Consortium'); ?></a>, <?php echo getTxt('CreateWebsitePara3'); ?>   <a href="http://hydroserverlite.codeplex.com/" target="_blank"><?php echo getTxt('Here'); ?></a>.</p>
         <p>&nbsp;</p>
		 </div>
   	<?php HTML_Render_Body_End(); ?>
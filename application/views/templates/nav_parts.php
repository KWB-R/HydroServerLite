<?php
/* #######################################################
 This file is used to create the navigation menu for the site.
		The menu dynamically fills based on the permissions of the
		user based on their role.
 ####################################################### */

function getMenuName()
{
	$menuName = '';
	if (isAdmin())
		$menuName = "Administrator";
	elseif(isTeacher())
		$menuName = "Teacher";
	elseif(isStudent())
		$menuName = "Student";
	else
		$menuName = "Public";

	return $menuName;
}

//
// Helper Functions for generationg parts of this template
//

function html_linkItem($class, $url, $textKey, $attributes = '')
{
	return 
		html_li_beg($class) . 
			html_a(site_url($url), getTxt($textKey), $attributes) . 
		"</li>";
}

	//Removed id=nav from here for now, will add it back after styling is merged. 



echo '       <div class="navbar_navbar-default" role="navigation">
<div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="visible-xs navbar-brand">'.getTxt(getMenuName().'Navigation').'</span>
        </div>
		
		   <div class="navbar-collapse collapse sidebar-navbar-collapse transparentNav" id="navbarCollapse">
		   <ul class="nav nav-tabs nav-stacked">
		   ';

if(isAdmin())
{
		echo '<li class="nav-header"> <a href="#" data-toggle="collapse" data-target="#siteManagement">
    <h4>'.getTxt('SiteManagement').'</h4>
    </a>
      <ul style="list-style: none;" class="collapse" id="siteManagement">';

	echo html_linkItem("add_site", "banner/add", "AddNewBanner");
	echo html_linkItem("add_site", "home/edit", "EditWelcomePage");
	
	echo "</ul>";
	echo "</li>";
}

if (isTeacher() || isAdmin()){



	echo '<li class="nav-header"> <a href="#" data-toggle="collapse" data-target="#dbManagement">
    <h4>'.getTxt('DatabaseManagement').'</h4>
    </a>
      <ul style="list-style: none;" class="collapse" id="dbManagement">';

	if (isAdmin()){
		// > admin
		echo html_linkItem("add_source", "source/add", "AddSource");
		echo html_linkItem("edit_source", "source/change", "ChangeSource");
	}
	// > teacher admin
	echo html_linkItem("add_site", "sites/add", "AddSite");
	
	if (isAdmin()){
		// > admin
		echo html_linkItem("edit_site", "sites/change", "ChangeSite");
		echo html_linkItem("add_variable", "variable/add", "AddVariable");
		echo html_linkItem("edit_variable", "variable/edit", "ChangeVariable");
		echo html_linkItem("add_method", "methods/add", "AddMethod");
		echo html_linkItem("edit_method", "methods/change", "ChangeMethod");
		echo html_linkItem("edit_variable", "series", "EditSC");
	}
	echo "</ul>";
	echo "</li>";
	// teacher admin
	echo '<li class="nav-header"> <a href="#" data-toggle="collapse" data-target="#usermgmt">
    <h4>'.getTxt('Users').'</h4>
    </a>
      <ul style="list-style: none;" class="collapse" id="usermgmt">';
	echo html_linkItem("add_user", "user/add", "AddUser");
	echo html_linkItem("edit_user", "user/changepass", "ChangePassword");
	echo html_linkItem("edit_user", "user/changeownpass", "ChangeYourPassword");
	// > admin
	if (isAdmin())
		echo html_linkItem("change_authority", "user/edit", "ChangeAuthorityButton");

	// > teacher admin
	echo html_linkItem("remove_user", "user/delete", "RemoveUser");
	echo"</ul>";
	echo"</li>";
}		  

if (isStudent() || isTeacher() || isAdmin()){
	// student teacher
		echo '<li class="nav-header"> <a href="#" data-toggle="collapse" data-target="#dataMgmt">
    <h4>'.getTxt('AddData').'</h4>
    </a>
      <ul style="list-style: none;" class="collapse" id="dataMgmt">';
	echo html_linkItem("add_single_value", "datapoint/addvalue", "AddSingleValue");
	echo html_linkItem("add_multiple_value", "datapoint/addmultiplevalues", "AddMultipleValues");
	echo html_linkItem("import_data", "datapoint/importfile", "ImportDataFiles");
	echo "</ul>";
	echo "</li>";
}

 echo "<li class=\"search\"><a href='".site_url('sites/map')."'>".getTxt('SearchData')."</a></li>";
echo "<li class=\"help\"><a href='".site_url('home/help')."'>".getTxt('Help')."</a></li>";

echo "<li class=\"search\"><a href='".site_url("services")."'>".getTxt('WebServices')."</a></li>";  

if(isLoggedIn())	{
	echo "<li class=\"home\"><a href='".site_url("home")."'>".getTxt('Home')."</a></li>";
	echo "<li class=\"login\"><a href='".site_url("auth/logout")."'>".getTxt('Logout')."</a></li>";
}else{
	echo "<li class=\"login\"><a href='#' onclick='showLogin()';>".getTxt('LoginButton')."</a></li>";
}




echo'
</ul>
</div></div>';


if(!isLoggedIn()){
?>
<script type="text/javascript">
function showLogin(show) {
    var loginForm = $("#loginHolder");
    if (typeof show == "undefined" || show) {
        loginForm.show();
        $("#username").focus();
    } else {
        loginForm.hide();
    }
    return false;
}
</script>
<?php }?>

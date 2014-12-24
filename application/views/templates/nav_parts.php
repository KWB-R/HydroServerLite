<?php
/* #######################################################
 This file is used to create the navigation menu for the site.
		The menu dynamically fills based on the permissions of the
		user based on their role.
 ####################################################### */

$menuName = '';
if (isAdmin())
	$menuName = "Administrator";
elseif(isTeacher())
	$menuName =  "Teacher";
elseif(isStudent())
	$menuName =  "Student";
else
	$menuName =  "Public";

	//Removed id=nav from here for now, will add it back after styling is merged. 
	
echo "<div id='nav' class=\"sidebar-nav\"><h1></h1>
";

echo '       <div class="navbar navbar-default" role="navigation">
<div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="visible-xs navbar-brand">'.getTxt($menuName.'Navigation').'</span>
        </div>
		
		   <div class="navbar-collapse collapse sidebar-navbar-collapse" id="navbarCollapse">
          <ul class="nav navbar-nav">
		';

if (isAdmin()){
	echo '<li class=\"dropdown\">
	
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.getTxt('SiteManagement').'<b class="caret"></b></a>';
	echo "<ul class=\"dropdown-menu\">";
	echo "<li class=\"versions\"><a href='".site_url('versions')."'>".getTxt('Versions')."</a>";
	//echo "<li class=\"aliases\"><a href='edit_aliases.php'>Aliases</a>";
	echo "</ul>";
	echo "</li>";
}

// teacher admin
if (isTeacher() || isAdmin()){
	echo "<li><h2>".getTxt('DatabaseManagement')."</h2>";
	echo "<ul class=\"dropdown-menu\">";
	if (isAdmin()){
		// > admin
		echo "<li class=\"add_source\"><a href='".site_url('sources/add')."'>".getTxt('AddSource')."</a>";
		echo "<li class=\"edit_source\"><a href='".site_url('sources/change')."'>".getTxt('ChangeSource')."</a></li>";
	}
	// > teacher admin
	echo "<li class=\"add_site\"><a href='".site_url('sites/add')."'>".getTxt('AddSite')."</a></li>";
	if (isAdmin()){
		// > admin
		echo "<li class=\"edit_site\"><a href='".site_url('sites/change')."'>".getTxt('ChangeSite')."</a></li>";
		echo "<li class=\"add_variable\"><a href='".site_url('variables/add')."'>".getTxt('AddVariable')."</a></li>";
		echo "<li class=\"edit_variable\"><a href='".site_url('variables/change')."'>".getTxt('ChangeVariable')."</a></li>";
		echo "<li class=\"add_method\"><a href='".site_url('methods/add')."'>".getTxt('AddMethod')."</a></li>";
		echo "<li class=\"edit_method\"><a href='".site_url('methods/change')."'>".getTxt('ChangeMethod')."</a></li>";
	}
	echo "</ul>";
	echo "</li>";
	// teacher admin
	echo "<li>";
	//#type $__User Alias
	echo "<h2>".getTxt('Users')."</h2>";

	echo "<ul>";
	echo "<li class=\"add_user\"><a href='".site_url('user/add')."'>".getTxt('AddUser')."</a></li>";
	echo "<li class=\"edit_user\"><a href='".site_url('user/changepass')."'>".getTxt('ChangePassword')."</a></li>";
	echo "<li class=\"edit_user\"><a href='".site_url('user/changeownpassword')."'>".getTxt('ChangeYourPassword')."</a></li>";
	// > admin
	if (isAdmin())
		echo "<li class=\"change_authority\"><a href='".site_url('user/changeauth')."'>".getTxt('ChangeAuthorityButton')."</a></li>";

	// > teacher admin
	echo "<li class=\"remove_user\"><a href='".site_url('user/delete')."'>".getTxt('RemoveUser')."</a></li>";
	echo"</ul>";
	echo"</li>";
}
if (isStudent() || isTeacher() || isAdmin()){
	// student teacher
	echo "<li>";
	echo "<h2>".getTxt('AddData')."</h2>";
	echo "<ul>";
	echo "<li class=\"add_single_value\"><a href='".site_url('data/addpoint')."'>".getTxt('AddSingleValue')."</a></li>";
	echo "<li class=\"add_multiple_value\"><a href='".site_url('data/addmultiple')."'>".getTxt('AddMultipleValues')."</a></li>";
	if (!isset($_SESSION["importFilePath"]))
	{
		echo "<li class=\"import_data\"><a href='".site_url('data/importfile')."'>".getTxt('ImportDataFiles')."</a></li>";
	}else
	{
		echo "<li class=\"import_data\"><a href='".site_url('data/importfile')."'>".getTxt('ImportDataFiles')."</a></li>";
		echo "<li class=\"import_data\"><a href='importWizard.php'>".getTxt('ImportDataFiles')."</a></li>";
	}
	
	echo "</ul>";
	echo "</li>";
}

// [all]
// public student teacher admin [all]

echo "<li class=\"search\"><a href='".site_url('sites')."'>".getTxt('SearchData')."</a></li>";
echo "<li class=\"help\"><a href='".site_url('home/help')."'>".getTxt('Help')."</a></li>";


//Check session variable being set or not. 

if (!isset($_SESSION))
{
session_start();
}

if (isset($_SESSION['mainpath']))
{
$servicesPath = str_replace("main_config.php","services\\",$_SESSION['mainpath']);
$servicesPath = str_replace("\\","/",$servicesPath);
}
else
{
$servicesPath = "../services";
}

echo "<li class=\"search\"><a href='".$servicesPath."'>".getTxt('WebServices')."</a></li>";


//Commented out because this home button wasn't necessary, or it could be used later for something else...
//if (getRequestedPage() != "/index.php")	{
	//echo "<li class=\"home\"><a href='index.php'>Back to Home</a></li>";
//}

if(isLoggedIn())	{
	echo "<li class=\"home\"><a href='".base_url()."'>".getTxt('Home')."</a></li>";
	echo "<li class=\"login\"><a href='".site_url("auth/logout")."'>".getTxt('Logout')."</a></li>";
}else{
	echo "<li class=\"login\"><a href='#' onclick='showLogin()';>".getTxt('Login')."</a></li>";
}



//echo "<img class=\"footer\" src=\"assets/images/nav-footer.jpg\" />";
echo "</ul>
        </div></div></div>";

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
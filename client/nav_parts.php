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
          <span class="visible-xs navbar-brand">'.getText($menuName.'Navigation').'</span>
        </div>
		
		   <div class="navbar-collapse collapse sidebar-navbar-collapse" id="navbarCollapse">
          <ul class="nav navbar-nav">
		';

if (isAdmin()){
	echo '<li class=\"dropdown\">
	
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.getText('SiteManagement').'<b class="caret"></b></a>';
	echo "<ul class=\"dropdown-menu\">";
	echo "<li class=\"versions\"><a href='versions.php'>".getText('Versions')."</a>";
	//echo "<li class=\"aliases\"><a href='edit_aliases.php'>Aliases</a>";
	echo "</ul>";
	echo "</li>";
}

// teacher admin
if (isTeacher() || isAdmin()){
	echo "<li><h2>".getText('DatabaseManagement')."</h2>";
	echo "<ul class=\"dropdown-menu\">";
	if (isAdmin()){
		// > admin
		echo "<li class=\"add_source\"><a href='add_source.php'>".getText('AddSource')."</a>";
		echo "<li class=\"edit_source\"><a href='change_source.php'>".getText('ChangeSource')."</a></li>";
	}
	// > teacher admin
	echo "<li class=\"add_site\"><a href='add_site.php'>".getText('AddSite')."</a></li>";
	if (isAdmin()){
		// > admin
		echo "<li class=\"edit_site\"><a href='edit_site.php'>".getText('ChangeSite')."</a></li>";
		echo "<li class=\"add_variable\"><a href='add_variable.php'>".getText('AddVariable')."</a></li>";
		echo "<li class=\"edit_variable\"><a href='edit_var.php'>".getText('ChangeVariable')."</a></li>";
		echo "<li class=\"add_method\"><a href='add_method.php'>".getText('AddMethod')."</a></li>";
		echo "<li class=\"edit_method\"><a href='change_method.php'>".getText('ChangeMethod')."</a></li>";
	}
	echo "</ul>";
	echo "</li>";
	// teacher admin
	echo "<li>";
	//#type $__User Alias
	echo "<h2>".getText('Users')."</h2>";

	echo "<ul>";
	echo "<li class=\"add_user\"><a href='adduser.php'>".getText('AddUser')."</a></li>";
	echo "<li class=\"edit_user\"><a href='changepassword.php'>".getText('ChangePassword')."</a></li>";
	echo "<li class=\"edit_user\"><a href='change_yourpassword.php'>".getText('ChangeYourPassword')."</a></li>";
	// > admin
	if (isAdmin())
		echo "<li class=\"change_authority\"><a href='changeauthority.php'".getText('ChangeAuthorityButton')."</a></li>";

	// > teacher admin
	echo "<li class=\"remove_user\"><a href='removeuser.php'>".getText('RemoveUser')."</a></li>";
	echo"</ul>";
	echo"</li>";
}
if (isStudent() || isTeacher() || isAdmin()){
	// student teacher
	echo "<li>";
	echo "<h2>".getText('AddData')."</h2>";
	echo "<ul>";
	echo "<li class=\"add_single_value\"><a href='add_data_value.php'>".getText('AddSingleValue')."</a></li>";
	echo "<li class=\"add_multiple_value\"><a href='add_multiple_values.php'>".getText('AddMultipleValues')."</a></li>";
	if (!isset($_SESSION["importFilePath"]))
	{
		echo "<li class=\"import_data\"><a href='import_data_file.php'>".getText('ImportDataFiles')."</a></li>";
	}else
	{
		echo "<li class=\"import_data\"><a href='import_data_file.php'>".getText('ImportDataFiles')."</a></li>";
		echo "<li class=\"import_data\"><a href='importWizard.php'>".getText('ImportDataFiles')."</a></li>";
	}
	
	echo "</ul>";
	echo "</li>";
}

// [all]
// public student teacher admin [all]

echo "<li class=\"search\"><a href='view_main.php'>".getText('SearchData')."</a></li>";
echo "<li class=\"help\"><a href='help.php'>".getText('Help')."</a></li>";


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

echo "<li class=\"search\"><a href='".$servicesPath."'>".getText('WebServices')."</a></li>";


//Commented out because this home button wasn't necessary, or it could be used later for something else...
//if (getRequestedPage() != "/index.php")	{
	//echo "<li class=\"home\"><a href='index.php'>Back to Home</a></li>";
//}

if(isLoggedIn())	{
	echo "<li class=\"home\"><a href='home.php'>".getText('WebServices')."</a></li>";
	echo "<li class=\"login\"><a href='login_handler.php?logout=-1'>".getText('Logout')."</a></li>";
}else{
	echo "<li class=\"login\"><a href='#' onclick='showLogin()';>".getText('Login')."</a></li>";
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
<?php
/* #######################################################
 This file is used to create the navigation menu for the site.
		The menu dynamically fills based on the permissions of the
		user based on their role.
 ####################################################### */
require_once('authorization_check.php'); 
global $__User;
global $__Source;
global $__Site;
global $__Method;
global $__Variable;
global $__Value;

$menuName = '';
if (isAdmin())
	$menuName = "Administrator";
elseif(isTeacher())
	$menuName =  "Teacher";
elseif(isStudent())
	$menuName =  "Student";
else
	$menuName =  "Public";

echo "<div id='nav'><h1>$menuName Navigation</h1><ul>";

if (isAdmin()){
	echo "<li><h2>Site Management</h2>";
	echo "<ul>";
	echo "<li class=\"versions\"><a href='versions.php'>Versions</a>";
	//echo "<li class=\"aliases\"><a href='edit_aliases.php'>Aliases</a>";
	echo "</ul>";
	echo "</li>";
}

// teacher admin
if (isTeacher() || isAdmin()){
	echo "<li><h2>Database Management</h2>";
	echo "<ul>";
	if (isAdmin()){
		// > admin
		echo "<li class=\"add_source\"><a href='add_source.php'>Add a ".$__Source->Capitalized."</a>";
		echo "<li class=\"edit_source\"><a href='change_source.php'>Change a ".$__Source->Capitalized."</a></li>";
	}
	// > teacher admin
	echo "<li class=\"add_site\"><a href='add_site.php'>Add a ".$__Site->Capitalized."</a></li>";
	if (isAdmin()){
		// > admin
		echo "<li class=\"edit_site\"><a href='edit_site.php'>Change a ".$__Site->Capitalized."</a></li>";
		echo "<li class=\"add_variable\"><a href='add_variable.php'>Add a ".$__Variable->Capitalized."</a></li>";
		echo "<li class=\"edit_variable\"><a href='edit_var.php'>Change a ".$__Variable->Capitalized."</a></li>";
		echo "<li class=\"add_method\"><a href='add_method.php'>Add a ".$__Method->Capitalized."</a></li>";
		echo "<li class=\"edit_method\"><a href='change_method.php'>Change a ".$__Method->Capitalized."</a></li>";
	}
	echo "</ul>";
	echo "</li>";
	// teacher admin
	echo "<li>";
	//#type $__User Alias
	echo "<h2>".$__User->PluralCapitalized."</h2>";

	echo "<ul>";
	echo "<li class=\"add_user\"><a href='adduser.php'>Add ".$__User->Capitalized."</a></li>";
	echo "<li class=\"edit_user\"><a href='changepassword.php'>Change ".$__User->Capitalized."'s Password</a></li>";
	echo "<li class=\"edit_user\"><a href='change_yourpassword.php'>Change "." Your Password</a></li>";
	// > admin
	if (isAdmin())
		echo "<li class=\"change_authority\"><a href='changeauthority.php'>Change ".$__User->Capitalized."'s Authority</a></li>";

	// > teacher admin
	echo "<li class=\"remove_user\"><a href='removeuser.php'>Remove ".$__User->Capitalized."</a></li>";
	echo"</ul>";
	echo"</li>";
}
if (isStudent() || isTeacher() || isAdmin()){
	// student teacher
	echo "<li>";
	echo "<h2>Add Data</h2>";
	echo "<ul>";
	echo "<li class=\"add_single_value\"><a href='add_data_value.php'>Add a Single ".$__Value->Capitalized."</a></li>";
	echo "<li class=\"add_multiple_value\"><a href='add_multiple_values.php'>Add Multiple ".$__Value->PluralCapitalized."</a></li>";
	if (!isset($_SESSION["importFilePath"]))
	{
		echo "<li class=\"import_data\"><a href='import_data_file.php'>Import Data File</a></li>";
	}else
	{
		echo "<li class=\"import_data\"><a href='import_data_file.php'>Import Different Data File</a></li>";
		echo "<li class=\"import_data\"><a href='importWizard.php'>Return to Import Wizard</a></li>";
	}
	
	echo "</ul>";
	echo "</li>";
}

// [all]
// public student teacher admin [all]

echo "<li class=\"search\"><a href='view_main.php'>Search Data</a></li>";
echo "<li class=\"help\"><a href='help.php'>Help Center</a></li>";

//Commented out because this home button wasn't necessary, or it could be used later for something else...
//if (getRequestedPage() != "/index.php")	{
	//echo "<li class=\"home\"><a href='index.php'>Back to Home</a></li>";
//}

if(isLoggedIn())	{
	echo "<li class=\"home\"><a href='home.php'>Home</a></li>";
	echo "<li class=\"login\"><a href='login_handler.php?logout=-1'>Logout</a></li>";
}else{
	echo "<li class=\"login\"><a href='#' onclick='showLogin()';>Login</a></li>";
}


echo "</ul>";
//echo "<img class=\"footer\" src=\"images/nav-footer.jpg\" />";
echo "</div>";

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
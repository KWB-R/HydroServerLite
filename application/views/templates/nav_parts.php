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



echo '       <div class="navbar navbar-default " role="navigation">
<div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="visible-xs navbar-brand">'.getTxt($menuName.'Navigation').'</span>
        </div>
		
		   <div class="navbar-collapse collapse sidebar-navbar-collapse transparentNav" id="navbarCollapse">
		   <ul class="nav nav-tabs nav-stacked">
		   ';
if (isTeacher() || isAdmin()){

	echo '<li class="nav-header"> <a href="#" data-toggle="collapse" data-target="#dbManagement">
    <h4>'.getTxt('DatabaseManagement').'</h4>
    </a>
      <ul style="list-style: none;" class="collapse" id="dbManagement">';

	if (isAdmin()){
		// > admin
		echo "<li class=\"add_source\"><a href='".site_url('source/add')."'>".getTxt('AddSource')."</a></li>";
		echo "<li class=\"edit_source\"><a href='".site_url('source/change')."'>".getTxt('ChangeSource')."</a></li>";
	}
	// > teacher admin
	echo "<li class=\"add_site\"><a href='".site_url('sites/add')."'>".getTxt('AddSite')."</a></li>";
	if (isAdmin()){
		// > admin
		echo "<li class=\"edit_site\"><a href='".site_url('sites/change')."'>".getTxt('ChangeSite')."</a></li>";
		echo "<li class=\"add_variable\"><a href='".site_url('variable/add')."'>".getTxt('AddVariable')."</a></li>";
		echo "<li class=\"edit_variable\"><a href='".site_url('variable/edit')."'>".getTxt('ChangeVariable')."</a></li>";
		echo "<li class=\"add_method\"><a href='".site_url('methods/add')."'>".getTxt('AddMethod')."</a></li>";
		echo "<li class=\"edit_method\"><a href='".site_url('methods/change')."'>".getTxt('ChangeMethod')."</a></li>";
		echo "<li class=\"edit_variable\"><a href='".site_url('series')."'>".getTxt('EditSC')."</a></li>";
	}
	echo "</ul>";
	echo "</li>";
	// teacher admin
	echo '<li class="nav-header"> <a href="#" data-toggle="collapse" data-target="#usermgmt">
    <h4>'.getTxt('Users').'</h4>
    </a>
      <ul style="list-style: none;" class="collapse" id="usermgmt">';
	echo "<li class=\"add_user\"><a href='".site_url('user/add')."'>".getTxt('AddUser')."</a></li>";
	echo "<li class=\"edit_user\"><a href='".site_url('user/changepass')."'>".getTxt('ChangePassword')."</a></li>";
	echo "<li class=\"edit_user\"><a href='".site_url('user/changeownpass')."'>".getTxt('ChangeYourPassword')."</a></li>";
	// > admin
	if (isAdmin())
		echo "<li class=\"change_authority\"><a href='".site_url('user/edit')."'>".getTxt('ChangeAuthorityButton')."</a></li>";

	// > teacher admin
	echo "<li class=\"remove_user\"><a href='".site_url('user/delete')."'>".getTxt('RemoveUser')."</a></li>";
	echo"</ul>";
	echo"</li>";
}		  

if (isStudent() || isTeacher() || isAdmin()){
	// student teacher
		echo '<li class="nav-header"> <a href="#" data-toggle="collapse" data-target="#dataMgmt">
    <h4>'.getTxt('AddData').'</h4>
    </a>
      <ul style="list-style: none;" class="collapse" id="dataMgmt">';
	echo "<li class=\"add_single_value\"><a href='".site_url('datapoint/addvalue')."'>".getTxt('AddSingleValue')."</a></li>";
	echo "<li class=\"add_multiple_value\"><a href='".site_url('datapoint/addmultiplevalues')."'>".getTxt('AddMultipleValues')."</a></li>";
	echo "<li class=\"import_data\"><a href='".site_url('datapoint/importfile')."'>".getTxt('ImportDataFiles')."</a></li>";
	echo "</ul>";
	echo "</li>";
}

 echo "<li class=\"search\"><a href='".site_url('sites/map')."'>".getTxt('SearchData')."</a></li>";
echo "<li class=\"help\"><a href='".site_url('home/help')."'>".getTxt('Help')."</a></li>";

$servicesPath = "../services";
echo "<li class=\"search\"><a href='".$servicesPath."'>".getTxt('WebServices')."</a></li>";  

if(isLoggedIn())	{
	echo "<li class=\"home\"><a href='".base_url()."'>".getTxt('Home')."</a></li>";
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
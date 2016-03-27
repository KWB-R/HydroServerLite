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

function html_li_sublist($keyword, $id, $items)
{
	return
		html_li_beg("nav-header") .
			html_h(
				html_a(
					"#", # target
					getTxt($keyword), # content
					html_attribs(array("data-toggle" => "collapse",	"data-target" => "#$id"))
				),
				4 // h4 header
			) .
			html_ul_beg("collapse", $id, "list-style: none;") .
				implode("\n", $items) .
			"</ul>" .
		"</li>";
}

//
// Provide configuration of submenus in an array and fill the items according
// to the role of the current user
//

$menuConfig = array(
	'Site' => array(
		'keyword' => 'SiteManagement', 
		'id' => "siteManagement", 
		'items' => array()
	),
	'DB' => array(
		'keyword' => 'DatabaseManagement', 
		'id' => 'dbManagement', 
		'items' => array()
	),
	'User' => array(
		'keyword' => 'Users', 
		'id' => 'usermgmt', 
		'items' => array()
	),
	'Data' => array(
		'keyword' => 'AddData', 
		'id' => 'dataMgmt', 
		'items' => array()
	)
);

if (isAdmin()) {

	$menuConfig['Site']['items'] = array(
		html_linkItem("add_site", "banner/add", "AddNewBanner"),
		html_linkItem("add_site", "home/edit", "EditWelcomePage")
	);

	$menuConfig['DB']['items'] = array(
		html_linkItem("add_source", "source/add", "AddSource"),
		html_linkItem("edit_source", "source/change", "ChangeSource")
	);
}

if (isTeacher() || isAdmin()) {
	$menuConfig['DB']['items'][] = html_linkItem("add_site", "sites/add", "AddSite");
}

if (isAdmin()) {
	$menuConfig['DB']['items'] = array_merge(
		$menuConfig['DB']['items'], array(
			html_linkItem("edit_site", "sites/change", "ChangeSite"),
			html_linkItem("add_variable", "variable/add", "AddVariable"),
			html_linkItem("edit_variable", "variable/edit", "ChangeVariable"),
			html_linkItem("add_method", "methods/add", "AddMethod"),
			html_linkItem("edit_method", "methods/change", "ChangeMethod"),
			html_linkItem("edit_variable", "series", "EditSC")
		)
	);
}

if (isTeacher() || isAdmin()) {
	$menuConfig['User']['items'] = array(
		html_linkItem("add_user", "user/add", "AddUser"),
		html_linkItem("edit_user", "user/changepass", "ChangePassword"),
		html_linkItem("edit_user", "user/changeownpass", "ChangeYourPassword")
	);
	if (isAdmin()) {
		$menuConfig['User']['items'][] = html_linkItem("change_authority", "user/edit", "ChangeAuthorityButton");
	}
	$menuConfig['User']['items'][] = html_linkItem("remove_user", "user/delete", "RemoveUser");
}

if (isStudent() || isTeacher() || isAdmin()) {
	$menuConfig['Data']['items'] = array(
		html_linkItem("add_single_value", "datapoint/addvalue", "AddSingleValue"),
		html_linkItem("add_multiple_value", "datapoint/addmultiplevalues", "AddMultipleValues"), 
		html_linkItem("import_data", "datapoint/importfile", "ImportDataFiles")
	);
}

//Removed id=nav from here for now, will add it back after styling is merged. 

// Here starts the output
echo html_div_beg("navbar_navbar-default", "", "navigation");
echo html_div_beg("navbar-header");
echo html_button_beg("navbar-toggle", "collapse", "#navbarCollapse");
echo html_span("sr-only", "Toggle navigation");
echo html_span("icon-bar");
echo html_span("icon-bar");
echo html_span("icon-bar");
echo "</button>";
echo html_span("visible-xs navbar-brand", getTxt(getMenuName() . 'Navigation'));
echo "</div>"; // navbar-header
echo html_div_beg( 
	"navbar-collapse collapse sidebar-navbar-collapse transparentNav", # class
	"navbarCollapse" # id
);
echo html_ul_beg("nav nav-tabs nav-stacked");

foreach ($menuConfig as $subConfig) {
	if (count($subConfig['items']) > 0) {
		echo html_li_sublist(
			$subConfig['keyword'], 
			$subConfig['id'], 
			$subConfig['items']
		);
	}
}

echo html_linkItem("search", "sites/map", "SearchData");
echo html_linkItem("help", "home/help", "Help");

$publicAccess = config_item("public_access");

if (isset($publicAccess) && ($publicAccess === TRUE)) {
	echo html_linkItem("search", "services", 'WebServices');
}

if(isLoggedIn()) {
	echo html_linkItem("home", "home", "Home");
	echo html_linkItem("login", "auth/logout", "Logout");
}
else {
	echo html_linkItem("login", "#", "LoginButton", 'onclick="return showLogin();"');
}

echo "</ul>";
echo "</div>";
echo "</div>";

if (! isLoggedIn()) {
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
	<?php 
}
?>

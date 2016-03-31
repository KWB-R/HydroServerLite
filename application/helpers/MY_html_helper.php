<?php

//
// General HTML Helper Functions
//

// Functions for setting HTML attributes

function html_attr($name, $value = '')
{
	return (($value == '')? '' : " $name=\"$value\"");
}

function html_attribs($assignments)
{
	$pieces = array();

	foreach ($assignments as $key => $value) {
		if ($value != '') {
			$pieces[] = html_attr($key, $value);
		}
	}

	return implode("", $pieces);
}

// Full HTML Tags
function html_label($class, $content)
{
	return "<label class=\"$class\">$content</label>\n";
}

function html_tr($content)
{
	return "<tr>" . $content . "</tr>\n";
}

function html_td_left($content = '')
{
	return '<td align="left">' . $content . "</td>\n";
}

function html_td_right($content = '', $attributes = '')
{
	return "<td align=\"right\" $attributes>$content</td>\n";
}

function html_input($id, $content)
{
	return "<input id=\"$id\" $content />\n";
}

function html_input_button($id, $value, $content = '')
{
	return "<input id=\"$id\" type=\"button\" value=\"$value\" $content />\n";
}

function html_b($content)
{
	return '<b>' . $content . '</b>';
}

function html_a($url, $content, $attributes = '')
{
	return "\n<a" . html_attr("href", $url) .
		($attributes? " " . $attributes : "") . ">" . $content . "</a>";
}

function html_span($class, $content = '')
{
	return "<span" . html_attr("class", $class) . '>' . $content . "</span>";
}

function html_option($value, $content)
{
	return "<option value=\"$value\">$content</option>\n";
}

// HTML tag starts

function html_button_beg($class, $toggle, $target)
{
	$attribs = html_attribs(array(
		"type" => "button",
		"class" => $class,
		"data-toggle" => $toggle,
		"data-target" => $target
	));

	return "\n<button" . $attribs . ">";
}

function html_div_beg($class = '', $id = '', $role = '')
{
	$attribs = html_attribs(array(
		"class" => $class, "id" => $id, "role" => $role
	));

	return "<div" . $attribs . ">\n";
}

function html_div_end($indent = 0, $eol = true)
{
	return str_repeat('  ', $indent) . "</div>" . ($eol ? "\n" : '');
}

function html_li_beg($class = '')
{
	return "\n<li" . html_attr("class", $class) . ">";
}

function html_ul_beg($class = '', $id = '', $style = '')
{
	return "\n<ul" . html_attribs(
		array("class" => $class, "id" => $id, "style" => $style)
	) . ">";
}

function encloseInBeginEndComments($html, $sectionName = 'section')
{
	return
		"\n<!-- BEGIN $sectionName -->\n\n" .
		$html .
		"\n<!-- END $sectionName -->\n\n";
}

// Function for generating options. Keeping it here as it might be used by 
// various controllers once they get the result from the model.

function optionsSource($result)
{
	$html = '';

	foreach ($result as $row) {

		$html .= html_option($row["SourceID"], $row["Organization"]);
	}

	return $html;
}

function optionsVariable($result)
{
	$html = '';

	foreach ($result as $row) {

		$typename = translateTerm($row["VariableName"]);
		$datatype = translateTerm($row["DataType"]);

		$html .= html_option($row["VariableID"], "$typename ($datatype)");
	}

	return $html;
}

function genOptions($result)
{
	$html = '';

	foreach ($result as $key => $value) {
		$html .= html_option($key, $value);
	}

	return $html;
}

function getImg($name)
{
	return base_url() . "assets/images/" . $name;
}

function getDetailsImg($name)
{
	return base_url() . "uploads/" . $name;
}

function requiredSpan($req)
{
	$span = "    <span class=\"required\"></span>\n";
	//$span = '<span class="required" />';

	return $req ? $span : '';
}

function genInput($labelKey, $id, $name, $req = false, $extra = '')
{
	echo html_formGroup("text", $labelKey, $id, $name, $req, $extra);
}

function genInputD($labelKey, $id, $name, $req = false, $extra = '')
{
	echo html_formGroup("textarea", $labelKey, $id, $name, $req, $extra);
}

function genInputH($labelKey, $id, $name, $hint, $req = false, $extra = '')
{
	echo html_formGroup("text", $labelKey, $id, $name, $req, $extra, $hint);
}

function genInputT($labelKey, $id, $name, $req = false, $extra = '', $help)
{
	echo html_formGroup('text', $labelKey, $id, $name, $req, $extra, '', $help);
}

function genDropLists($labelKey, $id, $name, $req = false, $hint = '')
{
	echo html_formGroup('div', $labelKey, $id, $name, $req, '', $hint);
}

function genDropListsH($labelKey, $id, $name, $hint, $req = false)
{
	genDropLists($labelKey, $id, $name, $req, $hint);
}

function html_formGroup($type, $labelKey, $id, $name, $req = false, $extra = '',
	$hint = '', $help = '', $extraSelect = ''
)
{
	$html  = html_div_beg('form-group');
	$html .= '  ' . html_label('col-sm-2 control-label', getTxt($labelKey));
	$html .= '  ' . html_div_beg('col-sm-10');
	$html .= '    '; // indentation

	switch ($type) {
		case 'text':
			$html .= "<input type=\"text\" class=\"form-control\" id=\"$id\" name=\"$name\" $extra>\n";
			break;
		case 'textarea':
			$html .= "<textarea class=\"form-control\" rows=\"6\" id=\"$id\" name=\"$name\" $extra></textarea>\n";
			break;
		case 'div':
			$html .= "<div id=\"$id\" name=\"$name\"></div>\n";
			break;
		case 'select':
			$html .= "<select name=\"$name\" class=\"form-control\" id=\"$id\" $extra>\n";
			$html .= $extraSelect;
			break;
		default:
			break;
	}

	if ($type !== 'select') {
		$html .= requiredSpan($req);
	}

	if ($hint !== '') {
		$html .= "    <span class=\"hint\" title=\"$hint\">?</span>\n";
	}

	if ($help !== '') {
		$html .= "    <span class=\"em\">" . nbs(2) . getTxt($help) . "</span>\n";
	}

	if ($type === 'select') {
		$html .= requiredSpan($req);
	}

	$html .= "  </div>\n";
	$html .= "</div>\n";

	return $html;
}

function genSelect($labelKey, $id, $name, $optionBlock, $defaultSelect = false,
	$req = false, $extra = '', $hint = '')
{
	if ($defaultSelect) {
		$extraSelect = '<option value="-1">' . getTxt($defaultSelect) . '</option>' .
			$optionBlock . "</select>\n";
	} else {
		$extraSelect = '';
	}

	echo html_formGroup(
		'select', $labelKey, $id, $name, $req, $extra, $hint, '', $extraSelect
	);
}

function genSelectH($labelKey, $id, $name, $optionBlock, $hint,
	$defaultSelect = false, $req = false, $extra = '')
{
	genSelect(
		$labelKey, $id, $name, $optionBlock, $defaultSelect, $req, $extra, $hint
	);
}

function genHeading($headingKey, $req = false, $defaultColumn = 9)
{
	echo "<div class=\"col-md-$defaultColumn\">\n";

	// ALSO DISPLAYS THE ERROR MSGS. In case this function is not being used,
	// a call to the below function will be needed. 

	showMsgs();

	if ($req) {
		echo '<p class="em" align="right">' . getTxt('RequiredFieldsAsterisk') .
			"</p>\n";
	}

	echo '<p class="h3" align="center"><strong>' . getTxt($headingKey) .
		"</strong></p>\n";

	echo '<p>' . nbs(1) . "</p>\n";
}

function genSubmit($labelKey, $end = true)
{
	$html  = '<div class="col-md-3 col-md-offset-9">';
	$html .= '<input type="SUBMIT" name="submit" value="' . getTxt($labelKey) .
		'" class="button"/>';
	$html .= "</div>\n";
	$html .= "</form>\n";

	if ($end) {
		$html .= "</div>";
	}

	echo $html;
}

function HTML_Render_Head($js_vars, $PageTitle = "")
{
	$HeaderAddon = "";
	$WebClient = "HydroServer Lite";
	$faviconlink = base_url("assets/images/favicon.ico");

	if (isset($PageTitle) && $PageTitle != "") {
		$HeaderAddon = ": " . $PageTitle;
	}

	echo <<<PageHead
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>$WebClient $HeaderAddon</title>
		<link rel="shortcut icon" href="$faviconlink" type="image/x-icon" />
		<link rel="bookmark" href="favicon.ico" />
		<script type="text/javascript">
		$js_vars
		</script>

PageHead;
}

function HTML_Render_Body_Start()
{
	global $_SITE_Minimum_PHP_Version;

	$HTML_1 = <<<PageBody1
	</head>
	<body>
		<div class="container">
		<div class="masthead">
PageBody1;

	$HTML_1A = <<<PartA
		</div>

<!-- /container -->
PartA;

	$HTML_2 = <<<PageBody2
		<div class="row mainContainer" style="margin-left:0px;margin-right:0px;">
		<div class="col-md-3" id="navArea">
PageBody2;

	$HTML_3 = <<<PageBody3
		</div>

PageBody3;

	echo $HTML_1;

	echo getTopBanner();

	echo $HTML_1A;

	$CI = &get_instance();
	$CI->load->view('templates/header');

	echo $HTML_2;

	$CI->load->view('templates/nav_parts');

//	if ($instanceName->isAdmin()) //Still don't know what this does, maybe manages versions? Will worry about this when I reach that page. 
//		checkPHPVersion($_SITE_Minimum_PHP_Version);

	echo $HTML_3;
}

function HTML_Render_Body_StartInstall()
{
	global $_SITE_Minimum_PHP_Version;

	$HTML_1 = <<<PageBody1
	</head>
	<body>
		<div class="container">
		<div class="masthead">
PageBody1;

	$HTML_1A = 	<<<PartA
		</div>

<!-- /container -->
PartA;

	$HTML_2 = <<<PageBody2
		<div class="row mainContainer" style="margin-left:0px;margin-right:0px;">
		<div class="col-md-3">
PageBody2;

	$HTML_3 = <<<PageBody3
		</div>
PageBody3;

	echo $HTML_1;

	echo getTopBanner();

	echo $HTML_1A;

	$CI = &get_instance();
	$CI->load->view('templates/header');

	echo $HTML_2;

//	if ($instanceName->isAdmin()) //Still don't know what this does, maybe manages versions? Will worry about this when I reach that page. 
//		checkPHPVersion($_SITE_Minimum_PHP_Version);

	echo $HTML_3;
}

function showMsgs()
{
	if (
		isset($_SESSION["Errors"]) ||
		isset($_SESSION["Warnings"]) ||
		isset($_SESSION["Successes"])
	)
	{
		echo "<ul class=\"messages\">";
		showMessages("Errors","error");
		showMessages("Warnings","warning");
		showMessages("Successes","success");
		echo "</ul>";
	}
}

function getTopBanner()
{
	if (!defined('BASEURL2')) {
		define('BASEURL2',"");
	}
	
	$name = 'topBanner' . substr(BASEURL2, 0, -1);

	//Check uploads directory for topBanner
	$topBanner = "";
	$extensions = array('.gif','.jpg','.png','.jpeg');

	foreach ($extensions as $extension) {

		$path = "uploads/" . $name . $extension;

		if (file_exists(FCPATH . $path)) {

			$topBanner = base_url() . $path;
		}
	}

	if ($topBanner == "") {
		$topBanner = getImg("WebClientBanner.png");
	}

	return '<img src="' . $topBanner . '" alt="logo" class="img"  style="max-width:auto; max-height:120px;"/>';
}

function checkPHPVersion($minimumVersion)
{
	$phpVersion = phpversion();
	
	if (! isVersionGreater($phpVersion,$minimumVersion)) {
		addError(sprintf("Your version of PHP (%s) is not high enough. Components of this site require PHP %s.".
			" Please visit the <a href='versions.php'>Versions page</a> for more details.",
			$phpVersion, $minimumVersion));
	}
}

function isVersionGreater($currentVersion,$minimumVersion)
{
	$minimumParts = explode(".", $minimumVersion);
	$currentParts = explode(".", $currentVersion);

	$isGreater = false;

	if ((int) $currentParts[0] >= (int) $minimumParts[0])
		if ((int) $currentParts[1] >= (int) $minimumParts[1])
			if ((int) $currentParts[2] >= (int) $minimumParts[2])
				$isGreater = true;
			else
				$isGreater = false;
		else
			$isGreater = false;
	else
		$isGreater = false;

	return $isGreater;
}

function showMessages($key, $class)
{
	if (isset($_SESSION[$key])) {

		$errors = $_SESSION[$key];

		foreach ($errors as $mess) {
			echo "<li class=\"$class\">$mess</li>";
		}
	}

	unset($_SESSION[$key]);
}

function HTML_Render_Body_End()
{
	$HTML_1 =   " </div>   <!-- Closing row -->";

	$HTML_1A = '
	</div> <!-- Closing Container -->
		<div id="popUp">
			<h1 id="popTitle"><span id="popTitleText">Details</span>
				<span id="popClose" onclick="$(this).parent().parent().hide(1000);">X</span>
			</h1>
			<div id="popMessage">
				Empty Message
			</div>
		</div>';

	echo $HTML_1;

	$CI = &get_instance();
	$CI->load->view('templates/footer');

	echo $HTML_1A;

	if (!isLoggedIn()) {
		$CI->load->view('templates/login');
	}

	echo "</body>\n";
	echo "</html>\n";
}

$__DateTimeFormats = array(
	"ATOM, RFC3339, W3C" => DateTime::ATOM,
	"COOKIE, RFC850" => DateTime::COOKIE,
	"ISO8601" => DateTime::ISO8601,
	"RFC822, RFC1036" => DateTime::RFC1036,
	"RFC1123, RFC2822, RSS" => DateTime::RFC2822,
	"Simple" => "Y-m-d H:i:s", // (2011/03/14 20:04:23)
	"Condensed" => "Ymd His" // (20110314 200423)
);

//"ATOM" => "Y-m-d\TH:i:sP", // //(2013-08-01T18:36:42-07:00)
//"COOKIE" => "l, d-M-y H:i:s T",  //(Thursday, 01-Aug-13 18:36:42 GMT+7)
//"ISO8601" => "Y-m-d\TH:i:sO",  //(2013-08-01T18:36:42-0700)
//"RFC822" => "D, d M y H:i:s O",  //(Thu, 01 Aug 13 18:36:42 -0700)
//"RFC850" => "l, d-M-y H:i:s T",  //(Thursday, 01-Aug-13 18:36:42 GMT+7)
//"RFC1036" => "D, d M y H:i:s O",  //(Thu, 01 Aug 13 18:36:42 -0700)
//"RFC1123" => "D, d M Y H:i:s O",  //(Thu, 01 Aug 2013 18:36:42 -0700)
//"RFC2822" => "D, d M Y H:i:s O",  //(Thu, 01 Aug 2013 18:36:42 -0700)
//"RFC3339" => "Y-m-d\TH:i:sP",  //(2013-08-01T18:36:42-07:00)
//"RSS" => "D, d M Y H:i:s O",  //(Thu, 01 Aug 2013 18:36:42 -0700)
//"W3C" => "Y-m-d\TH:i:sP",  //(2013-08-01T18:36:42-07:00)
//"Simple" => "Y-m-d H:i:s",  //(2013-08-01 18:36:42)
//"Condensed" => "Ymd His"  //(20130801 183642)

$__DateFormats = array(
	"ATOM, ISO8601, RFC3339, W3C" => "Y-m-d",  //(2013-08-01)
	"COOKIE, RFC850" => "l, d-M-y",  //(Thursday, 01-Aug-13)
	"RFC822, RFC1036" => "D, d M y",  //(Thu, 01 Aug 13)
	"RFC1123, RFC2822, RSS" => "D, d M Y",  //(Thu, 01 Aug 2013)
	"Simple (Year First)" =>"y/m/d", // 13/08/01
	"Simple (Full Year)" =>"Y/m/d", // 2013/08/01
	"Simple (Month First)" =>"m/d/Y", // 08/01/2013
	"Simple (Day First)" =>"d/m/Y", // 08/01/2013
	"Condensed" => "Ymd"  //(20130801)
	);

$__TimeFormats = array(
	"ATOM, RFC3339, W3C" => "\TH:i:sP",  //(T18:36:42-07:00)
	"COOKIE, RFC850" => "H:i:s T",  //(18:36:42 GMT+7)
	"ISO8601" => "\TH:i:sO",  //(T18:36:42-0700)
	"RFC822, RFC1036, RFC1123, RFC2822, RSS" => "H:i:s O",  //(18:36:42 -0700)
	"Simple" => "H:i:s",  //(18:36:42)
	"Condensed" => "His"  //(183642)
	);

// formatted using answer at: http://stackoverflow.com/questions/4755704/php-timezone-list
$__TimeZones = array(
	'(GMT-12:00) International Date Line West' => 'Pacific/Wake',
	'(GMT-11:00) Midway Island' => 'Pacific/Apia',
	'(GMT-11:00) Samoa' => 'Pacific/Apia',
	'(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
	'(GMT-09:00) Alaska' => 'America/Anchorage',
	'(GMT-08:00) Pacific Time (US &amp; Canada); Tijuana' => 'America/Los_Angeles',
	'(GMT-07:00) Arizona' => 'America/Phoenix',
	'(GMT-07:00) Chihuahua' => 'America/Chihuahua',
	'(GMT-07:00) La Paz' => 'America/Chihuahua',
	'(GMT-07:00) Mazatlan' => 'America/Chihuahua',
	'(GMT-07:00) Mountain Time (US &amp; Canada)' => 'America/Denver',
	'(GMT-06:00) Central America' => 'America/Managua',
	'(GMT-06:00) Central Time (US &amp; Canada)' => 'America/Chicago',
	'(GMT-06:00) Guadalajara' => 'America/Mexico_City',
	'(GMT-06:00) Mexico City' => 'America/Mexico_City',
	'(GMT-06:00) Monterrey' => 'America/Mexico_City',
	'(GMT-06:00) Saskatchewan' => 'America/Regina',
	'(GMT-05:00) Bogota' => 'America/Bogota',
	'(GMT-05:00) Eastern Time (US &amp; Canada)' => 'America/New_York',
	'(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
	'(GMT-05:00) Lima' => 'America/Bogota',
	'(GMT-05:00) Quito' => 'America/Bogota',
	'(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
	'(GMT-04:00) Caracas' => 'America/Caracas',
	'(GMT-04:00) La Paz' => 'America/Caracas',
	'(GMT-04:00) Santiago' => 'America/Santiago',
	'(GMT-03:30) Newfoundland' => 'America/St_Johns',
	'(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
	'(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
	'(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
	'(GMT-03:00) Greenland' => 'America/Godthab',
	'(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
	'(GMT-01:00) Azores' => 'Atlantic/Azores',
	'(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
	'(GMT) Casablanca' => 'Africa/Casablanca',
	'(GMT) Edinburgh' => 'Europe/London',
	'(GMT) Greenwich Mean Time : Dublin' => 'Europe/London',
	'(GMT) Lisbon' => 'Europe/London',
	'(GMT) London' => 'Europe/London',
	'(GMT) Monrovia' => 'Africa/Casablanca',
	'(GMT+01:00) Amsterdam' => 'Europe/Berlin',
	'(GMT+01:00) Belgrade' => 'Europe/Belgrade',
	'(GMT+01:00) Berlin' => 'Europe/Berlin',
	'(GMT+01:00) Bern' => 'Europe/Berlin',
	'(GMT+01:00) Bratislava' => 'Europe/Belgrade',
	'(GMT+01:00) Brussels' => 'Europe/Paris',
	'(GMT+01:00) Budapest' => 'Europe/Belgrade',
	'(GMT+01:00) Copenhagen' => 'Europe/Paris',
	'(GMT+01:00) Ljubljana' => 'Europe/Belgrade',
	'(GMT+01:00) Madrid' => 'Europe/Paris',
	'(GMT+01:00) Paris' => 'Europe/Paris',
	'(GMT+01:00) Prague' => 'Europe/Belgrade',
	'(GMT+01:00) Rome' => 'Europe/Berlin',
	'(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
	'(GMT+01:00) Skopje' => 'Europe/Sarajevo',
	'(GMT+01:00) Stockholm' => 'Europe/Berlin',
	'(GMT+01:00) Vienna' => 'Europe/Berlin',
	'(GMT+01:00) Warsaw' => 'Europe/Sarajevo',
	'(GMT+01:00) West Central Africa' => 'Africa/Lagos',
	'(GMT+01:00) Zagreb' => 'Europe/Sarajevo',
	'(GMT+02:00) Athens' => 'Europe/Istanbul',
	'(GMT+02:00) Bucharest' => 'Europe/Bucharest',
	'(GMT+02:00) Cairo' => 'Africa/Cairo',
	'(GMT+02:00) Harare' => 'Africa/Johannesburg',
	'(GMT+02:00) Helsinki' => 'Europe/Helsinki',
	'(GMT+02:00) Istanbul' => 'Europe/Istanbul',
	'(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
	'(GMT+02:00) Kyiv' => 'Europe/Helsinki',
	'(GMT+02:00) Minsk' => 'Europe/Istanbul',
	'(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
	'(GMT+02:00) Riga' => 'Europe/Helsinki',
	'(GMT+02:00) Sofia' => 'Europe/Helsinki',
	'(GMT+02:00) Tallinn' => 'Europe/Helsinki',
	'(GMT+02:00) Vilnius' => 'Europe/Helsinki',
	'(GMT+03:00) Baghdad' => 'Asia/Baghdad',
	'(GMT+03:00) Kuwait' => 'Asia/Riyadh',
	'(GMT+03:00) Moscow' => 'Europe/Moscow',
	'(GMT+03:00) Nairobi' => 'Africa/Nairobi',
	'(GMT+03:00) Riyadh' => 'Asia/Riyadh',
	'(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
	'(GMT+03:00) Volgograd' => 'Europe/Moscow',
	'(GMT+03:30) Tehran' => 'Asia/Tehran',
	'(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
	'(GMT+04:00) Baku' => 'Asia/Tbilisi',
	'(GMT+04:00) Muscat' => 'Asia/Muscat',
	'(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
	'(GMT+04:00) Yerevan' => 'Asia/Tbilisi',
	'(GMT+04:30) Kabul' => 'Asia/Kabul',
	'(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
	'(GMT+05:00) Islamabad' => 'Asia/Karachi',
	'(GMT+05:00) Karachi' => 'Asia/Karachi',
	'(GMT+05:00) Tashkent' => 'Asia/Karachi',
	'(GMT+05:30) Chennai' => 'Asia/Calcutta',
	'(GMT+05:30) Kolkata' => 'Asia/Calcutta',
	'(GMT+05:30) Mumbai' => 'Asia/Calcutta',
	'(GMT+05:30) New Delhi' => 'Asia/Calcutta',
	'(GMT+05:45) Kathmandu' => 'Asia/Katmandu',
	'(GMT+06:00) Almaty' => 'Asia/Novosibirsk',
	'(GMT+06:00) Astana' => 'Asia/Dhaka',
	'(GMT+06:00) Dhaka' => 'Asia/Dhaka',
	'(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
	'(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
	'(GMT+06:30) Rangoon' => 'Asia/Rangoon',
	'(GMT+07:00) Bangkok' => 'Asia/Bangkok',
	'(GMT+07:00) Hanoi' => 'Asia/Bangkok',
	'(GMT+07:00) Jakarta' => 'Asia/Bangkok',
	'(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
	'(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
	'(GMT+08:00) Chongqing' => 'Asia/Hong_Kong',
	'(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
	'(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
	'(GMT+08:00) Kuala Lumpur' => 'Asia/Singapore',
	'(GMT+08:00) Perth' => 'Australia/Perth',
	'(GMT+08:00) Singapore' => 'Asia/Singapore',
	'(GMT+08:00) Taipei' => 'Asia/Taipei',
	'(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
	'(GMT+08:00) Urumqi' => 'Asia/Hong_Kong',
	'(GMT+09:00) Osaka' => 'Asia/Tokyo',
	'(GMT+09:00) Sapporo' => 'Asia/Tokyo',
	'(GMT+09:00) Seoul' => 'Asia/Seoul',
	'(GMT+09:00) Tokyo' => 'Asia/Tokyo',
	'(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
	'(GMT+09:30) Adelaide' => 'Australia/Adelaide',
	'(GMT+09:30) Darwin' => 'Australia/Darwin',
	'(GMT+10:00) Brisbane' => 'Australia/Brisbane',
	'(GMT+10:00) Canberra' => 'Australia/Sydney',
	'(GMT+10:00) Guam' => 'Pacific/Guam',
	'(GMT+10:00) Hobart' => 'Australia/Hobart',
	'(GMT+10:00) Melbourne' => 'Australia/Sydney',
	'(GMT+10:00) Port Moresby' => 'Pacific/Guam',
	'(GMT+10:00) Sydney' => 'Australia/Sydney',
	'(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
	'(GMT+11:00) Magadan' => 'Asia/Magadan',
	'(GMT+11:00) New Caledonia' => 'Asia/Magadan',
	'(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
	'(GMT+12:00) Auckland' => 'Pacific/Auckland',
	'(GMT+12:00) Fiji' => 'Pacific/Fiji',
	'(GMT+12:00) Kamchatka' => 'Pacific/Fiji',
	'(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
	'(GMT+12:00) Wellington' => 'Pacific/Auckland',
	'(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
);

$_Countries = array(
	"AF" => "Afghanistan",
	"AL" => "Albania",
	"DZ" => "Algeria",
	"AS" => "American Samoa",
	"AD" => "Andorra",
	"AO" => "Angola",
	"AI" => "Anguilla",
	"AQ" => "Antarctica",
	"AG" => "Antigua and Barbuda",
	"AR" => "Argentina",
	"AM" => "Armenia",
	"AW" => "Aruba",
	"AU" => "Australia",
	"AT" => "Austria",
	"AZ" => "Azerbaijan",
	"BS" => "Bahamas",
	"BH" => "Bahrain",
	"BD" => "Bangladesh",
	"BB" => "Barbados",
	"BY" => "Belarus",
	"BE" => "Belgium",
	"BZ" => "Belize",
	"BJ" => "Benin",
	"BM" => "Bermuda",
	"BT" => "Bhutan",
	"BO" => "Bolivia",
	"BA" => "Bosnia and Herzegowina",
	"BW" => "Botswana",
	"BV" => "Bouvet Island",
	"BR" => "Brazil",
	"IO" => "British Indian Ocean Territory",
	"BN" => "Brunei Darussalam",
	"BG" => "Bulgaria",
	"BF" => "Burkina Faso",
	"BI" => "Burundi",
	"KH" => "Cambodia",
	"CM" => "Cameroon",
	"CA" => "Canada",
	"CV" => "Cape Verde",
	"KY" => "Cayman Islands",
	"CF" => "Central African Republic",
	"TD" => "Chad",
	"CL" => "Chile",
	"CN" => "China",
	"CX" => "Christmas Island",
	"CC" => "Cocos (Keeling) Islands",
	"CO" => "Colombia",
	"KM" => "Comoros",
	"CG" => "Congo",
	"CD" => "Congo, the Democratic Republic of the",
	"CK" => "Cook Islands",
	"CR" => "Costa Rica",
	"CI" => "Cote d'Ivoire",
	"HR" => "Croatia (Hrvatska)",
	"CU" => "Cuba",
	"CY" => "Cyprus",
	"CZ" => "Czech Republic",
	"DK" => "Denmark",
	"DJ" => "Djibouti",
	"DM" => "Dominica",
	"DO" => "Dominican Republic",
	"TP" => "East Timor",
	"EC" => "Ecuador",
	"EG" => "Egypt",
	"SV" => "El Salvador",
	"GQ" => "Equatorial Guinea",
	"ER" => "Eritrea",
	"EE" => "Estonia",
	"ET" => "Ethiopia",
	"FK" => "Falkland Islands (Malvinas)",
	"FO" => "Faroe Islands",
	"FJ" => "Fiji",
	"FI" => "Finland",
	"FR" => "France",
	"FX" => "France, Metropolitan",
	"GF" => "French Guiana",
	"PF" => "French Polynesia",
	"TF" => "French Southern Territories",
	"GA" => "Gabon",
	"GM" => "Gambia",
	"GE" => "Georgia",
	"DE" => "Germany",
	"GH" => "Ghana",
	"GI" => "Gibraltar",
	"GR" => "Greece",
	"GL" => "Greenland",
	"GD" => "Grenada",
	"GP" => "Guadeloupe",
	"GU" => "Guam",
	"GT" => "Guatemala",
	"GN" => "Guinea",
	"GW" => "Guinea-Bissau",
	"GY" => "Guyana",
	"HT" => "Haiti",
	"HM" => "Heard and Mc Donald Islands",
	"VA" => "Holy See (Vatican City State)",
	"HN" => "Honduras",
	"HK" => "Hong Kong",
	"HU" => "Hungary",
	"IS" => "Iceland",
	"IN" => "India",
	"ID" => "Indonesia",
	"IR" => "Iran (Islamic Republic of)",
	"IQ" => "Iraq",
	"IE" => "Ireland",
	"IL" => "Israel",
	"IT" => "Italy",
	"JM" => "Jamaica",
	"JP" => "Japan",
	"JO" => "Jordan",
	"KZ" => "Kazakhstan",
	"KE" => "Kenya",
	"KI" => "Kiribati",
	"KP" => "Korea, Democratic People's Republic of",
	"KR" => "Korea, Republic of",
	"KW" => "Kuwait",
	"KG" => "Kyrgyzstan",
	"LA" => "Lao People's Democratic Republic",
	"LV" => "Latvia",
	"LB" => "Lebanon",
	"LS" => "Lesotho",
	"LR" => "Liberia",
	"LY" => "Libyan Arab Jamahiriya",
	"LI" => "Liechtenstein",
	"LT" => "Lithuania",
	"LU" => "Luxembourg",
	"MO" => "Macau",
	"MK" => "Macedonia, The Former Yugoslav Republic of",
	"MG" => "Madagascar",
	"MW" => "Malawi",
	"MY" => "Malaysia",
	"MV" => "Maldives",
	"ML" => "Mali",
	"MT" => "Malta",
	"MH" => "Marshall Islands",
	"MQ" => "Martinique",
	"MR" => "Mauritania",
	"MU" => "Mauritius",
	"YT" => "Mayotte",
	"MX" => "Mexico",
	"FM" => "Micronesia, Federated States of",
	"MD" => "Moldova, Republic of",
	"MC" => "Monaco",
	"MN" => "Mongolia",
	"MS" => "Montserrat",
	"MA" => "Morocco",
	"MZ" => "Mozambique",
	"MM" => "Myanmar",
	"NA" => "Namibia",
	"NR" => "Nauru",
	"NP" => "Nepal",
	"NL" => "Netherlands",
	"AN" => "Netherlands Antilles",
	"NC" => "New Caledonia",
	"NZ" => "New Zealand",
	"NI" => "Nicaragua",
	"NE" => "Niger",
	"NG" => "Nigeria",
	"NU" => "Niue",
	"NF" => "Norfolk Island",
	"MP" => "Northern Mariana Islands",
	"NO" => "Norway",
	"OM" => "Oman",
	"PK" => "Pakistan",
	"PW" => "Palau",
	"PA" => "Panama",
	"PG" => "Papua New Guinea",
	"PY" => "Paraguay",
	"PE" => "Peru",
	"PH" => "Philippines",
	"PN" => "Pitcairn",
	"PL" => "Poland",
	"PT" => "Portugal",
	"PR" => "Puerto Rico",
	"QA" => "Qatar",
	"RE" => "Reunion",
	"RO" => "Romania",
	"RU" => "Russian Federation",
	"RW" => "Rwanda",
	"KN" => "Saint Kitts and Nevis", 
	"LC" => "Saint LUCIA",
	"VC" => "Saint Vincent and the Grenadines",
	"WS" => "Samoa",
	"SM" => "San Marino",
	"ST" => "Sao Tome and Principe", 
	"SA" => "Saudi Arabia",
	"SN" => "Senegal",
	"SC" => "Seychelles",
	"SL" => "Sierra Leone",
	"SG" => "Singapore",
	"SK" => "Slovakia (Slovak Republic)",
	"SI" => "Slovenia",
	"SB" => "Solomon Islands",
	"SO" => "Somalia",
	"ZA" => "South Africa",
	"GS" => "South Georgia and the South Sandwich Islands",
	"ES" => "Spain",
	"LK" => "Sri Lanka",
	"SH" => "St. Helena",
	"PM" => "St. Pierre and Miquelon",
	"SD" => "Sudan",
	"SR" => "Suriname",
	"SJ" => "Svalbard and Jan Mayen Islands",
	"SZ" => "Swaziland",
	"SE" => "Sweden",
	"CH" => "Switzerland",
	"SY" => "Syrian Arab Republic",
	"TW" => "Taiwan, Province of China",
	"TJ" => "Tajikistan",
	"TZ" => "Tanzania, United Republic of",
	"TH" => "Thailand",
	"TG" => "Togo",
	"TK" => "Tokelau",
	"TO" => "Tonga",
	"TT" => "Trinidad and Tobago",
	"TN" => "Tunisia",
	"TR" => "Turkey",
	"TM" => "Turkmenistan",
	"TC" => "Turks and Caicos Islands",
	"TV" => "Tuvalu",
	"UG" => "Uganda",
	"UA" => "Ukraine",
	"AE" => "United Arab Emirates",
	"GB" => "United Kingdom",
	"US" => "United States",
	"UM" => "United States Minor Outlying Islands",
	"UY" => "Uruguay",
	"UZ" => "Uzbekistan",
	"VU" => "Vanuatu",
	"VE" => "Venezuela",
	"VN" => "Viet Nam",
	"VG" => "Virgin Islands (British)",
	"VI" => "Virgin Islands (U.S.)",
	"WF" => "Wallis and Futuna Islands",
	"EH" => "Western Sahara",
	"YE" => "Yemen",
	"YU" => "Yugoslavia",
	"ZM" => "Zambia",
	"ZW" => "Zimbabwe"
);

function getStates()
{
	return array(
		"AL" => "Alabama",
		"AK" => "Alaska",
		"AZ" => "Arizona",
		"AR" => "Arkansas",
		"CA" => "California",
		"CO" => "Colorado",
		"CT" => "Connecticut",
		"DE" => "Delaware",
		"DC" => "District of Columbia",
		"FL" => "Florida",
		"GA" => "Georgia",
		"HI" => "Hawaii",
		"ID" => "Idaho",
		"IL" => "Illinois",
		"IN" => "Indiana",
		"IA" => "Iowa",
		"KS" => "Kansas",
		"KY" => "Kentucky",
		"LA" => "Louisiana",
		"ME" => "Maine",
		"MD" => "Maryland",
		"MA" => "Massachusetts",
		"MI" => "Michigan",
		"MN" => "Minnesota",
		"MS" => "Mississippi",
		"MO" => "Missouri",
		"MT" => "Montana",
		"NE" => "Nebraska",
		"NV" => "Nevada",
		"NH" => "New Hampshire",
		"NJ" => "New Jersey",
		"NM" => "New Mexico",
		"NY" => "New York",
		"NC" => "North Carolina",
		"ND" => "North Dakota",
		"OH" => "Ohio",
		"OK" => "Oklahoma",
		"OR" => "Oregon",
		"PA" => "Pennsylvania",
		"RI" => "Rhode Island",
		"SC" => "South Carolina",
		"SD" => "South Dakota",
		"TN" => "Tennessee",
		"TX" => "Texas",
		"UT" => "Utah",
		"VT" => "Vermont",
		"VA" => "Virginia",
		"WA" => "Washington",
		"WV" => "West Virginia",
		"WI" => "Wisconsin",
		"WY" => "Wyoming"
		//"NULL" => $CI->getTxt('International') Moved to Controller.
	);
}
?>

<?php

//This is required to get the international text strings dictionary
require_once 'internationalize.php';

//Author : Rohit Khattar : Include File added to check removal of Setup directory. 

require_once 'setupCheck.php';

//check authority to be here
//require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';
require_once 'fetchMainConfig.php';

if (!isset($_COOKIE['power'])){
//Check to see if the person is an authorized user and display their first name
$sql ="SELECT * FROM moss_users WHERE username='$_POST[username]' AND password= password('$_POST[password]')";

$result = @mysql_query($sql,$connection) or die(mysql_error());

//get the number of rows in the result set
$num = mysql_num_rows($result);
	if ($num != 0) {
		//get the person's first name and authority
		while ($row = mysql_fetch_assoc($result)) {
		$firstname = $row['firstname'];
		$auth = $row['authority'];
		}
		$uname ="$firstname";
	} else {
		header("Location: index.php?state=pass");
		exit;
	}
}

//Count the number of Sites
	$sql_sites ="SELECT * FROM sites";

	$result_sites = @mysql_query($sql_sites,$connection) or die(mysql_error());

	//Get the number of rows in the result set
	$num_sites = mysql_num_rows($result_sites);

//Count the number of Data Points
	$sql_datapts ="SELECT * FROM datavalues";

	$result_datapts = @mysql_query($sql_datapts,$connection) or die(mysql_error());

	//Get the number of rows in the result set
	$num_datapts = mysql_num_rows($result_datapts);
	
//Count the number of Variables
	$sql_vars ="SELECT * FROM varmeth";

	$result_vars = @mysql_query($sql_vars,$connection) or die(mysql_error());

	//Get the number of rows in the result set
	$num_vars = mysql_num_rows($result_vars);
	
//Count the number of Users
	$sql_users ="SELECT * FROM moss_users";

	$result_u = @mysql_query($sql_users,$connection) or die(mysql_error());

	//Get the number of rows in the result set
	$num_u = mysql_num_rows($result_u);

//Check the cookie or set it (based on authority) if not already
// or redirect the user elsewhere if unauthorized

if (isset($_COOKIE['power'])){

	if($_COOKIE['power'] =='admin'){
	//$nav ="<script src=js/A_navbar.js></script>";
	$nav ="<script src=A_navbar.php></script>";
	}
	elseif ($_COOKIE['power'] =='teacher'){
	//$nav ="<script src=js/T_navbar.js></script>";
	$nav ="<script src=T_navbar.php></script>";
	}
	elseif ($_COOKIE['power'] =='student'){
	//$nav ="<script src=js/S_navbar.js></script>";
	$nav ="<script src=S_navbar.php></script>";
	} 
}else{
	if ($auth == "admin"){
	//$nav = "<script src=js/A_navbar.js></script>";
	$nav = "<script src=A_navbar.php></script>";
	$cookie_name = "power";
	$cookie_value = $auth;
	$cookie_expire = time()+14400;
	$cookie_domain = $www;
	setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_domain);
	}

	elseif ($auth == "teacher"){
	//$nav = "<script src=js/T_navbar.js></script>";
	$nav = "<script src=T_navbar.php></script>";
	$cookie_name = "power";
	$cookie_value = $auth;
	$cookie_expire = time()+14400;
	$cookie_domain = $www;
	setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_domain);
	}

	elseif ($auth == "student"){
	//$nav = "<script src=js/S_navbar.js></script>";
	$nav = "<script src=S_navbar.php></script>";
	$cookie_name = "power";
	$cookie_value = $auth;
	$cookie_expire = time()+14400;
	$cookie_domain = $www;
	setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_domain);
	}

	else {
	
	//header("Location: index.php?state=pass2");
	echo("out");
	exit;	
	}	
}

?>

<html>
<head>
<!--<title>HydroServer Lite Web Client</title>-->
<title><?php echo $WebClient; ?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="bookmark" href="favicon.ico" >
<link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC3d042tZnUAA8256hCC2Y6QeTSREaxrY0&sensor=true"></script>    
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
            
            
	<script type="text/javascript">
    //<![CDATA[

//Populate the Javascript Array

var initialLocation;
var siberia = new google.maps.LatLng(60, 105);
var newyork = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
var browserSupportFlag =  new Boolean();
 var option_num=0;
    var map;
    var markers = [];
    var infoWindow;
    var locationSelect;
	var xml="-1";	

   function load() {
 
      map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(44, -160),
        zoom: 12,
        mapTypeId: 'roadmap',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
      });
      infoWindow = new google.maps.InfoWindow();

      locationSelect = document.getElementById("locationSelect");
      locationSelect.onchange = function() {
        var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
        if (markerNum != "none"){
          google.maps.event.trigger(markers[markerNum], 'mouseover');
        }
      };
	  
	  loadall();
   }

function track_loc(){
  // Try W3C Geolocation (Preferred)
  if(navigator.geolocation) {
    browserSupportFlag = true;
    navigator.geolocation.getCurrentPosition(function(position) {
      initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
	   
	    var geocoder = new google.maps.Geocoder();
     geocoder.geocode({location: initialLocation}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
        searchLocationsNear2(results[0].geometry.location);
       } else {
         //alert(address + ' not found');
		 alert(address + <?php echo "'".$NotFound."'";?>);
       }
     });
  
	   
	   
    }, function() {
      handleNoGeolocation(browserSupportFlag);
    });
  }
  // Browser doesn't support Geolocation
   else {
    browserSupportFlag = false;
    handleNoGeolocation(browserSupportFlag);
  }
  
 
  
 // searchLocations2(initialLocation);
}

function loadall()
{
	clearLocations();
	option_num=0;
	 var searchUrl = 'db_display_all.php';
  downloadUrl(searchUrl, function(data) {
  var xml = parseXml(data);
  var markerNodes = xml.documentElement.getElementsByTagName("marker");
  var bounds = new google.maps.LatLngBounds();
   for (var i = 0; i < markerNodes.length; i++) {
	 
    var name = markerNodes[i].getAttribute("name");
    var sitecode = markerNodes[i].getAttribute("sitecode");
	var lat = markerNodes[i].getAttribute("lat");
	var long = markerNodes[i].getAttribute("lng");
    var distance = parseFloat(markerNodes[i].getAttribute("distance"));
    var latlng = new google.maps.LatLng(
        parseFloat(markerNodes[i].getAttribute("lat")),
        parseFloat(markerNodes[i].getAttribute("lng")));
	var type = markerNodes[i].getAttribute("sitetype");
	var siteid = markerNodes[i].getAttribute("siteid");
	    create_source(latlng, name, sitecode, type, lat, long, siteid, i);
    bounds.extend(latlng);
  }

  map.fitBounds(bounds);
  panToBounds(bounds);
	 });
	
	}
	

function create_source(latlng, name, sitecode, type, lat, long, siteid, i)

{
	//To Get The Sources Available on That Site

 var searchUrl_sources = 'db_source_search.php?siteid='+siteid;
	
	downloadUrl(searchUrl_sources, function(data) {
	 var xml5 = parseXml(data);
	 var sourcenodes = xml5.documentElement.getElementsByTagName("source");
	 var sourcename;
    var sourcecode;
	var sourcelink;
	 for (var j = 0; j <sourcenodes.length; j++) 
	 { 
	sourcename = sourcenodes[j].getAttribute("sourcename");
    sourcecode = sourcenodes[j].getAttribute("sourcecode");
	sourcelink = sourcenodes[j].getAttribute("sourcelink");
	 }
	 
	 if (sourcelink==undefined)
	 {sourcelink="";
		 }
	 
  if((sourcename!=undefined)&&(sourcecode!=undefined))
	{
	createMarker(latlng, name, sitecode, type, lat, long, sourcename, sourcecode, sourcelink, siteid);
	createOption(name, i, sourcename); 
	 }
	
	  });
	
	}

   function searchLocations() {
     var address = document.getElementById("addressInput").value;
     var geocoder = new google.maps.Geocoder();
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
        searchLocationsNear(results[0].geometry.location);
       } else {
         //alert(address + ' not found');
		 alert(address + <?php echo "'".$NotFound."'";?>);
       }
     });
   }
   

   function clearLocations() {
     infoWindow.close();
     for (var i = 0; i < markers.length; i++) {
       markers[i].setMap(null);
     }
     markers.length = 0;

     locationSelect.innerHTML = "";
     var option = document.createElement("option");
     option.value = "none";
     //option.innerHTML = "Click here for a list of Sites: ";
     option.innerHTML = <?php echo "'".$ClickHere."'";?>;
     locationSelect.appendChild(option);
   }

   function searchLocationsNear(center) {
     clearLocations(); 
	 option_num=0;

     var radius = document.getElementById('radiusSelect').value;
     var searchUrl = 'db_search.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
     downloadUrl(searchUrl, function(data) {
       var xml2 = parseXml(data);
	   xml=xml2;
       var markerNodes = xml2.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
       
	   if (markerNodes.length==0)
	   //{alert("No Sites Found. Please Alter Search Terms");}
	   {alert(<?php echo "'".$NoSites."'";?>);}
	   for (var i = 0; i < markerNodes.length; i++) {
        var name = markerNodes[i].getAttribute("name");
        var sitecode = markerNodes[i].getAttribute("sitecode");
	   	var lat = markerNodes[i].getAttribute("lat");
		var long = markerNodes[i].getAttribute("lng");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));var type = markerNodes[i].getAttribute("sitetype");
	var siteid = markerNodes[i].getAttribute("siteid");
	    create_source(latlng, name, sitecode, type, lat, long, siteid, i);
    bounds.extend(latlng);
       }
       map.fitBounds(bounds);
       locationSelect.style.visibility = "visible";
       locationSelect.onchange = function() {
         var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
         google.maps.event.trigger(markers[markerNum], 'mouseover');
       };
      });
    }
  

 function searchLocationsNear2(center) {
     clearLocations();
	 option_num=0; 
    var radius = 300; //Radius for tracking
     var searchUrl = 'db_search.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
     downloadUrl(searchUrl, function(data) {
       var xml2 = parseXml(data);
       var markerNodes = xml2.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
       for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("name");
         var sitecode = markerNodes[i].getAttribute("sitecode");
var lat = markerNodes[i].getAttribute("lat");
var long = markerNodes[i].getAttribute("lng");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));var type = markerNodes[i].getAttribute("sitetype");
	var siteid = markerNodes[i].getAttribute("siteid");
	    create_source(latlng, name, sitecode, type, lat, long, siteid, i);
    bounds.extend(latlng);
       }
       map.fitBounds(bounds);
       locationSelect.style.visibility = "visible";
       locationSelect.onchange = function() {
         var markerNum = locationSelect.options[locationSelect.selectedIndex].value;

         google.maps.event.trigger(markers[markerNum], 'mouseover');
       };
      });
    }
  
   function createMarker(latlng, name, sitecode, type, lat, long, sourcename, sourcecode, sourcelink, siteid) 
   {
	   
//send out an ajax request to retrieve the picname

$.ajax({
  type: "POST",
  url: "getsitepic.php?sc="+siteid
}).done(function( msg ) {
  if(msg!=-1)
  {

 //var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>Site type: "+type+"<br/>Latitude: "+lat+"<br/>Longitude: "+long+"<br/>Source: <a href='"+sourcelink+"' target='_blank'>"+sourcename+"</a><br/><a href='details.php?siteid="+siteid+"'>Click here to visit the site</a></div><div id='spic' style='margin-left:5px;height:100px;width:100px;float:left;'>"+msg+"</div>";
 var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>"+ <?php echo "'".$SiteType."'";?> +type+"<br/> "+<?php echo "'".$Latitude."'";?> +lat+"<br/>"+  <?php echo "'".$Longitude."'";?>  +long+"<br/>"+ <?php echo "'".$Source."'";?> + "<a href='"+sourcelink+"' target='_blank'>"+sourcename+"</a><br/><a href='details.php?siteid="+siteid+"'>" + <?php echo "'".$VisitSite."'";?> + "</a></div><div id='spic' style='margin-left:5px;height:100px;width:100px;float:left;'>"+msg+"</div>";

 var marker = new google.maps.Marker({
    map: map,
    position: latlng
  });
  google.maps.event.addListener(marker, 'mouseover', function() {
    infoWindow.setContent(html);
    infoWindow.open(map, marker);
  });
  markers.push(marker);

}
else
{

// var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>Site type: "+type+"<br/>Latitude: "+lat+"<br/>Longitude: "+long+"<br/>Source: <a href='"+sourcelink+"' target='_blank'>"+sourcename+"</a><br/><a href='details.php?siteid="+siteid+"'>Click here to visit the site</a></div>";
  var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>" + <?php echo "'".$SiteType."'";?> +type+"<br/>" + <?php echo "'".$Latitude."'";?> +lat+"<br/>"+ <?php echo "'".$Longitude."'";?> +long+"<br/>" + <?php echo "'".$Source."'";?> +"<a href='"+sourcelink+"' target='_blank'>"+sourcename+"</a><br/><a href='details.php?siteid="+siteid+"'>"+ <?php echo "'".$VisitSite."'";?> +"</a></div>";


 var marker = new google.maps.Marker({
    map: map,
    position: latlng
  });
  google.maps.event.addListener(marker, 'mouseover', function() {
    infoWindow.setContent(html);
    infoWindow.open(map, marker);
  });
  markers.push(marker);

}
});


	   

  
 
}


    function createOption(name, num, sourcename) {
		
      var option = document.createElement("option");
      option.value = option_num;
      option.innerHTML = name + " (Source : " + sourcename + ")";
      locationSelect.appendChild(option);
   option_num=option_num+1;
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request.responseText, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}

    //]]>
  </script>

</head>

<body background="images/bkgrdimage.jpg" onLoad="load()">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="images/WebClientBanner.png" width="960" height="200" alt="logo" /></td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php include 'header.php'; ?></td>
  </tr>
  <tr>
    <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
    <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br />
    <!--<h2>Welcome <?php echo "$uname"; ?> to the <?php echo "$orgname"; ?> Data Portal!</h2>-->
    <h2><?php echo $Welcome; ?> <?php echo "$uname"; ?> <?php echo $ToThe;?> <?php echo "$orgname"; ?> <?php echo $DataPortal; ?></h2>
    <p><strong><!--This system is running HydroServer Lite--> <?php echo $ThisSystemRuns;?> <?php echo "$HSLversion"; ?> <!--and your database contains--> <?php echo $DatabaseContains; ?> <?php echo "$num_sites"; ?> <!--Sites,--><?php echo $Sites; ?> <?php echo "$num_datapts"; ?> <!--data points,--><?php echo $DataPoints;?> <?php echo "$num_vars"; ?> <!--Variables, and--><?php echo $Variables; ?> <?php echo "$num_u"; ?> <!--users.--><?php echo $users; ?></strong></p>
      <table width="630" border="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="630" height="450"><div id="map" style="width:100%; height:100%"></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <!--<td><p>To search for a data collection sites, simply type in the city or hit the button &quot;Find sites near me!&quot; to show sites within a 300 mile radius of your present geographic location. (Note: Sites in which there is no data will NOT be displayed below.)</p></td>-->
          <td><p><?php echo $SearchDataHome; ?></p></td>

        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input type="text" id="addressInput" size="10"/>
            <select name="radiusSelect" id="radiusSelect">
              <option value="25" selected><?php echo $TwentyFive; ?></option>
              <option value="50"><?php echo $Fifty; ?></option>
              <option value="100"><?php echo $OneHundred; ?></option>
              <option value="200"><?php echo $TwoHundred; ?></option>
              <option value="300"><?php echo $ThreeHundred; ?></option>
              <option value="400"><?php echo $FourHundred; ?></option>
              <option value="500"><?php echo $FiveHundred; ?></option>
              <!--<option value="25" selected>25mi</option>
              <option value="50">50mi</option>
              <option value="100">100mi</option>
              <option value="200">200mi</option>
              <option value="300">300mi</option>
              <option value="400">400mi</option>
              <option value="500">500mi</option>-->
            </select>
            <!--<input type="button" onClick="searchLocations()" value="Search"/>
            <input type='button' onClick="loadall()" value="Reset Search"/>
            <input type='button' onClick="track_loc()" value="Find Sites Near Me!"/></td>-->
            <input type="button" onClick="searchLocations()" value="<?php echo $Search; ?>"/>
            <input type='button' onClick="loadall()" value="<?php echo $ResetSearch; ?>"/>
            <input type='button' onClick="track_loc()" value="<?php echo $FindSites; ?>"/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div>
            <select name="locationSelect" id="locationSelect" style="width:100%;visibility:hidden">
            </select>
          </div></td>
        </tr>
      </table>
    </blockquote>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <script src="js/footer.js"></script>
  </tr>
</table>
</body>
</html>
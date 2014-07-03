<?php
//This is required to get the international text strings dictionary
require_once 'internationalize.php';
?>

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

   function loadmap() {
 
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
		 alert(address + <?php echo "'".$NotFound."'"; ?>);
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
         alert(address + <?php echo "'".$NotFound."'"; ?>);
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
	 option.innerHTML = <?php echo "'".$ClickForSiteList."'"; ?>;
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
	   {alert("No Sites Found. Please Alter Search Terms");}
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
 //var html = "<b>" + name + "</b> <br/>Site type: "+type+"<br/>Latitude: "+lat+"<br/>Longitude: "+long+"<br/>Source: <a href='"+sourcelink+"' target='_blank'>"+sourcename+"</a><br/><a href='javascript:browsesite("+siteid+");'>Click here to compare data from this site</a></div>";
 var html = "<b>" + name + "</b> <br/>" +<?php echo "'".$SiteType."'";?> +type+"<br/>"+<?php echo "'".$Latitude."'";?> +lat+"<br/>"+<?php echo "'".$Longitude."'";?> +long+"<br/>"+<?php echo "'".$Source."'";?>+ "<a href='"+sourcelink+"' target='_blank'>"+sourcename+"</a><br/><a href='javascript:browsesite("+siteid+");'>"+<?php echo "'".$CompareData."'";?>+"</a></div>";
 
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


    function createOption(name, num, sourcename) {
		
      var option = document.createElement("option");
      option.value = option_num;
      //option.innerHTML = name + " (Source : " + sourcename + ")";
	  option.innerHTML = name +" ("+<?php echo "'".$Source."'"; ?>+" "+ sourcename + ")";
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
	
function browsesite(id)
{
$('#window').jqxWindow('hide');
$('#window2').jqxWindow('show');
$('#window2Content').load('compare_2.php?sid='+id, function() {
$('#siteidc').val(id);
loadsitecomp();
});
//Get Content for the site page into the window2

}
	
  </script>

<!--Please Select a site from the below map or click same site to show options from the same site.-->
<?php echo $SelectSiteBelow; ?>

 <table width="630" border="0">
           <tr>
             <!--<td>Please enter a location to search data collection sites nearby or hit the button "Find sites near me!" to show sites within a 300 mile radius of your present geographic location. (Note: Sites in which there is no data will NOT be displayed below.)</td>-->
			 <td><?php echo $EnterSearchLocation; ?></td>
		   </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
         
           <tr>
             <td width="500" height="300"><div id="map" style="width:100%; height:100%"></div></td>
           </tr>
            <tr>
             <td>&nbsp;</td>
           </tr>
            <tr>
             <td><input type="text" id="addressInput" size="10"/>
               <select name="radiusSelect" id="radiusSelect">
                 <!--<option value="25" selected>25mi</option>
                 <option value="50">50mi</option>
                 <option value="100">100mi</option>
                 <option value="200">200mi</option>
                 <option value="300">300mi</option>
                 <option value="400">400mi</option>
                 <option value="500">500mi</option>-->
				 <option value="25" selected><?php echo $TwentyFive; ?></option>
                 <option value="50"><?php echo $Fifty; ?></option>
                 <option value="100"><?php echo $OneHundred; ?></option>
                 <option value="200"><?php echo $TwoHundred; ?></option>
                 <option value="300"><?php echo $ThreeHundred ?></option>
                 <option value="400"><?php echo $FourHundred; ?></option>
                 <option value="500"><?php echo $FiveHundred; ?></option>
               </select>
               <!--<input type="button" onClick="searchLocations()" value="Search"/>
               <input type='button' onClick="loadall()" value="Reset Search"/>
             <input type='button' onClick="track_loc()" value="Find sites near me!"/>
             <input type='button' onClick="browsesite(siteid)" value="Same Site"/>-->
			 <input type="button" onClick="searchLocations()" value="<?php echo $Search; ?>"/>
               <input type='button' onClick="loadall()" value="<?php echo $ResetSearch; ?>"/>
             <input type='button' onClick="track_loc()" value="<?php echo $FindSites; ?>"/>
             <input type='button' onClick="browsesite(siteid)" value="<?php echo $SameSite; ?>"/>
             </td>
           </tr>
           
           <tr>
             <td>&nbsp;</td>
           </tr>
             <tr>
             <td><div><select name="locationSelect" id="locationSelect" style="width:100%;visibility:hidden"></select></div></td>
           </tr>
           
           
         </table>


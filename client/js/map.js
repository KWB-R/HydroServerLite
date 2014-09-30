
//Populate the Javascript Array

var initialLocation;
var siberia = new google.maps.LatLng(60, 105);
var newyork = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
var browserSupportFlag = new Boolean();
var option_num = 0;
var map;
var markers = [];
var infoWindow;
var locationSelect;
var xml = "-1";
var markerCluster;

function load() {

    var mp = document.getElementById("map");
	
	//Attaching an event handler for resizing of div
    if (mp != null) {
        map = new google.maps.Map(mp, { //document.getElementById("map"), {
            center: new google.maps.LatLng(44, -160),
            zoom: 12,
            mapTypeId: 'roadmap',
            mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU }
        });
        infoWindow = new google.maps.InfoWindow();

        locationSelect = document.getElementById("locationSelect");
        locationSelect.onchange = function () {
            var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
            if (markerNum != "none") {
                google.maps.event.trigger(markers[markerNum], 'mouseover');
            }
        };
      updateHeights();
        loadall();
		markerCluster = new MarkerClusterer(map);
	}
	
	$("#map").resize(function() {
		google.maps.event.trigger(map, 'resize');
		
	});
	
}
// Update the height of the map Container to make sure it will fit inside of the window
//   in which it is displayed. This is mostly for when it is used inside an iFrame on
//   another site.
function updateHeights() {
    var winHeight = $(window).height();
    var totalHeight = $("#mapOuter").height();
    var filterHeight = $("#mapFilters").height();
    var locationHeight = $("#mapLocations").height();
    var newContHeight = totalHeight - filterHeight - locationHeight;
    $("#mapContainer").height(newContHeight);

}

function track_loc() {
    // Try W3C Geolocation (Preferred)
    if (navigator.geolocation) {
        browserSupportFlag = true;
        navigator.geolocation.getCurrentPosition(function (position) {
            initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: initialLocation }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    searchLocationsNear2(results[0].geometry.location);
                } else {
                    alert(address + ' not found');
                }
            });



        }, function () {
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

function loadall() {
    clearLocations();
    option_num = 0;
	var markerCount=0;
    var searchUrl = 'db_display_all.php';
    downloadUrl(searchUrl, function (data) {
        var xml = parseXml(data);
        var markerNodes = new Array();
        if (xml.documentElement != null) {
            markerNodes = xml.documentElement.getElementsByTagName("marker");
        } else {
            alert("Trouble accessing the data store. Please contact an Administrator!");
        }
        var bounds = new google.maps.LatLngBounds();
		markerCount=markerNodes.length;
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
				var sourcename = markerNodes[i].getAttribute("sourcename");
                var sourcecode = markerNodes[i].getAttribute("sourcecode");
				var sourcelink = markerNodes[i].getAttribute("sourcelink");
				var sitepic = markerNodes[i].getAttribute("sitepic");
				//Changed the SQL query to avoid multiple calls for each site and its source details Rohit 8/5/2014
                create_source(latlng, name, sitecode, type, lat, long, siteid, i,sourcename,sourcecode,sourcelink,sitepic);
                bounds.extend(latlng);
            }

            if (markerNodes.length == 1) {
                var center = bounds.getCenter();
                var latlng1 = new google.maps.LatLng(center.lat + 0.001, center.lon + 0.001);
                var latlng2 = new google.maps.LatLng(center.lat - 0.001, center.lon - 0.001);
                //bounds.extend(latlng1);
                //bounds.extend(latlng2);
                map.setZoom(10);
            }
		
	  
        map.fitBounds(bounds);
        map.panToBounds(bounds);
        //map.setZoom(15);
    });
return markerCount;
}


function create_source(latlng, name, sitecode, type, lat, long, siteid, i, sourcename, sourcecode, sourcelink,sitepic) {
    //To Get The Sources Available on That Site
	//No longer sending individual requests. This is just a intermediate step now. No actual processing done here. 
	createMarker(latlng, name, sitecode, type, lat, long, sourcename, sourcecode, sourcelink, siteid,sitepic);
    createOption(name, i, sourcename);

}

function searchLocations() {
    var address = document.getElementById("addressInput").value;
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: address }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            searchLocationsNear(results[0].geometry.location);
        } else {
            alert(address + ' not found');
        }
    });
}


function clearLocations() {
    infoWindow.close();
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers.length = 0;
	markers = [];
	if(markerCluster != undefined)
	{
	markerCluster.clearMarkers();
    }locationSelect.innerHTML = "";
    var option = document.createElement("option");
    option.value = "none";
    option.innerHTML = "Click here for a list of Sites: ";
    locationSelect.appendChild(option);
}

function searchLocationsNear(center) {
    clearLocations();
    option_num = 0;

    var radius = document.getElementById('radiusSelect').value;
    var searchUrl = 'db_search.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
    downloadUrl(searchUrl, function (data) {
        var xml2 = parseXml(data);
        xml = xml2;
        var markerNodes = xml2.documentElement.getElementsByTagName("marker");
        var bounds = new google.maps.LatLngBounds();

        if (markerNodes.length == 0)
        { alert("No Sites Found. Please Alter Search Terms"); }
        for (var i = 0; i < markerNodes.length; i++) {
            var name = markerNodes[i].getAttribute("name");
            var sitecode = markerNodes[i].getAttribute("sitecode");
            var lat = markerNodes[i].getAttribute("lat");
            var long = markerNodes[i].getAttribute("lng");
            var distance = parseFloat(markerNodes[i].getAttribute("distance"));
            var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng"))); var type = markerNodes[i].getAttribute("sitetype");
            var siteid = markerNodes[i].getAttribute("siteid");
            create_source(latlng, name, sitecode, type, lat, long, siteid, i);
            bounds.extend(latlng);
        }
        map.fitBounds(bounds);
        locationSelect.style.visibility = "visible";
        locationSelect.onchange = function () {
            var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
            google.maps.event.trigger(markers[markerNum], 'mouseover');
        };
    });
}


function searchLocationsNear2(center) {
    clearLocations();
    option_num = 0;
    var radius = 300; //Radius for tracking
    var searchUrl = 'db_search.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
    downloadUrl(searchUrl, function (data) {
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
              parseFloat(markerNodes[i].getAttribute("lng"))); var type = markerNodes[i].getAttribute("sitetype");
            var siteid = markerNodes[i].getAttribute("siteid");
            create_source(latlng, name, sitecode, type, lat, long, siteid, i);
            bounds.extend(latlng);
        }
        map.fitBounds(bounds);
        locationSelect.style.visibility = "visible";
        locationSelect.onchange = function () {
            var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
            google.maps.event.trigger(markers[markerNum], 'mouseover');
        };
    });
}

function createMarker(latlng, name, sitecode, type, lat, long, sourcename, sourcecode, sourcelink, siteid,sitepic) {
	
	//Sending out a request each time we want to get site picture is super redundant and will slow down the process considerably. 
	//Figured out a join so that the image is going to be provided by the first request only. 
	if(sitepic!="")
	{
		var image = "<img src='imagesite/small/"+sitepic +"' width='100' height='100'>";	
        var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>Site Type: " + type + "<br/>Latitude: " + lat + "<br/>Longitude: " + long + "<br/>Source: <a href='" + sourcelink + "' target='_blank'>" + sourcename + "</a><br/><a target='_blank' href='details.php?siteid=" + siteid + "'>Click here for site details and data</a></div><div id='spic' style='margin-left:5px;height:100px;width:100px;float:left;'>" + image + "</div>";
	
	}
	else
	{
		var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>Site Type: " + type + "<br/>Latitude: " + lat + "<br/>Longitude: " + long + "<br/>Source: <a href='" + sourcelink + "' target='_blank'>" + sourcename + "</a><br/><a target='_blank' href='details.php?siteid=" + siteid + "'>Click here for site details and data</a></div>";	
	}
   
	var marker = new google.maps.Marker({position: latlng});
	google.maps.event.addListener(marker, 'mouseover', function () {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);});
	markerCluster.addMarker(marker);
	markers.push(marker);
}


function createOption(name, num, sourcename) {

    var option = document.createElement("option");
    option.value = option_num;
    option.innerHTML = name + " (Source : " + sourcename + ")";
    locationSelect.appendChild(option);
    option_num = option_num + 1;
}

function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

    request.onreadystatechange = function () {
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

function doNothing() { }

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
var flag = 0;

function load() {

    var mp = document.getElementById("map");
    if (mp != null) {
        map = new google.maps.Map(mp, { //document.getElementById("map"), {
            center: new google.maps.LatLng(40.249, -111.649),
            zoom: 12,
            mapTypeId: 'roadmap',
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            }
        });
        infoWindow = new google.maps.InfoWindow();

        locationSelect = document.getElementById("locationSelect");
        locationSelect.onchange = function() {
            var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
            if (markerNum != "none") {
                google.maps.event.trigger(markers[markerNum], 'mouseover');
            }
        };
        updateHeights();
        loadall();
        markerCluster = new MarkerClusterer(map);
    }
	
	google.maps.event.addListener(map, 'zoom_changed', function() {
    zoomLevel = map.getZoom();
	if(zoomLevel>=19)
	{
	if(flag==0)
		//disable clustering
	{		markerCluster.clearMarkers();
		for (var i = 0; i < markers.length; i++) { 
          markers[i].setOptions({map: map, visible:true});
        }
		flag=1;}
	}
	else
	{
		if(flag==1)
		{
			//enable clustering
			flag=0;
			/*  for (var i = 0; i < markers.length; i++) { 
          markers[i].setOptions({ map:null, visible: false});
        }*/
        markerCluster = new MarkerClusterer(map,markers);
		}
	}
	
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
        navigator.geolocation.getCurrentPosition(function(position) {
            initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                location: initialLocation
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    searchLocationsNear2(results[0].geometry.location);
                } else {
                    alert(address + ' not found');
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

function loadall() {
    clearLocations();
    option_num = 0;
    var markerCount = 0;
    var searchUrl = base_url+"sites/displayAll";
    if (document.getElementById("allSitesCheck").checked) {
        searchUrl = base_url+"sites/displayAll/1";
    }
    downloadUrl(searchUrl, function(data) {
        var xml = parseXml(data);
        var markerNodes = new Array();
        if (xml.documentElement != null) {
            markerNodes = xml.documentElement.getElementsByTagName("marker");
        } else {
            alert("Trouble accessing the data store. Please contact an Administrator!");
        }
        var bounds = new google.maps.LatLngBounds();
        markerCount = markerNodes.length;
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
            create_source(latlng, name, sitecode, type, lat, long, siteid, i, sourcename, sourcecode, sourcelink, sitepic);
            bounds.extend(latlng);
        }
		
		//This sets the map center and zoom when there are two or more markers
		if (markerCount > 1) {
			map.fitBounds(bounds);
			map.panToBounds(bounds);
		}
	});
		
	//This sets the map center and zoom when there is only one marker present
	if (markerCount == 1) {
		var latlng = new google.maps.LatLng(
			parseFloat(markerNodes[0].getAttribute("lat")),
			parseFloat(markerNodes[0].getAttribute("lng")));
		map.setCenter(latlng, 1.5);
	}
	//This sets the map center when there are no makers present	(Jeremy Fowler)
	if (markerCount == 0) {
		map.setCenter(new google.maps.LatLng(40.249, -111.649), 1.5);
	}
    
    return markerCount;
}


function create_source(latlng, name, sitecode, type, lat, long, siteid, i, sourcename, sourcecode, sourcelink, sitepic) {
    //To Get The Sources Available on That Site
    //No longer sending individual requests. This is just a intermediate step now. No actual processing done here. 
    createMarker(latlng, name, sitecode, type, lat, long, sourcename, sourcecode, sourcelink, siteid, sitepic);
    createOption(name, i, sourcename);

}

function searchLocations() {
    var address = document.getElementById("addressInput").value;
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        address: address
    }, function(results, status) {
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
    if (markerCluster != undefined) {
        markerCluster.clearMarkers();
    }
    locationSelect.innerHTML = "";
    var option = document.createElement("option");
    option.value = "none";
    option.innerHTML = "Click here for a list of Sites: ";
    locationSelect.appendChild(option);
}

function searchLocationsNear(center) {
    clearLocations();
    option_num = 0;

    var radius = document.getElementById('radiusSelect').value;
    var searchUrl = base_url+'sites/siteSearch?lat=' + center.lat() + '&long=' + center.lng() + '&radius=' + radius;
    downloadUrl(searchUrl, function(data) {
        var xml2 = parseXml(data);
        xml = xml2;
        var markerNodes = xml2.documentElement.getElementsByTagName("marker");
        var bounds = new google.maps.LatLngBounds();

        if (markerNodes.length == 0) {
            alert("No Sites Found. Please Alter Search Terms");
        }
        for (var i = 0; i < markerNodes.length; i++) {
            var name = markerNodes[i].getAttribute("name");
            var sitecode = markerNodes[i].getAttribute("sitecode");
            var lat = markerNodes[i].getAttribute("lat");
            var long = markerNodes[i].getAttribute("lng");
            var latlng = new google.maps.LatLng(
                parseFloat(markerNodes[i].getAttribute("lat")),
                parseFloat(markerNodes[i].getAttribute("lng")));
            var type = markerNodes[i].getAttribute("sitetype");
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
    option_num = 0;
    var radius = 300; //Radius for tracking
    var searchUrl = base_url+'sites/siteSearch?lat=' + center.lat() + '&long=' + center.lng() + '&radius=' + radius;
    downloadUrl(searchUrl, function(data) {
        var xml2 = parseXml(data);
        var markerNodes = xml2.documentElement.getElementsByTagName("marker");
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markerNodes.length; i++) {
            var name = markerNodes[i].getAttribute("name");
            var sitecode = markerNodes[i].getAttribute("sitecode");
            var lat = markerNodes[i].getAttribute("lat");
            var long = markerNodes[i].getAttribute("lng");
            var latlng = new google.maps.LatLng(
                parseFloat(markerNodes[i].getAttribute("lat")),
                parseFloat(markerNodes[i].getAttribute("lng")));
            var type = markerNodes[i].getAttribute("sitetype");
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

function createMarker(latlng, name, sitecode, type, lat, long, sourcename, sourcecode, sourcelink, siteid, sitepic) {

    //Sending out a request each time we want to get site picture is super redundant and will slow down the process considerably. 
    //Figured out a join so that the image is going to be provided by the first request only. 
    if (sitepic != "") {
	var imgurl = base_url.replace("index.php","assets/images/imagesite/small");
        var image = "<img src='" +imgurl + sitepic + "' width='100' height='100'>";
        var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>Site Type: " + type + "<br/>Latitude: " + lat + "<br/>Longitude: " + long + "<br/>Source: <a href='" + sourcelink + "' target='_blank'>" + sourcename + "</a><br/><a href='"+base_url+"sites/details/" + siteid + "'>Click here for site details and data</a></div><div id='spic' style='margin-left:5px;height:100px;width:100px;float:left;'>" + image + "</div>";

    } else {
        var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>Site Type: " + type + "<br/>Latitude: " + lat + "<br/>Longitude: " + long + "<br/>Source: <a href='" + sourcelink + "' target='_blank'>" + sourcename + "</a><br/><a href='"+base_url+"sites/details/" + siteid + "'>Click here for site details and data</a></div>";
    }
	
	var allMarkers = markers;
	var finalLatLng = latlng;
	
	if (allMarkers.length != 0) {
    for (i=0; i < allMarkers.length; i++) {
        var existingMarker = allMarkers[i];
        var pos = existingMarker.getPosition();

        //if a marker already exists in the same position as this marker
        if (latlng.equals(pos)) {
            //update the position of the coincident marker by applying a small multipler to its coordinates
            var newLat = latlng.lat() + (Math.random() -.5) / 2000;// * (Math.random() * (max - min) + min);
            var newLng = latlng.lng() + (Math.random() -.5) / 2000;// * (Math.random() * (max - min) + min);
            finalLatLng = new google.maps.LatLng(newLat,newLng);
        }
    }
}
    var marker = new google.maps.Marker({
        position: finalLatLng
    });
    google.maps.event.addListener(marker, 'mouseover', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
    });
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
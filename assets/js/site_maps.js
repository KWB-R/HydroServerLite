var map;
var marker = null;
var elevator;

function initialize() {
    GetSourceName();
    
    var myLatlng = new google.maps.LatLng(43.52764, -112.04951);

    var myOptions = {
        zoom: 6,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDoubleClickZoom: true
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    elevator = new google.maps.ElevationService();
    google.maps.event.addListener(map, 'dblclick', function (event) {
    placeMarker(event.latLng);
    });
}

if (navigator.geolocation) {
    browserSupportFlag = true;
    navigator.geolocation.getCurrentPosition(function (position) {
        initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: initialLocation },
        function (results, status) {
            if (typeof map != "undefined" && map != null) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                } else {
                    alert(address + ' not found');
                }
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



function placeMarker(location) {


    if (marker == null) {
        marker = new google.maps.Marker({
            position: location,
            map: map,
            draggable: true
        });

        google.maps.event.addListener(marker, 'dragend', function (event) {

            //Again Update the Latitude longitude values
            update(event.latLng)
            //    placeMarker(event.latLng);
        });


        //Update values in the 
        update(location)

    }
    else {
        marker.setPosition(location);
        //Update Values into the form	
        update(location)

    }
    map.setCenter(location);
}

function update(location) {


    $("#Latitude").val(parseFloat(location.lat()).toFixed(5));
    $("#Longitude").val(parseFloat(location.lng()).toFixed(5));

    //Update Elevation




    var locations = [];
    locations.push(location);

    // Create a LocationElevationRequest object using the array's one value
    var positionalRequest = {
        'locations': locations
    }

    // Initiate the location request
    elevator.getElevationForLocations(positionalRequest, function (results, status) {
        if (status == google.maps.ElevationStatus.OK) {

            // Retrieve the first result
            if (results[0]) {

                // Open an info window indicating the elevation at the clicked position
                $("#Elevation").val(parseFloat(results[0].elevation).toFixed(1));


            } else {
                alert("No results found");
            }
        } else {
            alert("Elevation service failed due to: " + status);
        }
    });



    // Now to update the state
    var latlng1 = new google.maps.LatLng(location.lat(), location.lng());
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng1 }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                $("#locationtext").html("Closest known location: " + results[0].formatted_address);
				var str = results[0].formatted_address;
				var usa = str.search("USA");
				if(usa == -1) {
							$("#state").val("NULL");
							$("#countyWrapper").hide();
							} else	{		
							var auto = JSON.stringify(results[0]);
							console.log(auto);
							for (var i=0; i<results[0].address_components.length; i++)
									{
										if (results[0].address_components[i].types[0] == "administrative_area_level_2") {
												county = results[0].address_components[i];
											}
										if (results[0].address_components[i].types[0] == "administrative_area_level_1") {
												state = results[0].address_components[i];
											}
									}					
							$("#state").val(state.short_name);
							new_drop_down_list(county.long_name);
							$("#countyWrapper").show();
							$("#county").val(county.long_name);
							}
							
            }
        } else {
            alert("Geocoder failed due to: " + status);
        }
    });

}
function new_drop_down_list(value){
    var state = $('#state').val();

    if(state == 'AK' || state == 'DC' || state == 'NULL'){ // Alaska and District Columbia have no counties
		//$('#county_original').hide();
    	$('#loading_county_drop_down').hide(); // Hide the Loading...
	    $('#no_county_drop_down').show(); // Show the "no counties" message (if it's the case)
    }else{
		$('#county_original').hide(); // Hide the original drop down
		$('#county_drop_down').show(); // Show the drop down
		$('#no_county_drop_down').hide();
		var jsURL = asset_url+"js/";
    	$.getScript(jsURL+"states/"+ state.toLowerCase() +".js", function(){

	  		populate($("#county")[0]);
			$("#county").val(value);
			$('#loading_county_drop_down').hide();
			$('#county_drop_down').show(); // Show the drop down
    	});
	}
}


//Function to run on form submission to implement a validation and then run an ajax request to post the data to the server and display the message that the site has been added successfully

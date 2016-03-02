<div class='col-md-12'>
<?php echo getTxt('EnterSearchLocation')?>
<div class="container-fluid" id="mapOuter" style="width:100%; height:450px;">
	<div class="container-fluid" id="mapContainer" style="width:100%; height:auto;">
		<div id="map" style="width:100%; height:100%;"> </div>
	</div>
	<div>
	<div id="mapFilters">
		<input type="text" id="addressInput" class="short"/>
		<select name="radiusSelect" id="radiusSelect">
		  <option value="25" selected><?php echo getTxt('TwentyFive'); ?></option>
              <option value="50"><?php echo getTxt('Fifty'); ?></option>
              <option value="100"><?php echo getTxt('OneHundred'); ?></option>
              <option value="200"><?php echo getTxt('TwoHundred'); ?></option>
              <option value="300"><?php echo getTxt('ThreeHundred'); ?></option>
              <option value="400"><?php echo getTxt('FourHundred'); ?></option>
              <option value="500"><?php echo getTxt('FiveHundred'); ?></option>
		</select>
		<input type="button" onClick="searchLocations()" value="<?php echo getTxt('Search'); ?>"/>
        <input type='button' onClick="loadall()" value="<?php echo getTxt('ResetSearch'); ?>"/>
        <input type='button' onClick="track_loc()" value="<?php echo getTxt('FindSites'); ?>"/>
		<input type="checkbox" id="allSitesCheck" onClick="loadall()" value="allSites"><?php echo getTxt('AllSites'); ?></input>
		<p class="instruction">
		<?php echo getTxt('EnterSearchLocation') ?>
		</p>
		<div id="mapLocations" >
		<select name="locationSelect" id="locationSelect" style="width:100%;"></select>
	</div>
	</div>		
		</div>
	</div>
</div>
<script>
function browsesite(id)
{
console.log("Window 1 just opened.");
$('#window').jqxWindow('hide');
$('#window2').jqxWindow('show');
$('#window2Content').load(base_url+'datapoint/compare/2?siteid='+id, function() {
$('#siteidc').val(id);
loadsitecomp();
});
//Get Content for the site page into the window2

}
//Overwrite the marker function
createMarker = function(latlng, name, sitecode, type, lat, long, sourcename, sourcecode, sourcelink, siteid, sitepic) {

    //Sending out a request each time we want to get site picture is super redundant and will slow down the process considerably. 
    //Figured out a join so that the image is going to be provided by the first request only. 
    if (sitepic != "") {
		var imgurl = base_url.replace("index.php","uploads");
         var image = "<img src='" +imgurl + sitepic + "' width='100' height='100'>";
        var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>Site Type: " + type + "<br/>Latitude: " + lat + "<br/>Longitude: " + long + "<br/>Source: <a href='" + sourcelink + "' target='_blank'>" + sourcename + "</a><br/><a href='javascript:browsesite("+siteid+");'>Click here for site details and data</a></div><div id='spic' style='margin-left:5px;height:100px;width:100px;float:left;'>" + image + "</div>";

    } else {
        var html = "<div id='menu12' style='float:left;'><b>" + name + "</b> <br/>Site Type: " + type + "<br/>Latitude: " + lat + "<br/>Longitude: " + long + "<br/>Source: <a href='" + sourcelink + "' target='_blank'>" + sourcename + "</a><br/><a href='javascript:browsesite("+siteid+");'>Click here for site details and data</a></div>";
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
load();	
</script>

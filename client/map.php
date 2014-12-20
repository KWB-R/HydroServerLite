<?php
	//This is required to get the international text strings dictionary
	
	require_once 'internationalize.php';
	require_once "_html_parts.php";
	//<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC3d042tZnUAA8256hCC2Y6QeTSREaxrY0&sensor=true"></script>    
	//<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	//<script src="js/map.js" type="text/javascript"></script>
	//<script src="js/markerclusterer.js" type="text/javascript"></script>
	echo $JS_JQuery;
echo $JS_Maps;
?>
<div id="mapOuter" style="width:100%; height:500px;">
	<div id="mapContainer" style="width:100%;">
		<div id="map" style="width:100%; height:100%;"> </div>
	</div>
	<div>
	<div id="mapFilters">
		<input type="text" id="addressInput" class="short"/>
		<select name="radiusSelect" id="radiusSelect">
		  <option value="25" selected><?php echo $TwentyFive; ?></option>
              <option value="50"><?php echo $Fifty; ?></option>
              <option value="100"><?php echo $OneHundred; ?></option>
              <option value="200"><?php echo $TwoHundred; ?></option>
              <option value="300"><?php echo $ThreeHundred; ?></option>
              <option value="400"><?php echo $FourHundred; ?></option>
              <option value="500"><?php echo $FiveHundred; ?></option>
		</select>
		<input type="button" onClick="searchLocations()" value="<?php echo $Search; ?>"/>
        <input type='button' onClick="loadall()" value="<?php echo $ResetSearch; ?>"/>
        <input type='button' onClick="track_loc()" value="<?php echo $FindSites; ?>"/>
		<input type="checkbox" id="allSitesCheck" onClick="loadall()" value="allSites"><?php echo $AllSites; ?></input>
		<p class="instruction">
		<?php echo $SearchDataInst ?>
		</p>
		<div id="mapLocations" >
		<select name="locationSelect" id="locationSelect" style="width:100%;"></select>
	</div>
	</div>		
	
</div>
<script>
	load();
</script>
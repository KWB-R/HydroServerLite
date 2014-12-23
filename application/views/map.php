<?php
HTML_Render_Head();

echo $CSS_Main;
echo $JS_JQuery;
echo $JS_Maps;
HTML_Render_Body_Start();
?>
<div class='col-md-9' style='height:500px;'>
<div id="mapOuter" style="width:100%; height:500px;">
	<div id="mapContainer" style="width:100%;">
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
		<?php echo getTxt('SearchDataInst') ?>
		</p>
		<div id="mapLocations" >
		<select name="locationSelect" id="locationSelect" style="width:100%;"></select>
	</div>
	</div>		
</div>
</div></div>
<script>
	load();
</script>
<?
HTML_Render_Body_End();
?>
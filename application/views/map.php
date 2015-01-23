<?php
HTML_Render_Head($js_vars,getTxt('SearchData'));
echo $CSS_Main;
echo $JS_JQuery;
echo $JS_Maps;
HTML_Render_Body_Start();
?>
<div class='col-md-9'>
<?php showMsgs();?>
<div id="mapOuter" style="width:100%; height:875px;">
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
        <div class="btn-group" role="group">
		<input type="button" class="btn btn-default" onClick="searchLocations()" value="<?php echo getTxt('Search'); ?>"/>
        <input type='button' class="btn btn-default" onClick="loadall()" value="<?php echo getTxt('ResetSearch'); ?>"/>
        <input type='button' class="btn btn-default" onClick="track_loc()" value="<?php echo getTxt('FindSites'); ?>"/>
        <input type="button" class="btn btn-default" id="fullscreen" value="<?php echo getTxt('FullScreen'); ?>"/></input>
        <input type="button" class="btn btn-default" id="exitfullscreen" value="<?php echo getTxt('EFullScreen'); ?>"/></input>
        <div class="input-group">
        <input type="checkbox" class="checkbox" id="allSitesCheck" onClick="loadall()" value="allSites"><?php echo getTxt('AllSites'); ?></input>
        </div>
        </div>
      
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
	load();
$(document).ready(function(){	
 $('#fullscreen').click(function(){
    $("#mapOuter").css("position", 'fixed').
      css('top', 0).
      css('left', 0).
      css("width", '100%').
      css("height", '100%');
    google.maps.event.trigger(map, 'resize');
    return false;
  });
 
  $('#exitfullscreen').click(function(){
    $("#mapOuter").css("position", 'relative').
      css('top', 0).
      css("width", googleMapWidth).
      css("height", googleMapHeight);
    google.maps.event.trigger(map, 'resize');
    return false;
	
  });

$.fn.scrollView = function () {
    return this.each(function () {
        $('html, body').animate({
            scrollTop: $(this).offset().top
        });
    });
}
$('#mapOuter').scrollView();
});

</script>
<?php
HTML_Render_Body_End();
?>
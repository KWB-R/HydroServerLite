//Common Scripts to be included on each page of Hydroserver Lite
//Added 1/28/2014

jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();

$( document ).ready(function() {
	
	$('#langChange li a').click(function (e) {
		var newHeading = $(this).text();
		var value = $(this).attr('data');
		$("#dropdownMenu1:first-child").text("Language: "+newHeading);
		$("#dropdownMenu1:first-child").val("Language: "+newHeading);
		
	$.post( base_url+'home/changeLang',{lang:value,disp:newHeading}).done(function( data ) {
	  if (data == "langChanged")
	  {
		location.reload();
	  }
	  else
	  {
		alert("Error in changing Language. Error : " +data);
		return false;  
	  }
  });
		
	});
	
	
  $(document.body).on("change", '#langChange' ,function() {
	
	console.log($(this).val());

  
  $.post( './changeLang.php',{lang:$(this).val()}).done(function( data ) {
	  if (data == "langChanged")
	  {
	  //Check URL for existing GET Parameters
	  var extra = "";
	  if(document.URL.indexOf("?")!=-1)
	  {
		extra = document.URL.split("?")[1];
		extra = extra.replace("langChang=1&&","");
	  }
		
		window.location = "?langChang=1&&" + extra;
	  }
	  else
	  {
		alert("Error in changing Language. Error : " +data);
		return false;  
	  }
  });

});});
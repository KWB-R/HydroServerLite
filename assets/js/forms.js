// Initial calls to set for common form fields
$(document).ready(function () {
    setHintHandlers();
    hideHiddenFields();
});

function setHintHandlers() {
    $(".hint").click(function () {
        var img = $(this);
        var txt = img.attr("title");
        showMessage("Details", txt, img.offset())
    });
}
// to create include the following format
//          goto:URL||text||target_frame
//      or 
//          goto:URL
function showMessage(title, text, offSet, cls) {
    if (typeof cls != "undefined" && cls != null && cls != "") {
        $("#popUp").removeClass().addClass(cls);
    }
    $("#popTitleText").text(title);
    var messArr = text.split("\n");
    for (var i = 0, messLen = messArr.length; i < messLen; i++) {
        var gotoIdx = messArr[i].indexOf("goto:");
        if (gotoIdx > -1) {
            var txt = messArr[i];
            var urlStartIdx = gotoIdx + 5;
            var lookForEndIdx = txt.lastIndexOf("||", txt.length);
            if (lookForEndIdx < 0) lookForEndIdx = urlStartIdx;
            var urlEndIdx = txt.indexOf(" ", lookForEndIdx);
            if (urlEndIdx < 0) urlEndIdx = txt.length;
            var anchorParts = txt.substring(urlStartIdx, urlEndIdx).split("||");
            var url = "<a href='" + anchorParts[0] + "' ";
            if (anchorParts.length > 2) url += "target='" + anchorParts[2] + "' ";
            url += ">";
            if (anchorParts.length > 1 && anchorParts[1].trim() != "")
                url += anchorParts[1];
            else
                url += anchorParts[0];

            url += "</a>";
            messArr[i] = txt.substring(0, gotoIdx) + url + txt.substring(urlEndIdx);
        }
    }
    var textEles = "<p>" + messArr.join("</p><p>") + "</p>";
    $("#popMessage").html(textEles);
    var popper = $("#popUp");
    popper.offset({ top: offSet.top, left: offSet.left }, popper.show(1000));
}
function hideHiddenFields() {
    $(".hiddenField").hide();
}
function getGenericFormDataObject() {
    var outPut = new Object();
    $("*[name]").each(function () {
        var inp = $(this);
        var val = inp.val();
        if (typeof val == "undefined") val = inp.text();
        outPut[inp.attr("name")] = val;
    });
    return outPut;
}
function showSitesForSource(srcID, idOfSiteSelect) {
    var siteSelect = $("#" + idOfSiteSelect);
    if (typeof srcID == "undefined" || srcID == "") {
        siteSelect.parent.append("<p class='warning'>Could not find requested: ##SOURCE##</p>");
        siteSelect.hide();
    } else {
        siteSelect.show();
        $.ajax("getSiteItems.php",//?srcid=" + srcID
            {
                type: "GET",
                data: { srcid: srcID }
            })
            .done(function (data) {
                siteSelect.html(data);
            }).fail(function () {
                alert("Error connecting to site.");
            });
    }
}
function showSites(str) {
	$("#SiteID").empty();
	if(str!="-1")
	{	
		$.ajax({
		 url: base_url+"sites/getSitesJSON?source="+str,
		 dataType: "json"
		})
		 .done(function( sites ) {
			 if(sites.length>0)
			 {
				$("#SiteID").append($("<option />").val(-1).text(phpVars.SelectSite));
				$.each(sites, function() {
					$("#SiteID").append($("<option />").val(this.SiteID).text(this.SiteName));
				});
				
			 }
			 else
			 {
				$("#SiteID").append($("<option />").val(-1).text(phpVars.NoSitesSource));
			 }
		});
	}
	else
	{
		$("#SiteID").append($("<option />").val(-1).text(phpVars.SelectSite));
	}
}

function showMethods(str) {
	$("#MethodID").empty();
	if(str!="-1")
	{
		$.ajax({
		 url: base_url+"methods/getMethodsJSON?var="+str,
		 dataType: "json"
		})
		 .done(function( methods ) {
			 if(methods.length>0)
			 {
				$.each(methods, function() {
					$("#MethodID").append($("<option />").val(this.MethodID).text(this.MethodDescription));
				});
			 }
			 else
			 {
				$("#MethodID").append($("<option />").val(-1).text(phpVars.NoMethodsVariable));
			 }
		});
	}
	else
	{
		$("#MethodID").append($("<option />").val(-1).text(phpVars.SelectVariable));
	}
}

//Date Validation Script Begins
function validatedate(dateid) {

    var value2 = $('#' + dateid).val();

    var rowNum = dateid.slice(10, dateid.length);
    var rowText = "";
    if (typeof rowNum != "undefined" && rowNum != null && rowNum > -1)
        rowText = "Error on row " + rowNum + ": ";

    //Removing all space
    var value = value2.replace(" ", "");

    //minimum length is 10. example 2012-05-31
    if (value.length != 10) {
        alert(rowText + "Invalid date length. Date format should be YYYY-MM-DD");
        return false;
    }
    if (isDate(value, dateid) == false) {
        return false;
    }
    return true;
}

//Check the length of each segment to ensure it is correct. The order is yyyy-mm-dd by default.
function isDate(value, dateid) {
    try {

        var YearIndex = 0;
        var MonthIndex = 1;
        var DayIndex = 2;

        value = value.replace("/", "-").replace(".", "-");
        var SplitValue = value.split("-");
        var OK = true;

        var rowNum = dateid.slice(10, dateid.length);
        var rowText = "";
        if (typeof rowNum != "undefined" && rowNum != null && rowNum > -1)
            rowText = "Error on row " + rowNum + ": ";


        //Check the length of the year
        if (OK && SplitValue[YearIndex].length != 4) {
            alert(rowText + "Please enter the correct length for the YEAR.");
            OK = false;
            return OK;
        }

        //Check the length of the month
        if (OK && SplitValue[MonthIndex].length != 2) {
            alert(rowText + "Please enter the correct length for the MONTH.");
            OK = false;
            return OK;
        }

        //Check the length of the day
        if (SplitValue[DayIndex].length != 2) {
            alert(rowText + "Please enter the correct length for the DAY.");
            OK = false;
            return OK;
        }
        if ((SplitValue[DayIndex] == "00") || (SplitValue[MonthIndex] == "00")) {
            alert(rowText + "Incorrect date. You cannot enter 00.");
            OK = false;
            return OK;
        }

        if (OK) {
            var Year = parseInt(SplitValue[YearIndex], 10);
            var Month = parseInt(SplitValue[MonthIndex], 10);
            var Day = parseInt(SplitValue[DayIndex], 10);

            if (OK = ((Year > 1900) && (Year <= new Date().getFullYear()))) {

                if (OK = (Month <= 12 && Month > 0)) {
                    var LeapYear = (((Year % 4) == 0) && ((Year % 100) != 0) || ((Year % 400) == 0));

                    if (Month == 2) {

                        OK = LeapYear ? Day <= 29 : Day <= 28;
                    }
                    else {
                        if ((Month == 4) || (Month == 6) || (Month == 9) || (Month == 11)) {
                            OK = (Day > 0 && Day <= 30);
                        }
                        else {

                            OK = (Day > 0 && Day <= 31);

                        }
                    }
                }
            }
        }
        if (OK == false) {
            alert(rowText + "Incorrect date range.");
        }
        return OK;
    }
    catch (e) {
        return false;
    }
}
//Date Validation script ends

//Time Validation Script Begins
function validatetime(timeid) {
    var strval = $('#' + timeid).val();

    var rowNum = timeid.slice(5, timeid.length);
    var rowText = "";
    if (typeof rowNum != "undefined" && rowNum != null && rowNum > -1)
        rowText = "Error on row " + rowNum + ": ";

    //Minimum and maximum length is 5, for example, 01:20
    if (strval.length < 5 || strval.length > 5) {
        alert(rowText + "Invalid time. Time format should be five characters long and formatted HH:MM");
        return false;
    }

    //Removing all space
    strval = trimAllSpace(strval);
    $('#' + timeid).val(strval)

    //Split the string
    var newval = strval.split(":");
    var horval = newval[0]
    var minval = newval[1];

    //Checking hours

    //minimum length for hours is two digits, for example, 12
    if (horval.length != 2) {
        alert(rowText + "Invalid time. Hours format should be two digits long.");
        return false;
    }
    if (horval < 0) {
        alert(rowText + "Invalid time. Hours cannot be less than 00.");
        return false;
    }
    else if (horval > 23) {
        alert(rowText + "Invalid time. Hours cannot be greater than 23.");
        return false;
    }

    //Checking minutes

    //minimum length for minutes is 2, for example, 59
    if (minval.length != 2) {
        alert(rowText + "Invalid time. Minutes format should be two digits long.");
        return false;
    }
    if (minval < 0) {
        alert(rowText + "Invalid time. Minutes cannot be less than 00.");
        return false;
    }
    else if (minval > 59) {
        alert(rowText + "Invalid time. Minutes cannot be greater than 59.");
        return false;
    }
    strval = IsNumeric(strval);
    $('#' + timeid).val(strval)
}

//The trimAllSpace() function will remove any extra spaces
function trimAllSpace(str) {
    var str1 = '';
    var i = 0;
    while (i != str.length) {
        if (str.charAt(i) != ' ')
            str1 = str1 + str.charAt(i); i++;
    }
    return str1;
}

//The trimString() function will remove 
function trimString(str) {
    var str1 = '';
    var i = 0;
    while (i != str.length) {
        if (str.charAt(i) != ' ') str1 = str1 + str.charAt(i); i++;
    }
    var retval = IsNumeric(str1);
    if (retval == false)
        return -100;
    else
        return str1;
}

//The IsNumeric() function will check whether the user has entered a numeric value or not.
function IsNumeric(strString) {
    var strValidChars = "0123456789:";
    var blnResult = true;

    //test strString consists of valid characters listed above
    for (i = 0; i < strString.length && blnResult == true; i++) {
        var strChar = strString.charAt(i);
        if (strValidChars.indexOf(strChar) == -1) {
            alert("Invalid character. You may only use numbers.");
            strString = strString.replace(strString[i], "");
            blnResult = false;
        }
    }
    return strString;
}
//Time Validation Script Ends

//Number validatin script
function validatenum(valid) {
    var v = $('#' + valid).val();
    var Value = isValidNumber(v, valid);
    return Value;
}

function isValidNumber(val, valid) {
    if (val == null || val.length == 0) {
        alert("Please enter a number in the Value box");
        return false;
    }

    var DecimalFound = false
    for (var i = 0; i < val.length; i++) {
        var ch = val.charAt(i)
        if (i == 0 && ch == "-") {
            continue
        }
        if (ch == "." && !DecimalFound) {
            DecimalFound = true
            continue
        }
        if (ch < "0" || ch > "9") {
            alert("Please enter a valid number in the Value box");
            return false;
        }
    }
    return true;
}
//Number Validation script ends

function showFieldIfAdd(obj, idToToggle, options) {
    var val = $(obj).val();
    if (typeof options != "object" || options == null)
        options = {}
    if (typeof options.delay == "undefined" || options.delay == null
        || options.delay < 0)
        options.delay = 500;

    var inp = $("#" + idToToggle);
    if (val == "-1") {
        inp.show(options.delay);
    } else {
        inp.hide(options.delay);
    }

    if (typeof options == "object" || options != null)
        if (typeof options.after != "undefined" && typeof options.after == "function")
            if (typeof options.param != "undefined" || options.param != null)
                options.after(options.param);
            else
                options.after();


}
function showSpinningWheelOnMe(obj) {
    $(obj).css("background", "transparent url(images/loader.gif) no-repeat");
    $(obj).val("    Processing...");
}

function showDetailsForHoveredOption(obj) {
    var sel = $(obj);
    var e = event || window.event;
    var target = e.target || e.srcElement;
    if (target.tagName == "OPTION") {
        showDetailsFromTitle(target);
    }
}

function showDetailsFromTitle(obj) {
    var ths = $(obj);
    var tle = ths.attr("title");
    var oriOffSet = ths.offset();
    var newLeft = oriOffSet.left + ths.outerWidth() + 15;
    if (newLeft + ths.outerWidth() > document.documentElement.clientWidth)
        newLeft = oriOffSet.left - 315;
    var offer = { top: oriOffSet.top, left: newLeft };
    showMessage("More Information", tle, offer, "");
}
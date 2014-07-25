$("table input[type=checkbox].useData").each(function () {
    var chk = $(this);
    chk.change(function () { highlightRowIfChecked(this,"selected","skipped"); });
    highlightRowIfChecked(chk,"selected","skipped");
});

$("table input[type=radio].useData").each(function () {
    var chk = $(this);
    chk.change(function () { highlightRowIfChecked(this, "selected", "skipped"); });
    highlightRowIfChecked(chk, "selected", "skipped");
});

$("table input[type=radio].rowMarker").each(function () {
    var chk = $(this);
    chk.change(function () { highlightRowIfChecked(this,"marked","skipped"); });
    highlightRowIfChecked(chk,"marked","skipped");
});

function highlightRowIfChecked(chk,classTrue,classFalse) {
    if (!(chk instanceof jQuery)) {
        chk = $(chk);
    }
    if (chk.attr("type") == "radio"){
        chk.parent().parent().parent().children("tr").each(function () {
            $(this).removeClass(classTrue).removeClass(classFalse);
        });
    }
    if (chk.is(":checked")) {
        chk.parent().parent().addClass(classTrue).removeClass(classFalse);
    } else {
        chk.parent().parent().removeClass(classTrue).addClass(classFalse);
    }
}
$(document).ready(function () {
    $("table select.selected").each(function () {
        disableOtherListsIfDate(this);
    });
    guessTimeZone();
});

function setAllCheckboxesTo(parentObjSelector, bool) {
    var chks = $(parentObjSelector + " input:checkbox");

    if (bool) {
        chks.attr("checked", "checked");
    } else {
        chks.attr("checked", null);
    }
    chks.change();

}

function setUnit(ddl) {
    var nameParts = ddl.id.split("_");
    var varDDLSelected = $("#variable_col_" + nameParts[2] + " :selected");
    var varUnitID = varDDLSelected.attr("unit");
    var varDDLUnit = $("#unit_col_" + nameParts[2]);
    varDDLUnit.children("option[value="+varUnitID+"]").attr("selected","selected");
}

function getMethods(ddl) {
    var nameParts = ddl.id.split("_");
    var varDDLSelected = $("#variable_col_" + nameParts[2] + " :selected");
    var varID = varDDLSelected.val();
    var varDDLMethod = $("#method_col_" + nameParts[2]);
    $.ajax("getMethodItems.php",
        {
            type: "GET",
            data: { varid: varID }
        })
        .done(function (data) {
            varDDLMethod.html(data);
        }).fail(function () {
            alert("Error connecting to site.");
        });
}


function disableOtherListsIfDate(ddl) {
    var nameParts = ddl.id.split("_");
    var varDDLSelected = $("#variable_col_" + nameParts[2] + " :selected").val();
    var parentList = $("#col_"+nameParts[2]+"_list");
    var unitTitle = $("#unit_col_" + nameParts[2] + "_title");
    var unitContainer = $("#unit_col_" + nameParts[2] + "_container");
    var methTitle = $("#method_col_" + nameParts[2] + "_title");
    var methContainer = $("#method_col_" + nameParts[2] + "_container");
    var dlFormatDateTime = $("#formatDateTimeSetter");
    var dlFormatDateOnly = $("#formatDateSetter");
    var dlFormatTimeOnly = $("#formatTimeSetter"); 
    var dlFormatTimeOffset = $("#formatTimeOffsetSetter"); 
    var hadDateError = false;

    var anotherRowIsDateTime = false;
    var anotherRowIsDateOnly = false;
    var anotherRowIsTimeOnly = false;
    // make sure only one DDL is set to Date Field; there can be only one.
    // -1 = DateTime Field
    // -2 = Date Field
    // -3 = Time Field
    $("select").each(function () {
        if (this.id.indexOf("variable_col_") > -1) {
            var val = $(this).val();
            if (val == -1 || val == -2 || val == -3) {
                var messa;
               
                if (val == -1) {
                    if (anotherRowIsDateTime) {
                        hadDateError = true;
                        messa = "Only one column may be set as the datetime field.\n" +
                            "If more than one column is set as a \"DateTime Field\"," +
                            " only the left most column will be used.\n";
                    } else if (anotherRowIsDateOnly) {
                        hadDateError = true;
                        messa = "Another column is set as a date field.\n" +
                            "If more than one column is set as a \"DateTime Field\"," +
                            " \"DateTime Field\", or \"DateTime Field\"," +
                            " only the left most column will be used.\n";
                    } else if (anotherRowIsTimeOnly) {
                        hadDateError = true;
                        messa = "Another column is set as a time field.\n" +
                            "If more than one column is set as a \"DateTime Field\"," +
                            " \"DateTime Field\", or \"DateTime Field\"," +
                            ", only the left most column will be used.\n";
                    }
                } else if (val == -2) {
                    if (anotherRowIsDateTime) {
                        hadDateError = true;
                        messa = "Only one column may be set as the datetime field.\n" +
                            "If more than one column is set as a \"DateTime Field\"," +
                            " only the left most column will be used.\n";
                    } else if (anotherRowIsDateOnly) {
                        hadDateError = true;
                        messa = "Another column is set as a date field.\n" +
                            "If more than one column is set as a \"Date Field\"," +
                            " only the left most column will be used.\n";
                    }
                } else if (val == -3) {
                    if (anotherRowIsDateTime) {
                        hadDateError = true;
                        messa = "Only one column may be set as the datetime field.\n" +
                            "If more than one column is set as a \"DateTime Field\"," +
                            " only the left most column will be used.\n";
                    } else if (anotherRowIsTimeOnly) {
                        hadDateError = true;
                        messa = "Another column is set as a Time field.\n" +
                            "If more than one column is set as a \"Time Field\"," +
                            " only the left most column will be used.\n";
                    }
                }
                var sel = $(this);
                var offS = sel.offset();
                offS.left += sel.outerWidth(true);
                if (typeof messa != "undefined" && messa != "") {
                    showMessage("Error", messa + " If you wish to ignore the data in a column, please select \"--Ignore--\".", offS, "error");
                }
            }
            if (val == -1 && !anotherRowIsDateTime)
                anotherRowIsDateTime = true;
            if (val == -2 && !anotherRowIsDateOnly)
                anotherRowIsDateOnly = true;
            if (val == -3 && !anotherRowIsTimeOnly)
                anotherRowIsTimeOnly = true;
        }
    });
    if (nameParts.length > 2) {
        if (parentList.find(dlFormatDateTime).length > 0) {
            $("#formatDateTimeSetterTitle").after(dlFormatDateTime)
        }
        if (parentList.find(dlFormatDateOnly).length > 0) {
            $("#formatDateSetterTitle").after(dlFormatDateOnly)
        }
        if (parentList.find(dlFormatTimeOnly).length > 0) {
            $("#formatTimeSetterTitle").after(dlFormatTimeOnly)
        }
        if (parentList.find(dlFormatTimeOffset).length > 0) {
            $("#formatTimeOffsetSetterTitle").after(dlFormatTimeOffset)
        }
        // add 3 to get correct column. +1 for offset of 0, +2 to get passed first two (non-data) columns.
        var myColumn = $("#importWizardData td:nth-child(" + (nameParts[2] * 1 + 3) + ")");
        if (varDDLSelected < 0) {
            //unitDDL.attr("disabled", "disabled");
            //methDDL.attr("disabled", "disabled");
            unitTitle.hide();
            unitContainer.hide();
            methTitle.hide();
            methContainer.hide();
            if (varDDLSelected == -10) {
                // ignored column
                myColumn.removeClass("highlighted");
                myColumn.addClass("ignored");
            } else {
                myColumn.removeClass("ignored");
                myColumn.addClass("highlighted");
                if (!hadDateError) {
                    if (varDDLSelected == -1) {
                        // add datetime formatter
                        parentList.append(dlFormatDateTime);
                        parentList.append(dlFormatTimeOffset);
                    }
                    else if (varDDLSelected == -2) {
                        // add date only formatter
                        parentList.append(dlFormatDateOnly);
                    }
                    else if (varDDLSelected == -3) {
                        // add time only formatter
                        parentList.append(dlFormatTimeOnly);
                        parentList.append(dlFormatTimeOffset);
                    }
                }
            }
        } else {
            //unitDDL.attr("disabled", null);
            //methDDL.attr("disabled", null);
            unitTitle.show();
            unitContainer.show();
            methTitle.show();
            methContainer.show();
            myColumn.removeClass("ignored");
            myColumn.removeClass("highlighted");
        }
    }
}
function showTimeFormatter(ddl) {
    var selectedTime = $(ddl).val();
    var lastChar = $.trim(selectedTime).substr(-1);
    var offSetChars = "O;P;T;e;I;Z;"
    if (offSetChars.indexOf(lastChar + ";") < 0) {
        // do not have an offset type in format
        $("#formatTimeOffset").show(500);
    } else {
        $("#formatTimeOffset").hide(500);
    }
    
}
function guessTimeZone() {
    var useDST = $("#timeInDST").is(":checked");
    var selTZ = $("#timeOffset");
    var localOffset = new Date().getTimezoneOffset();
    var localHours = localOffset / 60;
    var offSetString;

    if (useDST) {
        localHours += 1;
    }

    if (localHours < 0) {
        localHours = "0" + (-1 * localHours).toString();
        offSetString = "GMT+" + localHours;
    } else if (localHours > 0) {
        localHours = "0" + localHours.toString();
        offSetString = "GMT-" + localHours;
    } else {
        offSetString = "(GMT)";
    }

    offSetString +=":";

    //alert(useDST + "|" + localOffset + "|" + offSetString);

    var foundFirst = false;

    selTZ.children("option").each(function () {
        if (!foundFirst && this.innerText.indexOf(offSetString) > -1) {
            $(this).attr("selected", "selected");
            foundFirst = true;
        } else {
            $(this).attr("selected", null);
        }
    });

}
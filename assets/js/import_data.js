/// <reference path="jquery-1.7.2.js" />

function show_answer() {
    alert("If you do not see your location (Site) listed here, please contact your supervisor and ask them to add it before entering data.");
}

function show_answer2() {
    alert("If you do not see your Method listed here, please contact your supervisor and ask them to add it before entering data.");
}
function log(file) {
    var str = "";
    var id2;
    plupload.each(arguments, function (arg) {
        var row = "";

        if (typeof (arg) != "string") {
            plupload.each(arg, function (value, key) {
                // Convert items in File objects to human readable form

                if ((typeof (value) != "function") && (key == "name")) {
                    row += value;
                }
                if ((typeof (value) != "function") && (key == "id")) {
                    id2 = value;
                }

            });

            str += row + " ";
        } else {
            str += arg + " ";
        }
    });

    //Uploads the file and file name is stored in str
    //Now to start processing this file and check if its a valid csv file
    //lets process it on a server side php query and then get the result using an ajax request
    //if invalid file delete it from the server and delete it from the list
    //else pass the file name to a php script to add data

}



function $(id) {
    return document.getElementById(id);
}


var uploader = new plupload.Uploader({
    runtimes: 'gears,html5,flash,silverlight,browserplus',
    browse_button: 'pickfiles',
    unique_names: true,
    container: 'container',
    max_file_size: '10mb',
    multi_selection: false,
    url: 'upload.php',
    resize: { width: 320, height: 240, quality: 90 },
    flash_swf_url: 'uploader/plupload.flash.swf',
    silverlight_xap_url: 'uploader/plupload.silverlight.xap',
    filters: [
		{ title: "CSV File", extensions: "csv" },
		{ title: "Text File", extensions: "txt" }],
    rename: true,
    init: {

        FileUploaded: function (up, file, info) {
            // Called when a file has finished uploading
            log(file);
            check(file.name, file.id);
        },


        Error: function (up, args) {
            // Called when a error has occured
            alert("Invalid File Type Selected");

        }
    }



});



uploader.bind('Init', function (up, params) {
    ;
});

uploader.bind('FilesAdded', function (up, files) {
    for (var i in files) {
        var temp = files[i].name.split(".");

        var ext = temp[1];

        if (ext == "csv" || ext == "txt") {
            $('filelist').innerHTML += '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
            (function ($) { $("#filename").val(files[i].name); })(jQuery);
            files[i].name = files[i].id + "." + ext;
        }
    }
});

uploader.bind('UploadProgress', function (up, file) {
    $(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
});

$('uploadfiles').onclick = function () {
    uploader.start();
    return false;
};



uploader.init();


function del(id2) {


    var file1 = uploader.getFile(id2);
    $(file1.id).getElementsByTagName('b')[0].innerHTML = '<span>' + "Invalid File! Make changes and re-upload" + "</span>";
    (function ($) {
        $("#filename").val("Invalid File");
    })(jQuery);


}

//var fileToUpload;

function check(name, id2) {
    //alert(name);

    var url2 = 'upload_check.php?name=' + name;



    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var msg = xmlhttp.responseText;

            if (msg == "true") {
                alert("Upload successful. Click on submit data to proceed.");
                fileToUpload = name;

            }
            else { alert(msg); del(id2); }
        }
    }
    xmlhttp.open("GET", url2, true);
    xmlhttp.send();


}



(function ($) {
    $(document).ready(function () {
        $("#filename").val("Please select a file....");
        // echo $jq;
        var glob_siteid = 1;
        var fileToUpload = "";

        $("#viewdata").click(function () {
            window.location.href = "details.php?siteid=" + glob_siteid;

        });


        $("#viewdata2").click(function () {
            window.location.href = "import_data_file.php";

        });

        $("#statusmsg").hide();

    });

    $("#submit1").click(function () {

        if (($("#SourceID option:selected").val()) == -1) {
            alert("Please select a Source!");
            return false;
        }

        if (($("#SiteID option:selected").val()) == -1) {
            alert("Please select a Site!");
            return false;
        }

        glob_siteid = $("#SiteID option:selected").val();
        if (($("#VariableID option:selected").val()) == -1) {
            alert("Please select a Type!");
            return false;
        }

        if (($("#MethodID option:selected").val()) == -1) {
            alert("Please select a Method!");
            return false;
        }

        //All Validation checks completed...time to open the file and start processing




        var sourceid = $("#SourceID option:selected").val();
        var siteid = $("#SiteID option:selected").val();
        glob_siteid = siteid;
        var variableid = $("#VariableID option:selected").val();
        var methodid = $("#MethodID option:selected").val();


        if (typeof fileToUpload === "undefined") {
            alert("Please upload a file");
            return false;
        }

        var file_final = fileToUpload;

        $.ajax({
            type: "POST",
            url: "do_import_data_file.php?SourceID=" + sourceid + "&SiteID=" + siteid + "&VariableID=" + variableid + "&MethodID=" + methodid + "&filename=" + fileToUpload
        }).done(function (msg) {
            if (msg == 1) {
                $("#statusmsg").show(1200);
                return true;
            }
            else {
                alert(msg);
                alert("Error in database configuration");
                return false;

            }
        });



        return false;


    });
})(jQuery);



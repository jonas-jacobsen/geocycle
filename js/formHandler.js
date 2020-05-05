<!--script zur Formularverabeitung -->

//wenn Button geklickt wird, rotierender pfeil
var rotateCircle = "<p><i class=\"fas fa-sync\"></i></p>";

$('#ansprech_Form').submit(function (event) {
    event.preventDefault(); //seitenreloud wird verhindert
    $('#didChangeContactPers').html(rotateCircle);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'components/updateDataContactPersAjax.php',
        data: $(this).serialize(),
        success: function (response) {
            $('#contactPersCheck').html(response.contactPersCheck);
            contactPersCheckVar = response.contactPersCheckVar;
            showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar);
        }
    });
    //set Timeout for showing Anderungen vorgenommen
    setTimeout(function () {
        $('#didChangeContactPers').html('')
    }, 1000);
});

$('#request_Form').submit(function (event) {
    event.preventDefault(); //seitenreloud wird verhindert
    $('#didChangeRequest').html(rotateCircle);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'components/updateRequestAjax.php',
        data: $(this).serialize(),
        success: function (data) {
            $('#requestCheck').html(data.requestCheck);
            requestCheckVar = data.requestCheckVar;
            showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar);
            //alert("Daten in Ansprechpartner geändert")
        },
    });
    //set Timeout for showing Anderungen vorgenommen
    setTimeout(function () {
        $('#didChangeRequest').html('')
    }, 1000);
});

$('#furtherInformationForm').submit(function (event) {
    event.preventDefault(); //seitenreloud wird verhindert
    $('#didChangeFurtherInfo').html(rotateCircle);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'components/updateFurtherInfoAjax.php',
        data: $(this).serialize(),
        success: function (dataTwo) {
            $('#furtherInfoCheck').html(dataTwo.furtherInfoCheck);
            furtherInfoCheckVar = dataTwo.furtherInfoCheckVar;
            showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar);
        },
    });
    //set Timeout for showing Anderungen vorgenommen
    setTimeout(function () {
        $('#didChangeFurtherInfo').html('')
    }, 1000);
});

//dokumente mit ajax call löschen
$(document).on("click", ".delete", function (){
    alert("success");
    var divFileId;
    var id = $(this).attr("id");
    $.ajax({
        type: 'POST',
        dataType: 'text',
        async:true,
        url: 'components/updateFilesAjax.php',
        data: {id:id},
        success: function (dataThree) {
            divFileId = dataThree;
            $('#'+divFileId).hide();
        },
    });
});


//FileUpload
$(function () {
    var files = $("#files");
    $("#fileupload").fileupload({
        type: 'POST',
        url: 'dashboard.php',
        dropZone: '#dropzone',
        dataType: 'json',
        autoUpload: false
    }).on('fileuploadadd', function (e, data) {
        var fileTypeAllowed = /.\.(jpg|png|jpeg|pdf)$/i;
        var fileName = data.originalFiles[0]['name'];
        var fileSize = data.originalFiles[0]['size'];

        if (!fileTypeAllowed.test(fileName)) {
            $("#error").html("Dateityp nicht unterstützt");
        } else if (fileSize > 5000000) {
            $("#error").html("Datei zu groß! Max 500 kb");
        } else {
            $("#error").html('');
            //docOneCheckVar auf ein setzen damit progressbar aktualisert wird
            docOneCheckVar = 1;
            docOneCheck = "<i class=\"far fa-check-circle green-text\"></i>";
            $('#docOneCheck').html(docOneCheck);
            data.submit();
        }
    }).on('fileuploaddone', function (e, data) {
        var fileName = data.originalFiles[0]['name'];
        var status = data.jqXHR.responseJSON.status;
        var msg = data.jqXHR.responseJSON.msg;
        if (status == 1) {
            var path = data.jqXHR.responseJSON.path;
            var newFileId = data.jqXHR.responseJSON.newFileId;
            if (path.indexOf('.pdf') == -1) {
                icon = "imgIcon.png";
            } else {
                icon = "pdfIcon.png";
            }
            $("#einbinden").fadeIn().append('<div id="'+newFileId+'"><div class="view overlay hm-green-slight"><figure><a href="' + path + '" target="_blank"><img style="width: 100%" src="assets/images/' + icon + '"/></a><div class="mask flex-center"><p class="white-text"><a href="' + path + '" target="_blank">Anzeigen</a></p></div></figure></div><div style="text-align: center"><p>' + fileName + '</p><input type="hidden" name="deleteFileId" value="' + newFileId + '"><button type="button" id="'+newFileId+'" class="btn btn-outline-danger waves-effect delete"><i class="far fa-trash-alt"></i></button></div></div>');
        } else {
            $("#error").html(msg);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $("#progess").html("Hochgeladen zu " + progress + "%");
        showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar);
    });
});

//Progressbar überprüfen bei Ajax-Caall

function showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar) {
    countNumber = contactPersCheckVar + requestCheckVar + furtherInfoCheckVar + docOneCheckVar;
    if (countNumber == 4) {
        $('.progress-bar').css('width', '100%');
        $('#progressValue').html('100');
    } else if (countNumber == 3) {
        $('.progress-bar').css('width', '75%');
        $('#progressValue').html('75');
    } else if (countNumber == 2) {
        $('.progress-bar').css('width', '50%');
        $('#progressValue').html('50');
    } else if (countNumber == 1) {
        $('.progress-bar').css('width', '25%');
        $('#progressValue').html('25');
    } else {
        $('.progress-bar').css('width', '0%');
        $('#progressValue').html('0');
    }
}

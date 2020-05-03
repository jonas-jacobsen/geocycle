
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

            showProgressBarValue(contactPersCheckVar, requestCheckVar);
            //alert("Daten in Ansprechpartner geändert")
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

            showProgressBarValue(requestCheckVar, contactPersCheckVar);
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
        url: 'components/updateFurtherInfo.php',
        data: $(this).serialize(),
        success: function (dataTwo) {
            $('#furtherInfoCheck').html(dataTwo.furtherInfoCheck);

            furtherInfoCheckVar = dataTwo.furtherInfoCheckVar;

            showProgressBarValue(requestCheckVar, contactPersCheckVar, furtherInfoCheckVar);
            //alert("Daten in Ansprechpartner geändert")
        },
    });
    //set Timeout for showing Anderungen vorgenommen
    setTimeout(function () {
        $('#didChangeFurtherInfo').html('')
    }, 1000);
});


//Progressbar überprüfen bei Ajax-Caall
function showProgressBarValue(contactPersCheckVar, requestCheckVar) {
    if (contactPersCheckVar == 1 && requestCheckVar == 1 && furtherInfoCheckVar == 1) {
        $('.progress-bar').css('width', '100%');
        $('#progressValue').html('100');
    } else if(contactPersCheckVar == 0 && requestCheckVar == 1 || contactPersCheckVar == 1 && requestCheckVar == 0){
        $('.progress-bar').css('width', '50%');
        $('#progressValue').html('50');
    } else {
        $('.progress-bar').css('width', '0%');
        $('#progressValue').html('0');
    }
}

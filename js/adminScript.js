<!--script zur Formularverabeitung -->

//wenn Button geklickt wird, rotierender pfeil
var rotateCircle = "<p><i class=\"fas fa-sync\"></i></p>";

$('#ansprech_Form').submit(function (event) {
    event.preventDefault(); //seitenreloud wird verhindert
    $('#didChangeContactPers').html(rotateCircle);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'components/script/updateDataContactPersAjax.php',
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
        url: 'components/script/updateRequestAjax.php',
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
        url: 'components/script/updateFurtherInfoAjax.php',
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
$(document).on("click", ".buttonChangeCategory", function () {
    var id = $(this).attr("id");
    var value = $(this).attr("value");
    var requestId;
    console.log(id);
    console.log(value);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        async: true,
        url: 'components/script/updateAllocationAjax.php',
        data: {
            id: id,
            value: value,
        },
        success: function (data) {
            console.log(data.requestNb);
            console.log("neuerWert: "+data.neuerWert);
            requestId = data.requestNb;
            $('#rowWithId' + requestId).hide();
        },
    });
});
<!--script zur Formularverabeitung -->
//wenn Button geklickt wird, rotierender pfeil
var rotateCircle = "<p><i class=\"fas fa-sync\"></i></p>";

//funktion zur Berechnung der Entfernung des Werkes
const beckum = "[8.0556895,51.7643485]";
const dotternhausen = "[8.7803442, 48.2287518]";
const hoever = "[9.8893724, 52.3517868]";
const laegerdorf = "[9.5767891, 53.8787635]";

let addresses;
var custAdress;
let distances;

function calcDistances() {
    let request = new XMLHttpRequest();

    request.open('POST', "https://api.openrouteservice.org/v2/matrix/driving-car");

    request.setRequestHeader('Accept', 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8');
    request.setRequestHeader('Content-Type', 'application/json');
    request.setRequestHeader('Authorization', '5b3ce3597851110001cf62480b008bd0ed3e434ca180a642aa491b11');

    request.onreadystatechange = function () {
        if (this.readyState === 4) {
            distances = JSON.parse(this.responseText);
            getClosestFacility(distances.distances[0]);
        }
    };

    // let adresses = [custAdress, werk1, werk2, werk3, werk4];
    addresses = [
        {name: "customAdress", coords: custAdress},
        {name: "Beckum", coords: beckum},
        {name: "Dotternhausen", coords: dotternhausen},
        {name: "Höver", coords: hoever},
        {name: "Lägerdorf", coords: laegerdorf},
    ]

    addressCoords = addresses.map(function (elem) {
        return elem.coords;
    }).join(","); // join array of all addresses to one string
    const body = '{"locations":[' + addressCoords + '],"metrics":["distance"]}';

    request.send(body);
}

function getClosestFacility(array) {
    array.shift();
    var i = array.indexOf(Math.min.apply(Math, array));

    let facName = addresses[i + 1].name;
    let facDist = array[i] / 1000;
    var resultArray = [{name: facName, distance: facDist}];
    resultArray = JSON.stringify(resultArray);
    document.getElementById("facility").value = resultArray;
}

// get lat-long to adress
function getLatLong() {
    let street = document.getElementById("street").value;
    let zip = document.getElementById("zipcode").value;
    let town = document.getElementById("town").value;
    custAdress = [street, zip, town].join(",")

    var req = new XMLHttpRequest();

    url = 'https://api.openrouteservice.org/geocode/search?api_key=5b3ce3597851110001cf62480b008bd0ed3e434ca180a642aa491b11&text=' + custAdress;
    req.open('GET', url);

    req.setRequestHeader('Accept', 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8');

    req.onreadystatechange = function () {
        if (this.readyState === 4) {
            var temp = JSON.parse(this.responseText);
            var long = temp.bbox[0];
            var lat = temp.bbox[1];
            custAdress = '[' + long + ',' + lat + ']';
            calcDistances();
        }
    };
    req.send();
}

/* Eventlistener */
let townField = document.getElementById('town');
townField.addEventListener('change', getLatLong, false);

//let submitAnsprechButton = document.getElementById('submitAnsprech');
//submitAnsprechButton.addEventListener('click', getLatLong, false);
//End Check Distance Factory


//Änderung Panel Ansprechform
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

//Änderung Panel Request
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
            docOneCheckVar = data.docOneCheckVar;
            showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar);
            //alert("Daten in Ansprechpartner geändert")
        },
    });
    //set Timeout for showing Anderungen vorgenommen
    setTimeout(function () {
        $('#didChangeRequest').html('')
    }, 1000);
});

//ab hier parameterliste
/**
 * Checks if Hu is lower of higher than 10
 * for determining between "Brennstoff" and "Rohstoff"
 **/
let huInput = document.querySelector('#row0 > input[name="value"]');
let huResult = document.getElementById('huResult');

function checkHu() {
    let huValue = huInput.value;
    huValue = huValue.replace(/,/gi, '.');
    huValue = huValue.replace(/[<>]/gi, '');
    if (huValue >= 10) {
        huResult.innerHTML = "BRENNSTOFF";
        $(".rohstoff").css("visibility", "hidden");
        $(".rohstoff").css("height", "0");
        $(".brennstoff").css("visibility", "visible");
        $(".brennstoff").css("height", "auto");
        $(".rohstoff:not(.brennstoff) input[name='value']").val(0);
        $(".brennstoff input[value='']").val("");
        $(".brennstoff input[value='0']").val("");

    } else if (huValue < 10) {
        huResult.innerHTML = "ROHSTOFF";
        $(".brennstoff").css("visibility", "hidden");
        $(".brennstoff").css("height", "0");
        $(".rohstoff").css("visibility", "visible");
        $(".rohstoff").css("height", "auto");
        $(".brennstoff:not(.rohstoff) input[name='value']").val(0);
        $(".rohstoff input[value='']").val("");
        $(".rohstoff input[value='0']").val("");

    } else {
        huResult.innerHTML = "Hu Undefiniert";
    }
}

//einmaliges prüfen bei Datenbankabfrage
function initCheckHu() {
    let huValue = huInput.value;
    huValue = huValue.replace(/,/gi, '.');
    huValue = huValue.replace(/[<>]/gi, '');
    if (huValue >= 10) {
        huResult.innerHTML = "BRENNSTOFF";
        $(".rohstoff").css("visibility", "hidden");
        $(".rohstoff").css("height", "0");
        $(".brennstoff").css("visibility", "visible");
        $(".brennstoff").css("height", "auto");

    } else if (huValue < 10) {
        huResult.innerHTML = "ROHSTOFF";
        $(".brennstoff").css("visibility", "hidden");
        $(".brennstoff").css("height", "0");
        $(".rohstoff").css("visibility", "visible");
        $(".rohstoff").css("height", "auto");
    }
}

huInput.addEventListener('change', checkHu);
initCheckHu();


/**
 * Add new Row with custom parameter to analyse-Form
 **/
let addRowButton = document.getElementById("addRowButton");
let id = 5; // index id for new row, to keep track of row numbers --> number of required fields
let rowContent = '<input style="margin-right: 9px" type="text" name="param" placeholder="Parameter"/><input type="text" name="value" placeholder="Messwert" autocomplete="off" required pattern="[0-9<>,]{1,}" title="Nur \'1-9\', \',\' und \'< >\'"/><select style="margin-left: 5px" name="unit"><option selected="">mg/kg</option><option>ng/kg</option><option>Âµg/g</option><option>mj/kg</option><option>% TS</option><option>% OS</option></select>'; // inner HTML of blank row
let analyseForm = document.getElementById('param-div'); // Form to add row to

function addRow() {
    id++;
    let newRow = document.createElement('div');
    newRow.className = "ing-row";
    newRow.id = "row" + id;
    newRow.innerHTML = rowContent;
    analyseForm.appendChild(newRow);
    let lastEntry = analyseForm.lastChild.querySelector("[name='param']") // gets Param-Input for created entry
    autocomplete(lastEntry, parameterSuggs); // adds autocomplete for Parameter-Input
    lastEntry.focus();
}

addRowButton.addEventListener('click', addRow);


/**
 * Save all typed-in parameter as json string
 **/
let saveListButton = document.getElementById("saveListButton");
let analysisString = document.getElementById("analysisString");


$('#furtherInformationForm').submit(function (event) {
    event.preventDefault(); //seitenreloud wird verhindert

    let ingValues = []; // empty array to save list in
    let params = analyseForm.querySelectorAll("[name='param']"); // all params of added ingedients
    let values = analyseForm.querySelectorAll("[name='value']");// all values of added ingedients
    let units = analyseForm.querySelectorAll("[name='unit']"); // all units of added ingedients
    let ingRow = document.getElementsByClassName("ing-row"); // all rows
    for (var i = 0; i < ingRow.length; i++) {
        let row = { //create associative array for each row with keys
            "param": params[i].value,
            "value": values[i].value,
            "units": units[i].value
        }
        ingValues.push(row);
    }
    var listJson = JSON.stringify(ingValues);
    analysisString.value = listJson;

    //variablen der beiden Textareas
    var dispRoute = $('textarea[name="dispRoute"]').val();
    var procDesc = $('textarea[name="procDescr"]').val();
    var priceCondition = $('#priceCondition').val();
    var offeredPrice = $('#offeredPrice').val();

    $('#didChangeFurtherInfo').html(rotateCircle);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'components/script/updateFurtherInfoAjax.php',
        data: {
            dispRoute: dispRoute,
            procDesc: procDesc,
            listJson: listJson,
            priceCondition: priceCondition,
            offeredPrice: offeredPrice,
        },
        success: function (dataFurthInfo) {
            $('#furtherInfoCheck').html(dataFurthInfo.furtherInfoCheck);
            furtherInfoCheckVar = dataFurthInfo.furtherInfoCheckVar;
            showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar);
        },
    });
    //set Timeout for showing Anderungen vorgenommen
    setTimeout(function () {
        $('#didChangeFurtherInfo').html('')
    }, 1000);
});
//End Parameterlist

//dokumente mit ajax call löschen
$(document).on("click", ".delete", function () {
    var divFileId;
    var id = $(this).attr("id");
    $.ajax({
        type: 'POST',
        dataType: 'json',
        async: true,
        url: 'components/script/updateFilesAjax.php',
        data: {
            id: id,
        },
        success: function (dataThree) {
            divFileId = dataThree.fileId;
            $('#' + divFileId).hide();
            $('#docOneCheck').html(dataThree.fileUploadCheck);
            docOneCheckVar = dataThree.fileUploadCheckVar;
            showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar);
        },
    });
});


//FileUpload
$(function () {
    var files = $("#files");
    $("#fileupload").fileupload({
        type: 'POST',
        url: 'request.php',
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
            $("#einbinden").fadeIn().append('<div id="' + newFileId + '"><div class="view overlay hm-green-slight"><figure><a href="' + path + '" target="_blank"><img style="width: 100%" src="assets/images/' + icon + '"/></a><div class="mask flex-center"><p class="white-text"><a href="' + path + '" target="_blank">Anzeigen</a></p></div></figure></div><div style="text-align: center"><p>' + fileName + '</p><input type="hidden" name="deleteFileId" value="' + newFileId + '"><button type="button" id="' + newFileId + '" class="btn btn-outline-danger waves-effect delete"><i class="far fa-trash-alt"></i></button></div></div>');
        } else {
            $("#error").html(msg);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $("#progess").html("Hochgeladen zu " + progress + "%");
        showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar);
    });
});

//FileUpload 2
$(function () {
    var files = $("#files");
    $("#fileuploadFurtherDocs").fileupload({
        type: 'POST',
        url: 'request.php',
        dropZone: '#dropZoneFutherDocs',
        dataType: 'json',
        autoUpload: false
    }).on('fileuploadadd', function (e, data) {
        var fileTypeAllowed = /.\.(jpg|png|jpeg|pdf)$/i;
        var fileName = data.originalFiles[0]['name'];
        var fileSize = data.originalFiles[0]['size'];

        if (!fileTypeAllowed.test(fileName)) {
            $("#errorFurtherDocs").html("Dateityp nicht unterstützt");
        } else if (fileSize > 5000000) {
            $("#errorFurtherDocs").html("Datei zu groß! Max 500 kb");
        } else {
            $("#errorFurtherDocs").html('');
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
            $("#einbindenFurherDocs").fadeIn().append('<div id="' + newFileId + '"><div class="view overlay hm-green-slight"><figure><a href="' + path + '" target="_blank"><img style="width: 100%" src="assets/images/' + icon + '"/></a><div class="mask flex-center"><p class="white-text"><a href="' + path + '" target="_blank">Anzeigen</a></p></div></figure></div><div style="text-align: center"><p>' + fileName + '</p><input type="hidden" name="deleteFileId" value="' + newFileId + '"><button type="button" id="' + newFileId + '" class="btn btn-outline-danger waves-effect delete"><i class="far fa-trash-alt"></i></button></div></div>');
        } else {
            $("#errorFurtherDocs").html(msg);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $("#progessFurtherDocs").html("Hochgeladen zu " + progress + "%");
    });
});

//Progressbar überprüfen bei Ajax-Caall
function showProgressBarValue(contactPersCheckVar, requestCheckVar, furtherInfoCheckVar, docOneCheckVar) {
    docOneCheckVarTmp = 0;
    if (docOneCheckVar == 3) {
        countNumber = contactPersCheckVar + requestCheckVar + furtherInfoCheckVar;
        if (countNumber == 3) {
            $('.progress-bar').css('width', '100%');
            $('#progressValue').html('100');
            $('#progressValueSide').html('100');
            $('#filledOut').html('<button type="submit" id="requestIsFilledOut" name="requestIsFilledOut" value="1" class="btn btn-outline-success waves-effect">Anfrage Abschicken</button>');
        } else if (countNumber == 2) {
            $('.progress-bar').css('width', '66%');
            $('#progressValue').html('66');
            $('#progressValueSide').html('66');
            $('#filledOut').html('<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect "><a class="info_toogle_hover" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Bitte Füllen Sie alle Felder aus, damit die Anfrage abgeschickt werden kann">Anfrage Abschicken</a></button>');
        } else if (countNumber == 1) {
            $('.progress-bar').css('width', '33%');
            $('#progressValue').html('33');
            $('#progressValueSide').html('33');
            $('#filledOut').html('<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect "><a class="info_toogle_hover" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Bitte Füllen Sie alle Felder aus, damit die Anfrage abgeschickt werden kann">Anfrage Abschicken</a></button>');
        } else {
            $('.progress-bar').css('width', '0%');
            $('#progressValue').html('0');
            $('#progressValueSide').html('0');
            $('#filledOut').html('<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect "><a class="info_toogle_hover" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Bitte Füllen Sie alle Felder aus, damit die Anfrage abgeschickt werden kann">Anfrage Abschicken</a></button>');
        }
    } else {
        countNumber = contactPersCheckVar + requestCheckVar + furtherInfoCheckVar + docOneCheckVar;
        console.log("countNmb: "+countNumber);
        console.log("contact: "+contactPersCheckVar);
        console.log("request: "+requestCheckVar);
        console.log("further: "+furtherInfoCheckVar);
        console.log("doc: "+docOneCheckVar);
        if (countNumber == 4) {
            $('.progress-bar').css('width', '100%');
            $('#progressValue').html('100');
            $('#progressValueSide').html('100');
            $('#filledOut').html('<button type="submit" id="requestIsFilledOut" name="requestIsFilledOut" value="1" class="btn btn-outline-success waves-effect">Anfrage Abschicken</button>');
        } else if (countNumber == 3) {
            $('.progress-bar').css('width', '75%');
            $('#progressValue').html('75');
            $('#progressValueSide').html('75');
            $('#filledOut').html('<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect "><a class="info_toogle_hover" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Bitte Füllen Sie alle Felder aus, damit die Anfrage abgeschickt werden kann">Anfrage Abschicken</a></button>');
        } else if (countNumber == 2) {
            $('.progress-bar').css('width', '50%');
            $('#progressValue').html('50');
            $('#progressValueSide').html('50');
            $('#filledOut').html('<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect "><a class="info_toogle_hover" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Bitte Füllen Sie alle Felder aus, damit die Anfrage abgeschickt werden kann">Anfrage Abschicken</a></button>');
        } else if (countNumber == 1) {
            $('.progress-bar').css('width', '25%');
            $('#progressValue').html('25');
            $('#progressValueSide').html('25');
            $('#filledOut').html('<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect "><a class="info_toogle_hover" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Bitte Füllen Sie alle Felder aus, damit die Anfrage abgeschickt werden kann">Anfrage Abschicken</a></button>');
        } else {
            $('.progress-bar').css('width', '0%');
            $('#progressValue').html('0');
            $('#progressValueSide').html('0');
            $('#filledOut').html('<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect "><a class="info_toogle_hover" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Bitte Füllen Sie alle Felder aus, damit die Anfrage abgeschickt werden kann">Anfrage Abschicken</a></button>');
        }
    }
}

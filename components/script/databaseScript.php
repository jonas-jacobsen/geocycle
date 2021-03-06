<?php

//Prüfen ob neue oder Vorhandene RequestId in SesssionV Variable. ALte RequestId in $_Post variable
if (isset($_POST['requestId'])) {
    $requestId = $_POST['requestId'];
    $_SESSION['requestId'] = $requestId;
} else {
    $requestId = $_SESSION['requestId'];
}

//check ob Daten vorhanden sind
$sql_all = "SELECT * FROM userdata WHERE id = '$requestId'";
$statement = mysqli_query($conn, $sql_all);
$row = mysqli_fetch_array($statement);

//userdata - Requestdata
$userId = $_SESSION['userId'];
//ansprechpartner
$firstname = $row['Firstname'];
$surname = $row['Surname'];
$phone = $row['Phone'];
$street = $row['Street'];
$town = $row['Town'];
$zip = $row['Zip'];
$factory = htmlspecialchars($row['ClosestFactory']);
//Anfrage
$prodAbf = $row['ProdAbf'];
$erzHae = $row['ErzHae'];
$jato = $row['JaTo'];
$producer = $row['Producer'];
$wasteDescription = $row['WasteDescription'];
$avv = $row['Avv'];

//Preis von String in Float umwandeln  um damit rechnen zu können
$offeredPrice = floatval($row['OfferedPrice']);
$preisForGeo = "";
$preisForUser = "";
if ($offeredPrice < 0) {
    $offeredPrice = abs($offeredPrice);
    $preisForGeo = "selected";
} else {
    $preisForUser = "selected";
}

//deliveryform: Prüfen welche ausgewählt:
$deliveryFormEXW = "";
$deliveryFormFCA = "";
$deliveryFormCPT = "";
$deliveryFormCIP = "";
$deliveryFormDAP = "";
$deliveryFormDAT = "";
$deliveryForm = $row['DeliveryForm'];

if ($deliveryForm == "EXW") {
    $deliveryFormEXW = "selected";
} elseif ($deliveryForm == "FCA") {
    $deliveryFormFCA = "selected";
} elseif ($deliveryForm == "CPT") {
    $deliveryFormCPT = "selected";
} elseif ($deliveryForm == "CIP") {
    $deliveryFormCIP = "selected";
} elseif ($deliveryForm == "DAP") {
    $deliveryFormDAP = "selected";
} elseif ($deliveryForm == "DAT") {
    $deliveryFormDAT = "selected";
} else {
    $deliveryFormEXW = "selected";
}

//further Info
$dispRoute = $row['DisposalRoute'];
$procDescr = $row['ProcessDescription'];

//radiobuttons Check wich one is choosen
$radioOnPro = "";
$radioOnAbf = "";
if ($prodAbf == "Produktstatus") {
    $radioOnPro = "checked";
} elseif ($prodAbf == "Abfall") {
    $radioOnAbf = "checked";
} else {
    $radioOnPro = "checked";
    $radioOnAbf = "";
}

$radioOnErz = "";
$radioOnHae = "";
if ($erzHae == "Erzeuger") {
    $radioOnErz = "checked";
} elseif ($erzHae == "Händler") {
    $radioOnHae = "checked";
} else {
    $radioOnErz = "checked";
    $radioOnHae = "";
}
//parameterliste verabeitung auf requestSeite
//ab hier json verarbeitung
$paramJson = json_decode($row['ParameterList']);

//Variablen Initalisieren
$unterHo = "";
$wassergehalt = "";
$aschegehalt = "";
$chlor = "";
$schwefel = "";
$quecksilber = "";
$calcium = "";
$silicium = "";
$eisen = "";
$magnesium = "";
$kalium = "";
$natrium = "";
$aluminium = "";

if (isset($paramJson)) {
    $unterHo = $paramJson[0]->value;
    $wassergehalt = $paramJson[1]->value;
    $aschegehalt = $paramJson[2]->value;
    $chlor = $paramJson[3]->value;
    $schwefel = $paramJson[4]->value;
    $quecksilber = $paramJson[5]->value;
    $calcium = $paramJson[6]->value;
    $silicium = $paramJson[7]->value;
    $eisen = $paramJson[8]->value;
    $magnesium = $paramJson[9]->value;
    $kalium = $paramJson[10]->value;
    $natrium = $paramJson[11]->value;
    $aluminium = $paramJson[12]->value;
}

$rowContent = "";
$countJsonParam = count($paramJson);


for ($i = 13; $i < $countJsonParam; $i++) {
    $rowContent .= '<div class="ing-row" id="row' . $i . '"><input style="margin-right: 9px" type="text" name="param" placeholder="Parameter" value="' . $paramJson[$i]->param . '" disabled=""/><input type="text" name="value" placeholder="Messwert"  value="' . $paramJson[$i]->value . '" autocomplete="off" required pattern="[0-9<>,]{1,}" title="Nur \'1-9\', \',\' und \'< >\'"/><select style="margin-left: 5px" name="unit" disabled=""><option selected="">' . $paramJson[$i]->units . '</option></select></div>'; // inner HTML of blank row
}

//Überprüfen ob alle Daten in DB
$contactPersCheck = "";
$contactPersCheckVar = 0;

if ($row['Firstname'] && $row['Surname'] && $row['Street'] && $row['Town'] && $row['Zip'] && $row['Phone']) {
    $contactPersCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $contactPersCheckVar = 1;
} else {
    $contactPersCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $contactPersCheckVar = 0;
}

$requestCheck = "";
$requestCheckVar = 0;

if ($row['ProdAbf']) {
    if ($row['ProdAbf'] == "Produktstatus") {
        if ($row['ErzHae']) {
            if ($row['ErzHae'] == "Erzeuger" || $row['ErzHae'] == "Händler") {
                if ($row['JaTo'] && $row['DeliveryForm']) {
                    $requestCheck = "<i class=\"far fa-check-circle green-text\"></i>";
                    $requestCheckVar = 1;
                } else {
                    $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
                    $requestCheckVar = 0;
                }
            } else {
                $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
                $requestCheckVar = 0;
            }
        }
    } else {
        if ($row['ErzHae']) {
            if ($row['ErzHae'] == "Erzeuger" || $row['ErzHae'] == "Händler") {
                if ($row['JaTo'] && $row['DeliveryForm'] && $row['WasteDescription'] && $row['Avv']) {
                    $requestCheck = "<i class=\"far fa-check-circle green-text\"></i>";
                    $requestCheckVar = 1;
                } else {
                    $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
                    $requestCheckVar = 0;
                }
            } else {
                $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
                $requestCheckVar = 0;
            }
        } else {
            $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
            $requestCheckVar = 0;
        }
    }
} else {
    $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $requestCheckVar = 0;
}


$furtherInfoCheck = "";
$furtherInfoCheckVar = 0;

if ($row['DisposalRoute'] && $row['ProcessDescription'] && $offeredPrice) {
    $furtherInfoCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $furtherInfoCheckVar = 1;
} else {
    $furtherInfoCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $furtherInfoCheckVar = 0;
}


$docOneCheck = "";
$docOneCheckVar = 0;
if ($row['ProdAbf'] == "Abfall") {
    $docOneCheckVar = 3;
} else {
    $sqlDocOne = "SELECT * FROM docOne WHERE RequestId = '$requestId'";
    $result = mysqli_query($conn, $sqlDocOne);
    $numbers = mysqli_num_rows($result);

    if ($row['ProdAbf'] == 'Abfall') {
        $docOneCheck = "<i class=\"far fa-check-circle green-text\"></i>";
        $docOneCheckVar = 1;
    } else {
        if ($numbers > 0) {
            $docOneCheck = "<i class=\"far fa-check-circle green-text\"></i>";
            $docOneCheckVar = 1;
        } else {
            $docOneCheck = "<i class=\"far fa-times-circle red-text\"></i>";
            $docOneCheckVar = 0;
        }
    }
}


//Progressbar check bei Seiten Reload
$progressBarValue = "";
$progressValue = "";
$buttonRequestFilledOut = "";

if ($row['ProdAbf'] == "Abfall") {
    $countNumbers = $contactPersCheckVar + $requestCheckVar + $furtherInfoCheckVar;
    if ($countNumbers == 3) {
        $progressBarValue = "100%";
        $progressValue = "100";
        if ($row['OpenRequest'] == 1) {
            $buttonRequestFilledOut = '<button type="submit" id="requestIsFilledOutAgain" name="requestIsFilledOutAgain" value="1" class="btn btn-outline-success waves-effect">' . $lang["requestChecklistButtonAgain"] . '</button>';
        } else {
            $buttonRequestFilledOut = '<button type="submit" id="requestIsFilledOut" name="requestIsFilledOut" value="1" class="btn btn-outline-success waves-effect">' . $lang["requestChecklistButton"] . '</button>';
        }
    } elseif ($countNumbers == 2) {
        $progressBarValue = "66%";
        $progressValue = "66";
        $buttonRequestFilledOut = '<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect ">'.$lang['requestSubmitButtonRed'].'</button>';
    } elseif ($countNumbers == 1) {
        $progressBarValue = "33%";
        $progressValue = "33";
        $buttonRequestFilledOut = '<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect ">'.$lang['requestSubmitButtonRed'].'</button>';
    } else {
        $progressBarValue = "0%";
        $progressValue = "0";
        $buttonRequestFilledOut = '<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect ">'.$lang['requestSubmitButtonRed'].'</button>';
    }
} else {
    $countNumbers = $contactPersCheckVar + $requestCheckVar + $furtherInfoCheckVar + $docOneCheckVar;
    if ($countNumbers == 4) {
        $progressBarValue = "100%";
        $progressValue = "100";
        if ($row['OpenRequest'] == 1) {
            $buttonRequestFilledOut = '<button type="submit" id="requestIsFilledOutAgain" name="requestIsFilledOutAgain" value="1" class="btn btn-outline-success waves-effect">'.$lang['requestSubmitButtonAgain'].'</button>';
        } else {
            $buttonRequestFilledOut = '<button type="submit" id="requestIsFilledOut" name="requestIsFilledOut" value="1" class="btn btn-outline-success waves-effect">'.$lang['requestSubmitButton'].'</button>';
        }
    } elseif ($countNumbers == 3) {
        $progressBarValue = "75%";
        $progressValue = "75";
        $buttonRequestFilledOut = '<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect ">'.$lang['requestSubmitButtonRed'].'</button>';
    } elseif ($countNumbers == 2) {
        $progressBarValue = "50%";
        $progressValue = "50";
        $buttonRequestFilledOut = '<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect ">'.$lang['requestSubmitButtonRed'].'</button>';
    } elseif ($countNumbers == 1) {
        $progressBarValue = "25%";
        $progressValue = "25";
        $buttonRequestFilledOut = '<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect ">'.$lang['requestSubmitButtonRed'].'</button>';
    } else {
        $progressBarValue = "0%";
        $progressValue = "0";
        $buttonRequestFilledOut = '<button type="button" id="requestIsNotFilledOut" name="requestIsNotFilledOut" value="1" class="btn btn-outline-danger waves-effect ">'.$lang['requestSubmitButtonRed'].'</button>';
    }
}

//Fileupload
if (isset($_FILES['attachments'])) {

    //erstelle Folder für User id
    $folderUser = "uploads/" . $userId . "/";

    //erstelle Folder für Request
    $folder = "uploads/" . $userId . "/" . $requestId . "/";

    //Prüfen ob Ordner für User vorhanden ist
    if (!file_exists($folderUser)) {
        mkdir($folderUser);
    }
    //Prüfen ob Ordner für Request vorhanden ist
    if (!file_exists($folder)) {
        mkdir($folder);
    }

    $msg = "";
    $targetFile = $folder . basename($_FILES['attachments']['name'][0]);
    if (file_exists($targetFile)) {
        $msg = "Dokument existiert schon!";
        $status = 0;
        $path = "";
    } elseif (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile)) {
        $msg = "Dokument wurde hochgeladen";
        $status = 1;
        $path = $targetFile;
        //$msg = array("status" => 1, "msg" => "Dokument wurde hochgeladen", "path" => $targetFile);
        //Insert Path of the File into db DocOne
        $sql_insert_Doc = "INSERT INTO docOne SET Path = '$targetFile', UserId = '$userId', RequestId = '$requestId', DocType = 'docs'";
        $result = mysqli_query($conn, $sql_insert_Doc);
    }

    //Neue FileId aus Datenbank ziehen für Ajax operationen
    $sqlSelectUploadedId = "SELECT id FROM docOne ORDER BY id DESC LIMIT 1";
    $stmt = mysqli_query($conn, $sqlSelectUploadedId);
    $row = mysqli_fetch_array($stmt);
    $newFileId = $row['id'];

    $jsonArray = array(
        'msg' => $msg,
        'status' => $status,
        'path' => $path,
        'newFileId' => $newFileId,
    );
    exit(json_encode($jsonArray));
}
//Fileupload 2
if (isset($_FILES['attachmentsFurtherDocs'])) {

    //erstelle Folder für User id
    $folderUser = "uploads/" . $userId . "/";

    //erstelle Folder für Request
    $folder = "uploads/" . $userId . "/" . $requestId . "/";

    //Prüfen ob Ordner für User vorhanden ist
    if (!file_exists($folderUser)) {
        mkdir($folderUser);
    }
    //Prüfen ob Ordner für Request vorhanden ist
    if (!file_exists($folder)) {
        mkdir($folder);
    }

    $msg = "";
    $targetFile = $folder . basename($_FILES['attachmentsFurtherDocs']['name'][0]);
    if (file_exists($targetFile)) {
        $msg = "Dokument existiert schon!";
        $status = 0;
        $path = "";
    } elseif (move_uploaded_file($_FILES['attachmentsFurtherDocs']['tmp_name'][0], $targetFile)) {
        $msg = "Dokument wurde hochgeladen";
        $status = 1;
        $path = $targetFile;
        //$msg = array("status" => 1, "msg" => "Dokument wurde hochgeladen", "path" => $targetFile);
        //Insert Path of the File into db DocOne
        $sql_insert_Doc = "INSERT INTO docOne SET Path = '$targetFile', UserId = '$userId', RequestId = '$requestId', DocType = 'furtherDocs'";
        $result = mysqli_query($conn, $sql_insert_Doc);
    }

    //Neue FileId aus Datenbank ziehen für Ajax operationen
    $sqlSelectUploadedId = "SELECT id FROM docOne ORDER BY id DESC LIMIT 1";
    $stmt = mysqli_query($conn, $sqlSelectUploadedId);
    $row = mysqli_fetch_array($stmt);
    $newFileId = $row['id'];

    $jsonArray = array(
        'msg' => $msg,
        'status' => $status,
        'path' => $path,
        'newFileId' => $newFileId,
    );
    exit(json_encode($jsonArray));
}
//show Files
function showFiles($conn, $requestId, $userId, $docType)
{
    $sql_show_files = "SELECT * FROM docOne WHERE RequestId = $requestId AND DocType = '$docType'";
    $statement_show_fiels = mysqli_query($conn, $sql_show_files);
    while ($rowPath = mysqli_fetch_array($statement_show_fiels)) {
        $filePath = $rowPath['Path'];
        $fileName = explode("uploads/$userId/$requestId", $filePath);
        $fileId = $rowPath['id'];
        $requestId = $rowPath['RequestId'];
        //untescheidung zwischen PDF oder bild
        //voschaubild
        //mit a href link
        if (stristr($filePath, '.pdf') == true) {
            $icon = "pdfIcon.png";
        } else {
            $icon = "imgIcon.png";
        }

        echo '
        <div id="' . $fileId . '">
            <div class="view overlay hm-green-slight">
                <figure><a href="' . $filePath . '" target="_blank"><img style="width: 100%" src="assets/images/' . $icon . '"></a>
                    <div class="mask flex-center">
                        <a type="button" href="' . $filePath . '" target="_blank" class="showButton">Anzeigen</a>
                    </div>
                </figure>
            </div>
            <div style="text-align: center">
            <br>
                <p>' . $fileName[1] . '</p>  
                <input type="hidden" name="deleteFileId" value="' . $fileId . '">
                <button type="button" id="' . $fileId . '" name="delete" class="btn btn-outline-danger waves-effect delete">
                <i class="far fa-trash-alt"></i>
                </button>
            </div>  
        </div>
        ';
    }
}

?>





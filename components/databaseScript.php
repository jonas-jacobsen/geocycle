<?php

//check ob Daten vorhanden sind
$sql_all = "SELECT * FROM userdata WHERE id = $_SESSION[userId]";
$statement = mysqli_query($conn, $sql_all);

$row = mysqli_fetch_array($statement);

//userdata
$userId = $row['id'];
//ansprechpartner
$firstname = $row['Firstname'];
$surname = $row['Surname'];
$phone = $row['Phone'];
$street = $row['Street'];
$town = $row['Town'];
$zip = $row['Zip'];
//Anfrage
$prodAbf = $row['ProdAbf'];
$erzHae = $row['ErzHae'];
$jato = $row['JaTo'];
$producer = $row['Producer'];
$wasteDescription = $row['WasteDescription'];
$avv = $row['Avv'];
$deliveryForm = $row['DeliveryForm'];
//further Info
$dispRoute = $row['DisposalRoute'];
$procDescr = $row['ProcessDescription'];

//radiobuttons Check Db
$radioOnPro = "";
$radioOnAbf = "";
if ($prodAbf == "Produktstatus") {
    $radioOnPro = "checked";
} elseif ($prodAbf == "Abfall") {
    $radioOnAbf = "checked";
} else {
    $radioOnPro = "";
    $radioOnAbf = "";
}

$radioOnErz = "";
$radioOnHae = "";
if ($erzHae == "Erzeuger") {
    $radioOnErz = "checked";
} elseif ($erzHae == "Händler") {
    $radioOnHae = "checked";
} else {
    $radioOnErz = "";
    $radioOnHae = "";
}

//Überprüfen ob alle Daten in DB
$contactPersCheck = "";
$contactPersCheckVar = 0;

if ($row['Firstname'] && $row['Surname'] && $row['Street'] && $row['Town'] && $row['Zip']) {
    $contactPersCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $contactPersCheckVar = 1;
} else {
    $contactPersCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $contactPersCheckVar = 0;
}

$requestCheck = "";
$requestCheckVar = 0;

if ($row['ProdAbf'] && $row['ErzHae'] && $row['JaTo'] && $row['Producer'] && $row['WasteDescription'] && $row['Avv'] && $row['DeliveryForm']) {
    $requestCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $requestCheckVar = 1;
} else {
    $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $requestCheckVar = 0;
}

$furtherInfoCheck = "";
$furtherInfoCheckVar = 0;

if ($row['DisposalRoute'] && $row['ProcessDescription']) {
    $furtherInfoCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $furtherInfoCheckVar = 1;
} else {
    $furtherInfoCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $furtherInfoCheckVar = 0;
}

$docOneCheck = "";
$docOneCheckVar = 0;

$sqlDocOne = "SELECT * FROM docOne WHERE UserId = $userId";
$result = mysqli_query($conn, $sqlDocOne);
$numbers = mysqli_num_rows($result);
if ($numbers > 0) {
    $docOneCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $docOneCheckVar = 1;
} else {
    $docOneCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $docOneCheckVar = 0;
}

//Progressbar check bei Seiten Reload
$progressBarValue = "";
$progressValue = "";
$countNumbers = $contactPersCheckVar + $requestCheckVar + $furtherInfoCheckVar + $docOneCheckVar;
if ($countNumbers == 4) {
    $progressBarValue = "100%";
    $progressValue = "100";
} elseif ($countNumbers == 3) {
    $progressBarValue = "75%";
    $progressValue = "75";
} elseif ($countNumbers == 2) {
    $progressBarValue = "50%";
    $progressValue = "50";
} elseif ($countNumbers == 1) {
    $progressBarValue = "25%";
    $progressValue = "25";
} else {
    $progressBarValue = "0%";
    $progressValue = "0";
}

/*
if ($contactPersCheckVar == 1 && $requestCheckVar == 1) {

} elseif ($contactPersCheckVar == 0 && $requestCheckVar == 1 || $contactPersCheckVar == 1 && $requestCheckVar == 0) {
    $progressBarValue = "50%";
    $progressValue = "50";
} else {
    $progressBarValue = "0%";
    $progressValue = "0";
}
*/


//Fileupload
if (isset($_FILES['attachments'])) {
    $folder = "uploads/" . $userId . "/";
    if (!file_exists($folder)) {
        mkdir($folder);
    }

    $msg = "";
    $targetFile = $folder . basename($_FILES['attachments']['name'][0]);
    if (file_exists($targetFile)) {
        $msg = "Dokument existiert schon!";
        $status = 0;
        $path = "";
        //$msg = array("status" => 0, "msg" => "Dokument existiert schon!");
    } elseif (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile)) {
        $msg = "Dokument wurde hochgeladen";
        $status = 1;
        $path = $targetFile;
        //$msg = array("status" => 1, "msg" => "Dokument wurde hochgeladen", "path" => $targetFile);
        //Insert Path of the File into db DocOne
        $sql_insert_Doc = "INSERT INTO docOne SET Path = '$targetFile', UserId = '$userId'";
        $result = mysqli_query($conn, $sql_insert_Doc);
    }
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
function showFiles($conn, $userId)
{
    $sql_show_files = "SELECT * FROM docOne WHERE UserId = $userId";
    $statement_show_fiels = mysqli_query($conn, $sql_show_files);
    while ($rowPath = mysqli_fetch_array($statement_show_fiels)) {
        $filePath = $rowPath['Path'];
        $fileName = explode("uploads/$userId/", $filePath);
        $fileId = $rowPath['id'];
        //untescheidung zwischen PDF oder bild
        //voschaubild
        //mit a href link
        if (stristr($filePath, '.pdf') == true) {
            $icon = "pdfIcon.png";
        } else {
            $icon = "imgIcon.png";
        }

        echo '
        <div>
            <div class="view overlay hm-green-slight">
                <figure><a href="' . $filePath . '" target="_blank"><img style="width: 100%" src="assets/images/' . $icon . '"></a>
                    <div class="mask flex-center">
                        <p class="white-text">
                            <a href="' . $filePath . '" target="_blank">Anzeigen </a>
                        </p>
                    </div>
                </figure>
            </div>
            <div style="text-align: center">
                <p>' . $fileName[1] . '</p>
                <form id="deleteFileForm">
                    <input type="hidden" name="deleteFileId" value="' . $fileId . '">
                    <button type="submit" id="deleteFile" class="btn btn-outline-danger waves-effect">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </form>
            </div>  
        </div>
        ';
    }
}


?>
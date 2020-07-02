<?php
include("components/script/email.php");
//TeamDatatables
$allocation = "";
$teamname = "";
$getAllocation = $_SESSION['teamAllocation'];
if ($getAllocation == 2) {
    $allocation = 1;
} elseif ($getAllocation == 3) {
    $allocation = 2;
} elseif ($getAllocation == 4) {
    $allocation = 3;
}
$teamname = $allocation;

function showAllRequestForTeam($conn, $allocation)
{
    $sqlAllRequest = "SELECT * FROM userdata WHERE OpenRequest = 1 AND Allocation = $allocation AND AdminWorkInProgress = 1";
    $stmtAllRequest = mysqli_query($conn, $sqlAllRequest);
    while ($dataAllRequest = mysqli_fetch_array($stmtAllRequest)) {
        $userId = $dataAllRequest['userId'];
        $requestId = $dataAllRequest['id'];
        $name = $dataAllRequest['Surname'];
        $town = $dataAllRequest['Town'];
        $weight = $dataAllRequest['JaTo'];
        $avv = $dataAllRequest['Avv'];
        $deliveryForm = $dataAllRequest['DeliveryForm'];
        $producer = $dataAllRequest['Producer'];
        $adminWorkInprogress = $dataAllRequest['AdminWorkInProgress'];
        //Unternehmensname aus userdb holen
        $sqlCompany = "SELECT Company FROM user WHERE id = '$userId'";
        $stmtCompany= mysqli_query($conn, $sqlCompany);
        $rowCompany= mysqli_fetch_array($stmtCompany);
        $company = $rowCompany['Company'];
        //überprüfen welchen Status die Anfrage hat: In arbeit: Weiß, angenommen: Grün, Abgelehnt: Rot
        $backgroudstyle = "";
        if ($adminWorkInprogress == 2) {
            $backgroudstyle = "angenommen";
        } elseif ($adminWorkInprogress == 3) {
            $backgroudstyle = "abgelehnt";
        } else {
            $backgroudstyle = "";
        }
        echo '
        <tr>
            <td>' . $requestId . '</td>
            <td>' . $company . '</td>
            <td>' . $town . '</td>
            <td>' . $weight . '</td>
            <td>' . $avv . '</td>
            <td>' . $deliveryForm . '</td>
            <td>' . $producer . '</td>
            <td>
                <form id="shoeAll' . $requestId . '" method="get" action="selectedRequestTeam.php">
                    <input type="hidden" name="selectedRequest" value="' . $requestId . '">
                    <button type="submit" id="btnShowAll' . $requestId . '" class="btn btn-light-green">Anzeigen</button>
                </form>
            </td>
        </tr>';
    }
}

function showAllAcceptedRequestForTeam($conn, $allocation)
{
    $sqlAllRequest = "SELECT * FROM userdata WHERE OpenRequest = 1 AND Allocation = $allocation AND AdminWorkInProgress = 2";
    $stmtAllRequest = mysqli_query($conn, $sqlAllRequest);
    while ($dataAllRequest = mysqli_fetch_array($stmtAllRequest)) {
        $userId = $dataAllRequest['userId'];
        $requestId = $dataAllRequest['id'];
        $name = $dataAllRequest['Surname'];
        $town = $dataAllRequest['Town'];
        $weight = $dataAllRequest['JaTo'];
        $avv = $dataAllRequest['Avv'];
        $deliveryForm = $dataAllRequest['DeliveryForm'];
        $producer = $dataAllRequest['Producer'];
        $adminWorkInprogress = $dataAllRequest['AdminWorkInProgress'];
        //Unternehmensname aus userdb holen
        $sqlCompany = "SELECT Company FROM user WHERE id = '$userId'";
        $stmtCompany= mysqli_query($conn, $sqlCompany);
        $rowCompany= mysqli_fetch_array($stmtCompany);
        $company = $rowCompany['Company'];
        //überprüfen welchen Status die Anfrage hat: In arbeit: Weiß, angenommen: Grün, Abgelehnt: Rot
        $backgroudstyle = "";
        if ($adminWorkInprogress == 2) {
            $backgroudstyle = "angenommen";
        } elseif ($adminWorkInprogress == 3) {
            $backgroudstyle = "abgelehnt";
        } else {
            $backgroudstyle = "";
        }
        echo '
        <tr class="' . $backgroudstyle . '">
            <td>' . $requestId . '</td>
            <td>' . $company . '</td>
            <td>' . $town . '</td>
            <td>' . $weight . '</td>
            <td>' . $avv . '</td>
            <td>' . $deliveryForm . '</td>
            <td>' . $producer . '</td>
            <td>
                <form id="shoeAll' . $requestId . '" method="get" action="selectedRequestTeam.php">
                    <input type="hidden" name="selectedRequest" value="' . $requestId . '">
                    <button type="submit" id="btnShowAll' . $requestId . '" class="btn btn-light-green">Anzeigen</button>
                </form>
            </td>
        </tr>';
    }
}

//Formular verarbeiten
$errorShow = "";
if (isset($_POST['buttonSubmit'])) {
    $requestId = $_POST['requestId'];
    $textfield = $_POST['textfield'];

    //liste für csv mit neuen werten vorbereiten
    $listForCSV = $_POST['listForCSV'];
    //[]zeichen aus dem String löschen
    $listForCSV = str_replace('[', '', $listForCSV);
    $listForCSV = str_replace(']', '', $listForCSV);
    //String in Array umwandeln
    $listForCSV = explode(",", $listForCSV);

    //user email aus DB holen
    $sqlUserId = "SELECT userId AS id FROM userdata WHERE id = $requestId";
    $stmtUserId = mysqli_query($conn, $sqlUserId);
    $rowUserId = mysqli_fetch_array($stmtUserId);
    $userIdEmail = $rowUserId['id'];
    $sqlUserEmail = "SELECT Email AS Email FROM user WHERE id = $userIdEmail";
    $stmtUserEmail = mysqli_query($conn, $sqlUserEmail);
    $rowUserEmail = mysqli_fetch_array($stmtUserEmail);
    $userEmail = $rowUserEmail['Email'];

    if ($_POST['buttonSubmit'] == 1) {
        $sql = "UPDATE userdata SET AdminWorkInProgress = 2, CompletedRequestDate = CURRENT_DATE WHERE id = '$requestId'";
        $stmt = mysqli_query($conn, $sql);
        $errorShow = "<div class=\"alert alert-success msg mt-5 \" role=\"alert\">
                              Die Anfrage wurde erfolgreich angenommen!
                            </div>";
        //an das Array für die ML-CSV, die 1 für angenommen anhängen
        $listForCSV[18] = "a";
        addDataToCSV($listForCSV);
        //antwortemail an User senden
        sendMailToUser($userEmail, "info@geocycle.com", "angenommen", $textfield);
    } elseif ($_POST['buttonSubmit'] == 0) {
        $sql = "UPDATE userdata SET AdminWorkInProgress = 3, CompletedRequestDate = CURRENT_DATE WHERE id = '$requestId'";
        $stmt = mysqli_query($conn, $sql);
        $errorShow = "<div class=\"alert alert-danger msg mt-5\" role=\"alert\">
                              Die Anfrage wurde abgelehnt!
                            </div>";
        //an das Array für die ML-CSV, die 0 für abgelehnt anhängen
        $listForCSV[18] = "b";
        addDataToCSV($listForCSV);
        //antwortemail an User senden
        sendMailToUser($userEmail, "info@geocycle.com", "abgelehnt", $textfield);
    } else {

    }
}

function addDataToCSV($listForCSV)
{
    //funktion zum Hinzufügen von Daten zur csv
    function readcsv($filename)
    {
        $rows = array();
        foreach (file($filename, FILE_IGNORE_NEW_LINES) as $line) {
            $rows[] = str_getcsv($line);
        }
        return $rows;
    }
    //funktion zum Schreiben von neuen Daten in die vorhandene csv
    function writecsv($filename, $rows)
    {
        $file = fopen($filename, 'w');
        foreach ($rows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }

    $link = $_SERVER['DOCUMENT_ROOT'] . '/Projekte/geocycle/components/script/analyse/datasetsML/data.csv';

    $list = readcsv($link);
    //Array mit neuen Values
    $list[] = array("rohBrenn" => $listForCSV[0], "wasser" => $listForCSV[1], "asche" => $listForCSV[2], "chlor" => $listForCSV[3], "schwefel" => $listForCSV[4], "queck" => $listForCSV[5], "calcium" => $listForCSV[6], "silizium" => $listForCSV[7], "eisen"=> $listForCSV[8], "aluminium" => $listForCSV[9], "kalium" => $listForCSV[10], "magnesium" => $listForCSV[11], "natrium" => $listForCSV[12], "rohMainParam" => $listForCSV[13], "menge" => $listForCSV[14], "preis" => $listForCSV[15], "abfPro" => $listForCSV[16], "avvZert" => $listForCSV[17], "Lables" => $listForCSV[18]);

    //Add funktion aufrufen
    writecsv($link, $list);
    //END funktion zum Hinzufügen von Daten zur csv
}

?>
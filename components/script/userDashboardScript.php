<?php
$userId = $_SESSION['userId'];
$msgModalSendRequest = "";

//Anfrage wurde abgesendet, wird von Offen in abgeshlossen umgewandelt
if (isset($_POST['requestIsFilledOut'])) {
    if ($_POST['requestIsFilledOut'] == 1) {
        //datum abfragen
        $requestIdFilledOut = $_POST['requestId'];
        $sqlChangeFromOpenToClose = "UPDATE userdata SET OpenRequest = 1, AdminWorkInProgress = 1, Allocation = 0, IncomingRequestDate = CURRENT_DATE WHERE id = $requestIdFilledOut";
        mysqli_query($conn, $sqlChangeFromOpenToClose);
        $msgModalSendRequest = '<div class="alert alert-success msg mt-5" role="alert">
                              Deine Anfrage mit der Id ' . $requestIdFilledOut . ' wurde erfolgreich abgeschickt!
                            </div>';
    }
}


//check ob das erste mal angemeldet
$sqlIsNew = "SELECT isNew FROM user WHERE id = '$userId'";
$stmtIsNew = mysqli_query($conn, $sqlIsNew);
$rowIsnew = mysqli_fetch_array($stmtIsNew);

$modalShow = $rowIsnew['isNew'];

if ($modalShow == 0) {
    $sqlChangeCheckIfNew = "UPDATE user SET isNew = 1 WHERE id = $userId";
    mysqli_query($conn, $sqlChangeCheckIfNew);
} else {

}
//Button neue Anfrage
if (isset($_POST['newRequest'])) {
    $sqlNewRequest = "INSERT INTO userdata SET UserId = $userId, OpenRequest = 0";
    $stmtNewRequest = mysqli_query($conn, $sqlNewRequest);
    $sqlNewRequestId = "SELECT id FROM userdata WHERE userId = $userId ORDER BY id DESC LIMIT 1";
    $stmtNewRequestId = mysqli_query($conn, $sqlNewRequestId);
    $rowNewRequestId = mysqli_fetch_array($stmtNewRequestId);
    $requestId = $rowNewRequestId['id'];
    $_SESSION['requestId'] = $requestId;
    header('Location: request.php');
}

function showOpenRequest($conn, $userId)
{
    $sqlOpenRequest = "SELECT * FROM userdata WHERE userId = $userId && OpenRequest = 0";
    $stmtOpenRequest = mysqli_query($conn, $sqlOpenRequest);
    //text falls keine Anfragen vorhanden sind
    if (mysqli_num_rows($stmtOpenRequest) < 1) {
        echo "<p>Keine Anfragen vorhanden</p>";
    } else {
        echo '
    <div class="table-responsive">
        <table id="dtBasicExample" class="table" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Anfrage ID
                    </th>
                    <th class="th-sm">Name
                    </th>
                    <th class="th-sm">Ort
                    </th>
                    <th class="th-sm">Menge
                    </th>
                    <th class="th-sm">AVV
                    </th>
                    <th class="th-sm">Anlieferform
                    </th>
                    <th class="th-sm">Erzeuger
                    </th>
                    <th class="th-sm">
                    </th>
                </tr>
            </thead>
        <tbody>
    ';
        while ($dataOpenRequest = mysqli_fetch_array($stmtOpenRequest)) {
            $requestId = $dataOpenRequest['id'];
            $name = $dataOpenRequest['Surname'];
            $town = $dataOpenRequest['Town'];
            $weight = $dataOpenRequest['JaTo'];
            $avv = $dataOpenRequest['Avv'];
            $deliveryForm = $dataOpenRequest['DeliveryForm'];
            $producer = $dataOpenRequest['Producer'];

            echo '
                <tr>
                    <td>' . $requestId . '</td>
                    <td>' . $name . '</td>
                    <td>' . $town . '</td>
                    <td>' . $weight . '</td>
                    <td>' . $avv . '</td>
                    <td>' . $deliveryForm . '</td>
                    <td>' . $producer . '</td>
                    <td>
                        <form id="1" method="post" action="request.php">
                            <input type="hidden" name="requestId" value="' . $requestId . '">
                            <button type="submit" id="submitOne" name="submitOne" value="0" class="btn btn-light-green">Anzeigen</button>
                        </form>
                    </td>
                </tr>';
        }
        echo '
            </tbody>
        </table>
    </div>';
    }
}

function showCloseRequest($conn, $userId)
{
    $sqlCloseRequest = "SELECT * FROM userdata WHERE userId = $userId && OpenRequest = 1";
    $stmtCloseRequest = mysqli_query($conn, $sqlCloseRequest);

    //text falls keine Anfragen vorhanden sind
    if (mysqli_num_rows($stmtCloseRequest) < 1) {
        echo "<p>Keine Abgeschlossen Anfragen vorhanden</p>";
    } else {
        echo '
    <div class="table-responsive">
        <table id="dtBasicExample" class="table" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Anfrage ID
                    </th>
                    <th class="th-sm">Name
                    </th>
                    <th class="th-sm">Ort
                    </th>
                    <th class="th-sm">Menge
                    </th>
                    <th class="th-sm">AVV
                    </th>
                    <th class="th-sm">Anlieferform
                    </th>
                    <th class="th-sm">Erzeuger
                    </th>
                    <th class="th-sm">
                    </th>
                </tr>
            </thead>
        <tbody>
    ';

        while ($dataCloseRequest = mysqli_fetch_array($stmtCloseRequest)) {
            $requestId = $dataCloseRequest['id'];
            $name = $dataCloseRequest['Surname'];
            $town = $dataCloseRequest['Town'];
            $weight = $dataCloseRequest['JaTo'];
            $avv = $dataCloseRequest['Avv'];
            $deliveryForm = $dataCloseRequest['DeliveryForm'];
            $producer = $dataCloseRequest['Producer'];

            echo '
                <tr>
                    <td>' . $requestId . '</td>
                    <td>' . $name . '</td>
                    <td>' . $town . '</td>
                    <td>' . $weight . '</td>
                    <td>' . $avv . '</td>
                    <td>' . $deliveryForm . '</td>
                    <td>' . $producer . '</td>
                    <td>
                        <form id="1" method="post" action="request.php">
                            <input type="hidden" name="requestId" value="' . $requestId . '">
                            <button type="submit" id="submitOne" name="submitOne" value="0" class="btn btn-light-green">Anzeigen</button>
                        </form>
                    </td>
                </tr>';
        }
        echo '
            </tbody>
        </table>
    </div>';
    }
}
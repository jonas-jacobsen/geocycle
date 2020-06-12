<?php

include("components/script/email.php");

$userId = $_SESSION['userId'];
$msgModalSendRequest = "";

//Anfrage wurde abgesendet, wird von Offen in abgeshlossen umgewandelt
//hier noch prüfen ob es sich um eine erneute, abgewandelte Anfrage handelt (Vorhandene Daten aus anderer Anfrage)
$requestIsFilledOutAgain = $_POST['requestIsFilledOutAgain'];
if($requestIsFilledOutAgain == "1"){
    $sqlRequestAgain = "INSERT INTO userdata SET UserId = $userId, OpenRequest = 0";
    mysqli_query($conn, $sqlRequestAgain);
} else if (isset($_POST['requestIsFilledOut'])) {
    if ($_POST['requestIsFilledOut'] == 1) {

        //komplet neue Anfrage
        $requestIdFilledOut = $_POST['requestId'];
        $sqlChangeFromOpenToClose = "UPDATE userdata SET OpenRequest = 1, AdminWorkInProgress = 1, Allocation = 0, IncomingRequestDate = CURRENT_DATE WHERE id = $requestIdFilledOut";
        mysqli_query($conn, $sqlChangeFromOpenToClose);
        $msgModalSendRequest = '<div class="alert alert-success msg mt-5" role="alert">
                              Deine Anfrage mit der Id ' . $requestIdFilledOut . ' wurde erfolgreich abgeschickt!
                            </div>';

        sendMailToAdmin("jonas.jacobsen1992@hotmail.de", "geocycle@adminpanel.de");
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

function showOpenRequest($conn, $userId, $lang)
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
                    <th class="th-sm">'.$lang["userTableRequestId"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestName"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestTown"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestAmount"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestAVV"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestDeliveryForm"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestProducer"].'
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
                            <button type="submit" id="submitOne" name="submitOne" value="0" class="btn btn-light-green">'.$lang['userDashboardViewRequest'].'</button>
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

function showCloseRequest($conn, $userId, $lang)
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
                    <th class="th-sm">'.$lang["userTableRequestId"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestName"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestTown"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestAmount"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestAVV"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestDeliveryForm"].'
                    </th>
                    <th class="th-sm">'.$lang["userTableRequestProducer"].'
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
            $adminWorkInprogress = $dataCloseRequest['AdminWorkInProgress'];

            //überprüfen welchen Status die Anfrage hat: In arbeit: Weiß, Angenommen: Grün, Abgelehnt: Rot
            $backgroudstyle = "";
            if ($adminWorkInprogress == 2){
                $backgroudstyle = "angenommen";
            } elseif ($adminWorkInprogress == 3){
                $backgroudstyle = "abgelehnt";
            }else{
                $backgroudstyle = "";
            }
            echo '
                <tr class="'.$backgroudstyle.'">
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
                            <button type="submit" id="submitOne" name="submitOne" value="0" class="btn btn-light-green">'.$lang['userDashboardViewRequest'].'</button>
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
<?php
function showNewRequest($conn, $lang)
{

    $sqlAllRequest = "SELECT * FROM userdata WHERE OpenRequest = 1 AND Allocation = 0";
    $stmtAllRequest = mysqli_query($conn, $sqlAllRequest);
    while ($dataAllRequest = mysqli_fetch_array($stmtAllRequest)) {
        $requestId = $dataAllRequest['id'];
        $name = $dataAllRequest['Surname'];
        $town = $dataAllRequest['Town'];
        $weight = $dataAllRequest['JaTo'];
        $avv = $dataAllRequest['Avv'];
        $deliveryForm = $dataAllRequest['DeliveryForm'];
        $requestDate = $dataAllRequest['IncomingRequestDate'];
        $producer = $dataAllRequest['Producer'];
        echo '
        <tr id="rowWithId' . $requestId . '">
            <td>' . $requestId . '</td>
            <td>' . $name . '</td>
            <td>' . $town . '</td>
            <td>' . $weight . '</td>
            <td>' . $avv . '</td>
            <td>' . $deliveryForm . '</td>
            <td>' . $requestDate . '</td>
            <td>
                <div style="width: 100px; text-align: center">
                    <button class="buttonChangeCategory" id="' . $requestId . '" value="1" type="button">1</button>
                    <button class="buttonChangeCategory" id="' . $requestId . '" value="2" type="button">2</button>
                    <button class="buttonChangeCategory" id="' . $requestId . '" value="3" type="button">3</button>
                </div>
            </td>
            <td>
                <form id="' . $requestId . '" method="get" action="selectedRequest.php">
                    <input type="hidden" name="selectedRequest" value="' . $requestId . '">
                    <button type="submit" id="btn' . $requestId . '" class="btn btn-light-green">Anzeigen</button>
                </form>
            </td>
        </tr>';
    }
}

function showAllRequest($conn)
{
    $sqlAllRequest = "SELECT * FROM userdata WHERE OpenRequest = 1";
    $stmtAllRequest = mysqli_query($conn, $sqlAllRequest);
    while ($dataAllRequest = mysqli_fetch_array($stmtAllRequest)) {
        $requestId = $dataAllRequest['id'];
        $name = $dataAllRequest['Surname'];
        $town = $dataAllRequest['Town'];
        $weight = $dataAllRequest['JaTo'];
        $avv = $dataAllRequest['Avv'];
        $deliveryForm = $dataAllRequest['DeliveryForm'];
        $requestDate = $dataAllRequest['IncomingRequestDate'];
        $allocation = $dataAllRequest['Allocation'];
        $adminWorkInprogress = $dataAllRequest['AdminWorkInProgress'];
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
        <tr class="rowId' . $requestId . ' ' . $backgroudstyle . '">
            <td>' . $requestId . '</td>
            <td>' . $name . '</td>
            <td>' . $town . '</td>
            <td>' . $weight . '</td>
            <td>' . $avv . '</td>
            <td>' . $requestDate . '</td>
            <td id="allocationValue' . $requestId . '">' . $allocation . '</td>
            <td>
                <form id="shoeAll' . $requestId . '" method="get" action="selectedRequest.php">
                    <input type="hidden" name="selectedRequest" value="' . $requestId . '">
                    <button type="submit" id="btnShowAll' . $requestId . '" class="btn btn-light-green">Anzeigen</button>
                </form>
            </td>
        </tr>';
    }
}

function showAllTeammembers($conn)
{
    $teamAllocation = 0;
    $sqlAllMembers = "SELECT * FROM adminuser";
    $stmtAllMembers = mysqli_query($conn, $sqlAllMembers);
    while ($dataAllMembers = mysqli_fetch_array($stmtAllMembers)) {
        $id = $dataAllMembers['id'];
        $email = $dataAllMembers['Email'];
        $allocation = $dataAllMembers['TeamAllocation'];
        if($allocation == 1){
            $teamAllocation = 0;
        } elseif ($allocation == 2){
            $teamAllocation = 1;
        } elseif ($allocation == 3){
            $teamAllocation = 2;
        } elseif ($allocation == 4){
            $teamAllocation = 3;
        }
        echo '
        <tr class="' . $id . '">
            <td>' . $id . '</td>
            <td>' . $email . '</td>
            <td>' . $teamAllocation . '</td>
            <td>
                <button type="submit" id="delete' . $id . '" class="btn btn-light-red">Löschen</button>
            </td>
        </tr>';
    }
}

function showSecCode($conn){
    $sqlSecCode = "SELECT SecCode AS SecCode FROM adminuser WHERE TeamAllocation = 1";
    $stmtSecCode = mysqli_query($conn, $sqlSecCode);
    $row = mysqli_fetch_array($stmtSecCode);
    echo $secCode = $row['SecCode'];
}

if(isset($_POST['changeSecCode'])){
    $secCode =  $_POST['secCode'];
    $sqlChangeSecCode = "UPDATE adminuser SET SecCode = '$secCode' WHERE TeamAllocation = 1";
    mysqli_query($conn, $sqlChangeSecCode);
}

?>
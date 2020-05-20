<?php
function showNewRequest($conn) {
    $sqlAllRequest = "SELECT * FROM userdata WHERE OpenRequest = 1 AND Allocation = 0";
    $stmtAllRequest = mysqli_query($conn, $sqlAllRequest);
    while($dataAllRequest = mysqli_fetch_array($stmtAllRequest)){
        $requestId = $dataAllRequest['id'];
        $name = $dataAllRequest['Surname'];
        $town = $dataAllRequest['Town'];
        $weight = $dataAllRequest['JaTo'];
        $avv = $dataAllRequest['Avv'];
        $deliveryForm = $dataAllRequest['DeliveryForm'];
        $requestDate = $dataAllRequest['IncomingRequestDate'];
        $producer = $dataAllRequest['Producer'];
        echo '
        <tr id="rowWithId'.$requestId.'">
            <td>'.$requestId.'</td>
            <td>'.$name.'</td>
            <td>'.$town.'</td>
            <td>'.$weight.'</td>
            <td>'.$avv.'</td>
            <td>'.$deliveryForm.'</td>
            <td>'.$requestDate.'</td>
            <td>
                <div style="width: 100px; text-align: center">
                    <button class="buttonChangeCategory" id="'.$requestId.'" value="1" type="button">1</button>
                    <button class="buttonChangeCategory" id="'.$requestId.'" value="2" type="button">2</button>
                    <button class="buttonChangeCategory" id="'.$requestId.'" value="3" type="button">3</button>
                </div>
            </td>
            <td>
                <form id="'.$requestId.'" method="get" action="selectedRequest.php">
                    <input type="hidden" name="selectedRequest" value="'.$requestId.'">
                    <button type="submit" id="btn'.$requestId.'" class="btn btn-light-green">Anzeigen</button>
                </form>
            </td>
        </tr>';
    }
}

function showAllRequest($conn) {
    $sqlAllRequest = "SELECT * FROM userdata WHERE OpenRequest = 1";
    $stmtAllRequest = mysqli_query($conn, $sqlAllRequest);
    while($dataAllRequest = mysqli_fetch_array($stmtAllRequest)){
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
        if ($adminWorkInprogress == 2){
            $backgroudstyle = "angenommen";
        } elseif ($adminWorkInprogress == 3){
            $backgroudstyle = "abgelehnt";
        }else{
            $backgroudstyle = "";
        }
        echo '
        <tr class="'.$backgroudstyle.'">
            <td>'.$requestId.'</td>
            <td>'.$name.'</td>
            <td>'.$town.'</td>
            <td>'.$weight.'</td>
            <td>'.$avv.'</td>
            <td>'.$requestDate.'</td>
            <td id="allocationValue'.$requestId.'">'.$allocation.'</td>
            <td>
                <form id="shoeAll'.$requestId.'" method="get" action="selectedRequest.php">
                    <input type="hidden" name="selectedRequest" value="'.$requestId.'">
                    <button type="submit" id="btnShowAll'.$requestId.'" class="btn btn-light-green">Anzeigen</button>
                </form>
            </td>
        </tr>';
    }
}
?>
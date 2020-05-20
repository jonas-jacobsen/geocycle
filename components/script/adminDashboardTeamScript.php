<?php
//TeamDatatables
$allocation = "";
$teamname = "";
$getAllocation = $_SESSION['teamAllocation'];
if($getAllocation == 2){
    $allocation = 1;
}elseif ($getAllocation == 3){
    $allocation = 2;
}elseif ($getAllocation == 4){
    $allocation = 3;
}
$teamname = $allocation;

function showAllRequestForTeam($conn, $allocation) {
    $sqlAllRequest = "SELECT * FROM userdata WHERE OpenRequest = 1 AND Allocation = $allocation AND AdminWorkInProgress = 1";
    $stmtAllRequest = mysqli_query($conn, $sqlAllRequest);
    while($dataAllRequest = mysqli_fetch_array($stmtAllRequest)){
        $requestId = $dataAllRequest['id'];
        $name = $dataAllRequest['Surname'];
        $town = $dataAllRequest['Town'];
        $weight = $dataAllRequest['JaTo'];
        $avv = $dataAllRequest['Avv'];
        $deliveryForm = $dataAllRequest['DeliveryForm'];
        $producer = $dataAllRequest['Producer'];
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
            <td>'.$deliveryForm.'</td>
            <td>'.$producer.'</td>
            <td>
                <form id="shoeAll'.$requestId.'" method="get" action="selectedRequestTeam.php">
                    <input type="hidden" name="selectedRequest" value="'.$requestId.'">
                    <button type="submit" id="btnShowAll'.$requestId.'" class="btn btn-light-green">Anzeigen</button>
                </form>
            </td>
        </tr>';
    }
}

function showAllAcceptedRequestForTeam($conn, $allocation) {
    $sqlAllRequest = "SELECT * FROM userdata WHERE OpenRequest = 1 AND Allocation = $allocation AND AdminWorkInProgress = 2";
    $stmtAllRequest = mysqli_query($conn, $sqlAllRequest);
    while($dataAllRequest = mysqli_fetch_array($stmtAllRequest)){
        $requestId = $dataAllRequest['id'];
        $name = $dataAllRequest['Surname'];
        $town = $dataAllRequest['Town'];
        $weight = $dataAllRequest['JaTo'];
        $avv = $dataAllRequest['Avv'];
        $deliveryForm = $dataAllRequest['DeliveryForm'];
        $producer = $dataAllRequest['Producer'];
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
            <td>'.$deliveryForm.'</td>
            <td>'.$producer.'</td>
            <td>
                <form id="shoeAll'.$requestId.'" method="get" action="selectedRequestTeam.php">
                    <input type="hidden" name="selectedRequest" value="'.$requestId.'">
                    <button type="submit" id="btnShowAll'.$requestId.'" class="btn btn-light-green">Anzeigen</button>
                </form>
            </td>
        </tr>';
    }
}

//Formular verarbeiten
$errorShow = "";
if(isset($_POST['buttonSubmit'])){
    $requestId = $_POST['requestId'];
    if($_POST['buttonSubmit'] == 1){
        $sql = "UPDATE userdata SET AdminWorkInProgress = 2 WHERE id = '$requestId'";
        $stmt = mysqli_query($conn, $sql);
        $errorShow = "<div class=\"alert alert-success msg mt-5 \" role=\"alert\">
                              Die Anfrage wurde erfolgreich angenommen!
                            </div>";
    }if($_POST['buttonSubmit'] == 0){
        $sql = "UPDATE userdata SET AdminWorkInProgress = 3 WHERE id = '$requestId'";
        $stmt = mysqli_query($conn, $sql);
        $errorShow = "<div class=\"alert alert-danger msg mt-5\" role=\"alert\">
                              Die Anfrage wurde abgelehnt!
                            </div>";
    }else {
    }
}

?>
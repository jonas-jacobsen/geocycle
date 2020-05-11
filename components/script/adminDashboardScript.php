<?php

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
        $producer = $dataAllRequest['Producer'];
        echo '
        <tr>
            <td>'.$requestId.'</td>
            <td>'.$name.'</td>
            <td>'.$town.'</td>
            <td>'.$weight.'</td>
            <td>'.$avv.'</td>
            <td>'.$deliveryForm.'</td>
            <td>'.$producer.'</td>
            <td>
                <form id="'.$requestId.'" method="post" action="selectedRequest.php">
                    <input type="hidden" name="selectedRequest" value="'.$requestId.'">
                    <button type="submit" id="btn'.$requestId.'" name="submitOne" value="0" class="btn btn-light-green">Anzeigen</button>
                </form>
            </td>
        </tr>';
    }
}
?>
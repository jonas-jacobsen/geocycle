<?php
session_start();
include("../config.php");

$prodAbf = htmlspecialchars($_POST['prodAbf']);
$erzHae = htmlspecialchars($_POST['erzHae']);
$jato = htmlspecialchars($_POST['jato']);
$weightForm = htmlspecialchars($_POST['weightForm']);
$producer = htmlspecialchars($_POST['producer']);
$wasteDescription = htmlspecialchars($_POST['wasteDescription']);
$avv = htmlspecialchars($_POST['avv']);
$deliveryForm = htmlspecialchars($_POST['deliveryForm']);

$userId = $_SESSION['userId'];
$requestId = $_SESSION['requestId'];

$sql = "UPDATE userdata SET ProdAbf = '$prodAbf', ErzHae = '$erzHae', Jato = '$jato', WeightForm = '$weightForm',Producer = '$producer', WasteDescription = '$wasteDescription', Avv = '$avv', DeliveryForm = '$deliveryForm' WHERE id = $requestId";

$statement = mysqli_query($conn, $sql);


$sql_all = "SELECT * FROM userdata WHERE id = $requestId";
$statement = mysqli_query($conn, $sql_all);
$data = mysqli_fetch_array($statement);

//überprüfen ob dokumente vorhanden

 $sqlFiles = "SELECT * FROM docOne WHERE RequestId = $requestId";
$stmtFiles = mysqli_query($conn, $sqlFiles);

if(mysqli_num_rows($stmtFiles) < 1){
    $docOneheckVar = 0;
}else{
    $docOneheckVar = 1;
}


if ($data['ProdAbf']) {
    if ($data['ProdAbf'] == "Produktstatus") {
        if ($data['ErzHae']) {
            if ($data['ErzHae'] == "Erzeuger") {
                if ($data['JaTo'] && $data['DeliveryForm'] || $data['ErzHae'] == "Händler") {
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
        $docOneheckVar = 3;
        if ($data['ErzHae']) {
            if ($data['ErzHae'] == "Erzeuger" || $data['ErzHae'] == "Händler") {
                if ($data['JaTo'] && $data['DeliveryForm'] && $data['WasteDescription'] && $data['Avv']) {
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
        }else {
            $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
            $requestCheckVar = 0;
        }
    }
}else{
    $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $requestCheckVar = 0;
}

$jsonArray = array(
    'requestCheck' => $requestCheck,
    'requestCheckVar' => $requestCheckVar,
    'docOneCheckVar' => $docOneheckVar,
);

exit(json_encode($jsonArray));


?>
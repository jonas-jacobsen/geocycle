<?php
session_start();
include("../config.php");

$prodAbf = htmlspecialchars($_POST['prodAbf']);
$erzHae = htmlspecialchars($_POST['erzHae']);
$jato = htmlspecialchars($_POST['jato']);
$producer = htmlspecialchars($_POST['producer']);
$wasteDescription = htmlspecialchars($_POST['wasteDescription']);
$avv = htmlspecialchars($_POST['avv']);
$deliveryForm = htmlspecialchars($_POST['deliveryForm']);

$userId = $_SESSION['userId'];
$requestId = $_SESSION['requestId'];

$sql = "UPDATE userdata SET ProdAbf = '$prodAbf', ErzHae = '$erzHae', Jato = '$jato', Producer = '$producer', WasteDescription = '$wasteDescription', Avv = '$avv', DeliveryForm = '$deliveryForm' WHERE id = $requestId";

$statement = mysqli_query($conn, $sql);


$sql_all = "SELECT * FROM userdata WHERE id = $requestId";
$statement = mysqli_query($conn, $sql_all);

$data = mysqli_fetch_array($statement);
if ($data['ProdAbf'] && $data['ErzHae'] && $data['JaTo'] && $data['Producer'] && $data['WasteDescription'] && $data['Avv'] && $data['DeliveryForm']) {
    $requestCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $requestCheckVar = 1;
} else {
    $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $requestCheckVar = 0;
}

$jsonArray = array(
    'requestCheck' => $requestCheck,
    'requestCheckVar' => $requestCheckVar,
);

exit(json_encode($jsonArray));


?>
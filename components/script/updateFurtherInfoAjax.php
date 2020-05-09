<?php
session_start();
include("../config.php");

$dispRoute = htmlspecialchars($_POST['dispRoute']);
$procDescr = htmlspecialchars($_POST['procDescr']);

$userId = $_SESSION['userId'];
$requestId = $_SESSION['requestId'];

$sql = "UPDATE userdata SET DisposalRoute = '$dispRoute', ProcessDescription = '$procDescr' WHERE id = $requestId";

$statement = mysqli_query($conn, $sql);


$sql_all = "SELECT * FROM userdata WHERE id = $requestId";
$statement = mysqli_query($conn, $sql_all);

//Hier später noch überprüen ob File hochgeladen

$data = mysqli_fetch_array($statement);
if ($data['DisposalRoute'] && $data['ProcessDescription']) {
    $furtherInfoCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $furtherInfoCheckVar = 1;
} else {
    $furtherInfoCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $furtherInfoCheckVar = 0;
}

$jsonArray = array(
    'furtherInfoCheck' => $furtherInfoCheck,
    'furtherInfoCheckVar' => $furtherInfoCheckVar,
);

exit(json_encode($jsonArray));


?>
<?php
include("../config.php");
session_start();
$allocation = $_SESSION['teamAllocation']-1;
$sql = "SELECT * FROM userdata WHERE Allocation = $allocation AND AdminWorkInProgress = 1";
$stmt = mysqli_query($conn, $sql);

$jsonArray = array();
while($row = mysqli_fetch_array($stmt)){
    $i += 1;
    $arrayJson = array(
        'id' => $i,
        'requestId' => $row['id'],
        'name' => $row['Surname'],
        'town' => $row['Town'],
        'weight' => $row['JaTo'],
        'avv' => $row['Avv'],
        'deliveryForm' => $row['DeliveryForm'],
        'incomingRequestDate' => $row['IncomingRequestDate'],
        'producer' => $row['Producer'],
    );
    array_push($jsonArray, $arrayJson);
}
exit(json_encode($jsonArray));
?>


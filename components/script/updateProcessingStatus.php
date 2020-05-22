<?php
include("../config.php");
$sql = "SELECT id, AdminWorkInProgress FROM userdata WHERE AdminWorkInProgress = 2 OR AdminWorkInProgress = 3 ";
$stmt = mysqli_query($conn, $sql);

$jsonArray = array();
while($row = mysqli_fetch_array($stmt)){
    $i += 1;
    $arrayJson = array(
        'id' => $i,
        'requestId' => $row['id'],
        'status' => $row['AdminWorkInProgress'],
    );
    array_push($jsonArray, $arrayJson);
}

exit(json_encode($jsonArray));
?>
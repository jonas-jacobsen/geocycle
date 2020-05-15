<?php
session_start();
include("../config.php");

$id = $_POST['id'];
$value = $_POST['value'];

//file Pfad herausfinden
$sqlChangeAllocation = "UPDATE userdata SET Allocation = $value WHERE id = $id";
$statement = mysqli_query($conn, $sqlChangeAllocation);
$row = mysqli_fetch_array($statement);

$jsonArray = array(
    'requestNb' => $id,
    'neuerWert' => $value,
);
exit(json_encode($jsonArray));
?>
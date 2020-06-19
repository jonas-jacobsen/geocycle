<?php
include("email.php");

session_start();
include("../config.php");

$id = $_POST['id'];
$value = $_POST['value'];

//file Pfad herausfinden
$sqlChangeAllocation = "UPDATE userdata SET Allocation = $value WHERE id = $id";
$statement = mysqli_query($conn, $sqlChangeAllocation);
$row = mysqli_fetch_array($statement);


//Emailadresse von Verantworlichen Herausfinden
$sqlSelectResponsible = "SELECT email AS emailaddress FROM adminuser WHERE TeamAllocation = $value";
$statement = mysqli_query($conn, $sqlSelectResponsible);
$rowResp = mysqli_fetch_array($statement);
$emailResp = $rowResp['emailaddress'];
//Email versenden an verantwortlichen
sendMailToTeamAdmin($emailResp, 'admin@geocycle.de');

$jsonArray = array(
    'requestNb' => $id,
    'neuerWert' => $value,
);

exit(json_encode($jsonArray));
?>
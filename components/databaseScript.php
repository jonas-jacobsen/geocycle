<?php
//check ob Daten vorhanden sind
$sql_all = "SELECT * FROM userdata WHERE id = $_SESSION[userId]";
$statement = mysqli_query($conn, $sql_all);

$row = mysqli_fetch_array($statement);

//userdata
//ansprechpartner
$firstname = $row['Firstname'];
$surname = $row['Surname'];
$phone = $row['Phone'];
$street = $row['Street'];
$town = $row['Town'];
$zip = $row['Zip'];
//Anfrage
$prodAbf = $row['ProdAbf'];
$erzHae = $row['ErzHae'];
$jato = $row['JaTo'];
$producer = $row['Producer'];
$wasteDescription = $row['WasteDescription'];
$avv = $row['Avv'];
$deliveryForm = $row['DeliveryForm'];

//radiobuttons Check Db
if ($prodAbf == "Produktstatus") {
    $radioOnPro = "checked";
} elseif ($prodAbf == "Abfall") {
    $radioOnAbf = "checked";
} else {
    $radioOnPro = "";
    $radioOnAbf = "";
}

if ($erzHae == "Erzeuger") {
    $radioOnErz = "checked";
} elseif ($erzHae == "Händler") {
    $radioOnHae = "checked";
} else {
    $radioOnErz = "";
    $radioOnHae = "";
}

//Überprüfen ob alle Daten in DB
if ($row['Firstname'] && $row['Surname'] && $row['Street'] && $row['Town'] && $row['Zip']) {
    $contactPersCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $contactPersCheckVar = 1;
} else {
    $contactPersCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $contactPersCheckVar = 0;
}

if ($row['ProdAbf'] && $row['ErzHae'] && $row['JaTo'] && $row['Producer'] && $row['WasteDescription'] && $row['Avv'] && $row['DeliveryForm']) {
    $requestCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $requestCheckVar = 1;
} else {
    $requestCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $requestCheckVar = 0;
}

//Progressbar check bei Seiten Reload
if ($contactPersCheckVar == 1 && $requestCheckVar == 1) {
    $progressBarValue = "100%";
    $progressValue = "100";
} elseif ($contactPersCheckVar == 0 && $requestCheckVar == 1 || $contactPersCheckVar == 1 && $requestCheckVar == 0) {
    $progressBarValue = "50%";
    $progressValue = "50";
} else {
    $progressBarValue = "0%";
    $progressValue = "0";
}

?>
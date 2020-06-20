<?php

//count OpenRequest
$sqlCountOpenRequest = "SELECT COUNT(*) as TotalCountOpen FROM userdata WHERE AdminWorkInProgress = 1";
$stmtCountOpenRequest = mysqli_query($conn, $sqlCountOpenRequest);
$rowCountOpenRequest = mysqli_fetch_array($stmtCountOpenRequest);

//count User
$sqlCountOpenUser = "SELECT COUNT(*) as TotalCountUser FROM user";
$stmtCountOpenUser = mysqli_query($conn, $sqlCountOpenUser);
$rowCountOpenUser = mysqli_fetch_array($stmtCountOpenUser);

//count accepted Request
$sqlCountAcceptedRequest = "SELECT COUNT(*) as TotalAcceptedRequest FROM userdata WHERE AdminWorkInProgress = 2";
$stmtCountAcceptedRequest  = mysqli_query($conn, $sqlCountAcceptedRequest);
$rowCountAcceptedRequest  = mysqli_fetch_array($stmtCountAcceptedRequest);

//count all Request
$sqlCountAllRequest = "SELECT COUNT(*) as TotalRequest FROM userdata WHERE OpenRequest = 1";
$stmtCountAllRequest  = mysqli_query($conn, $sqlCountAllRequest);
$rowCountAllRequest  = mysqli_fetch_array($stmtCountAllRequest);
$acceptedPercent = intval($rowCountAcceptedRequest['TotalAcceptedRequest']/$rowCountAllRequest['TotalRequest']*100);

//PiechartAVV
$sqlAllAVV = "SELECT AVV AS avv FROM userdata";
$stmtAllAVV  = mysqli_query($conn, $sqlAllAVV);
$rowAllAVV = mysqli_fetch_array($stmtAllAVV);
$avvPiechartLables = '';
while ($rowAllAVV = mysqli_fetch_array($stmtAllAVV)) {
    if($rowAllAVV['avv'] != ""){
        $avvPiechartLables.= '"'.$rowAllAVV["avv"].'",';
    }else{}
}
$avvPiechartLables;

//Chart Roh-Brenn
//Produktstatus
$sqlCountProd = 'SELECT COUNT(*) AS TotalProd FROM userdata WHERE ProdAbf = "Produktstatus" AND OpenRequest = 1';
$stmtCountProd  = mysqli_query($conn, $sqlCountProd);
$rowCountProd  = mysqli_fetch_array($stmtCountProd);
$totalProd = intval($rowCountProd['TotalProd']);
//Abfallstatus
$sqlCountAbf = 'SELECT COUNT(*) AS TotalProd FROM userdata WHERE ProdAbf = "Abfall" AND OpenRequest = 1';
$stmtCountAbf = mysqli_query($conn, $sqlCountAbf);
$rowCountAbf = mysqli_fetch_array($stmtCountAbf);
$totalAbf = intval($rowCountAbf['TotalProd']);

?>
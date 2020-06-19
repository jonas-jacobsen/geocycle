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
?>
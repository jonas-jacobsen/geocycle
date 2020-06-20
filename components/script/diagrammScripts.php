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
$sqlAllAVV = "SELECT Avv, COUNT(*) AS count FROM userdata WHERE AVV != '' GROUP BY Avv";
$stmtAllAVV  = mysqli_query($conn, $sqlAllAVV);
$rowAllAVV = mysqli_fetch_array($stmtAllAVV);
$avvPiechartLables = '';
$avvPiechartData = NULL;
while ($rowAllAVV = mysqli_fetch_array($stmtAllAVV)) {
        $avvPiechartLables.= '"'.$rowAllAVV["Avv"].'",';
        $avvPiechartData .= $rowAllAVV["count"]."," ;
}
echo $avvPiechartData;

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


//anzahl Anfragen der jeweiligen Monate
$sqlMonthJan = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 1";
$stmtCountJan = mysqli_query($conn, $sqlMonthJan);
$rowCountJan  = mysqli_fetch_array($stmtCountJan);
$totalJan = intval($rowCountJan['TotalCount']);

$sqlMonthFeb = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 2";
$stmtCountFeb = mysqli_query($conn, $sqlMonthFeb);
$rowCountFeb  = mysqli_fetch_array($stmtCountFeb);
$totalFeb = intval($rowCountFeb['TotalCount']);

$sqlMonthMae = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 3";
$stmtCountMae = mysqli_query($conn, $sqlMonthMae);
$rowCountMae  = mysqli_fetch_array($stmtCountMae);
$totalMae = intval($rowCountMae['TotalCount']);

$sqlMonthApr = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 4";
$stmtCountApr = mysqli_query($conn, $sqlMonthApr);
$rowCountApr  = mysqli_fetch_array($stmtCountApr);
$totalApr = intval($rowCountApr['TotalCount']);

$sqlMonthMai = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 5";
$stmtCountMai = mysqli_query($conn, $sqlMonthMai);
$rowCountMai  = mysqli_fetch_array($stmtCountMai);
$totalMai = intval($rowCountMai['TotalCount']);

$sqlMonthJun = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 6";
$stmtCountJun = mysqli_query($conn, $sqlMonthJun);
$rowCountJun  = mysqli_fetch_array($stmtCountJun);
$totalJun = intval($rowCountJun['TotalCount']);

$sqlMonthJul = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 7";
$stmtCountJul = mysqli_query($conn, $sqlMonthJul);
$rowCountJul  = mysqli_fetch_array($stmtCountJul);
$totalJul = intval($rowCountJul['TotalCount']);

$sqlMonthAug = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 8";
$stmtCountAug = mysqli_query($conn, $sqlMonthAug);
$rowCountAug  = mysqli_fetch_array($stmtCountAug);
$totalAug = intval($rowCountAug['TotalCount']);

$sqlMonthSep = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 9";
$stmtCountSep = mysqli_query($conn, $sqlMonthSep);
$rowCountSep  = mysqli_fetch_array($stmtCountSep);
$totalSep = intval($rowCountSep['TotalCount']);

$sqlMonthOkt = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 10";
$stmtCountJan = mysqli_query($conn, $sqlMonthJan);
$rowCountJan  = mysqli_fetch_array($stmtCountJan);
$totalOkt = intval($rowCountProd['TotalCount']);

$sqlMonthNov = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 11";
$stmtCountNov = mysqli_query($conn, $sqlMonthNov);
$rowCountNov  = mysqli_fetch_array($stmtCountNov);
$totalNov = intval($rowCountNov['TotalCount']);

$sqlMonthDec = "SELECT COUNT(*) AS TotalCount FROM userdata WHERE MONTH(IncomingRequestDate) = 12";
$stmtCountDec = mysqli_query($conn, $sqlMonthDec);
$rowCountDec  = mysqli_fetch_array($stmtCountDec);
$totalDec = intval($rowCountDec['TotalCount']);

?>
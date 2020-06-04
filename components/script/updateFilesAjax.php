<?php
session_start();
include("../config.php");

$id = $_POST['id'];
$userId = $_SESSION['userId'];

//file Pfad herausfinden
$sql_all = "SELECT * FROM docOne WHERE UserId = $userId && id = $id";
$statement = mysqli_query($conn, $sql_all);
$row = mysqli_fetch_array($statement);
$delRequestId = $row['RequestId'];
//File Löschen
$filePath = $row['Path'];
unlink("../../".$filePath);

//path aus DB löscjen
$sql = "DELETE FROM docOne WHERE id = $id";
$statement = mysqli_query($conn, $sql);

//anzahl der noch vorhanden dokumente herausfinden
$sql_all_rows = "SELECT * FROM docOne WHERE UserId = $userId AND RequestId = $delRequestId";
$stmt = mysqli_query($conn, $sql_all_rows);
$count = mysqli_num_rows($stmt);
if ($count> 0){
    $fileUploadCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $fileUploadCheckVar = 1;
} else {
    $fileUploadCheck =  "<i class=\"far fa-times-circle red-text\"></i>";
    $fileUploadCheckVar = 0;
    //$deletePath = "../../uploads/".$userId."/".$_SESSION["requestId"];
    //rmdir($deletePath);
}
$jsonArray = array(
    'fileUploadCheck' => $fileUploadCheck,
    'fileUploadCheckVar' => $fileUploadCheckVar,
    'fileId' => $id,
);
exit(json_encode($jsonArray));


?>
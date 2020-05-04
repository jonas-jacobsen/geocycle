<?php
session_start();
include("config.php");

$fileId = $_POST['deleteFileId'];
$userId = $_SESSION['userId'];

//file Pfad herausfinden
$sql_all = "SELECT * FROM docOne WHERE UserId = $userId && id = $fileId";
$statement = mysqli_query($conn, $sql_all);
$row = mysqli_fetch_array($statement);

$filePath = $row['Path'];
unlink("../".$filePath);


$sql = "DELETE FROM docOne WHERE id = $fileId";
$statement = mysqli_query($conn, $sql);


$jsonArray = array(
    'requestCheck' => $requestCheck,
    'requestCheckVar' => $requestCheckVar,
);
exit(json_encode($jsonArray));


?>
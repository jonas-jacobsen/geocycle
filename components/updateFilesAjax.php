<?php
session_start();
include("config.php");

$id = $_POST['id'];
if(isset($_POST['id'])){

}
$userId = $_SESSION['userId'];

//file Pfad herausfinden
$sql_all = "SELECT * FROM docOne WHERE UserId = $userId && id = $id";
$statement = mysqli_query($conn, $sql_all);
$row = mysqli_fetch_array($statement);

//File Löschen
$filePath = $row['Path'];
unlink("../".$filePath);

//path aus DB löscjen
$sql = "DELETE FROM docOne WHERE id = $id";
$statement = mysqli_query($conn, $sql);

//anzahl der noch vorhanden dokumente herausfinden
$sql_all_rows = "SELECT * FROM docOne WHERE UserId = $userId";
$stmt = mysqli_query($conn, $sql_all_rows);
$count = mysqli_num_rows($stmt);
if ($count> 0){
    $mitt =  "mehr als null vorhanden";
} else {
    $mitt =  "kein Dokument mehr vohanden";
}

$jsonArray = array(
    'mit' => $mitt,
    'fileId' => $id,
);
exit($id);


?>
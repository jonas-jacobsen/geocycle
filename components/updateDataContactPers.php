<?php
session_start();
include("config.php");

if (isset($_POST['firstname']) && isset($_POST['surname'])) {
    $firstname = htmlspecialchars($_POST['firstname']);
    $surname = htmlspecialchars($_POST['surname']);
    $street = htmlspecialchars($_POST['street']);
    $town = htmlspecialchars($_POST['town']);
    $zip = htmlspecialchars($_POST['zip']);
    $userId = $_SESSION['userId'];

    $sql = "UPDATE userdata  SET Firstname = '$firstname', Surname = '$surname', Street = '$street', Town = '$town', Zip = '$zip' WHERE id = $userId";

    $statement = mysqli_query($conn, $sql);


    $sql_all = "SELECT * FROM userdata WHERE id = $userId";
    $statement = mysqli_query($conn, $sql_all);

    $data = mysqli_fetch_array($statement);
    if ($data['Firstname'] && $data['Surname'] && $data['Street'] && $data['Town'] && $data['Zip']) {
        $contactPersCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    } else {
        $contactPersCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    }

    $jsonArray = array(
        'contactPersCheck' => $contactPersCheck,
    );
    exit(json_encode($jsonArray));
}

?>
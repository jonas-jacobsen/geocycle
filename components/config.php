<?php
date_default_timezone_set('Europe/Berlin');

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//DB-Connect
$host_name = 'db5000208389.hosting-data.io';
$database = 'dbs203272';
$user_name = 'dbu162926';
$password = 'It-Projekt2019';
$conn = mysqli_connect($host_name, $user_name, $password, $database);


//language
if(!isset($_SESSION['lang'])){
    $_SESSION['lang'] = "de";
}else if (isset($_SESSION['lang']) && $_SESSION['lang'] != $_GET['lang'] && !empty($_GET['lang'])){
    if ($_GET['lang'] == "de"){
        $_SESSION['lang'] = "de";
    }else if($_GET['lang']== "en"){
        $_SESSION['lang'] = "en";
    }
}
require_once $_SERVER['DOCUMENT_ROOT']."/Projekte/geocycle/languages/".$_SESSION['lang'].".php";

/*
if (mysqli_connect_errno()) {
    die('<p>Verbindung zum MySQL Server fehlgeschlagen: '.mysqli_connect_error().'</p>');
} else {
    echo '<p>Verbindung zum MySQL Server erfolgreich aufgebaut.</p >';
}

?>*/

?>
<?php
date_default_timezone_set('Europe/Berlin');

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$host_name = 'db5000208389.hosting-data.io';
$database = 'dbs203272';
$user_name = 'dbu162926';
$password = 'It-Projekt2019';

$conn = mysqli_connect($host_name, $user_name, $password, $database);

/*
if (mysqli_connect_errno()) {
    die('<p>Verbindung zum MySQL Server fehlgeschlagen: '.mysqli_connect_error().'</p>');
} else {
    echo '<p>Verbindung zum MySQL Server erfolgreich aufgebaut.</p >';
}

?>*/

?>
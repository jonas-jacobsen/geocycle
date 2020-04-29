<?php
date_default_timezone_set('Europe/Berlin');

ini_set("error_reporting",E_ALL);
Ini_set("display_errors", 0);
error_reporting(E_ALL);

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
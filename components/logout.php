<?php
session_start();
session_destroy();
if(isset($_SESSION['adminId'])){
    header('Location: ../loginteam.php');
}else{
    header('Location: ../index.php');
}
?>
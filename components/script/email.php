<?php
use phpmailer\PHPMailer\PHPMailer;
use phpmailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function sendMailToAdmin($to, $from){
    $mail = new PHPMailer();
    $mail ->setFrom($from);
    $mail ->addAddress($to);
    $mail ->Subject = 'Neue Anfrage';
    $mail ->Body = '<h2>Neue Anfrage</h2><p>Sie haben eine neue Anfrage erhalten. Um sich die Anfrage anzeigen zu lassen &ouml;ffnen Sie bitte das Adminpanel: <a href="https://come-prima.de/Projekte/geocycle/admindashboard.php">Hier klicken</a></p>';
    $mail ->isHTML(true);
    return $mail -> send();
}

function sendMailToTeamAdmin($to, $from){
    $mail = new PHPMailer();
    $mail ->setFrom($from);
    $mail ->addAddress($to);
    $mail ->Subject = 'Neue Anfrage';
    $mail ->Body = '<h2>Neue Anfrage zugeteilt</h2><p>Es wurde Ihnen vom Admin eine neue Anfrage zugeordnet. Um sich die Anfrage anzeigen zu lassen &ouml;ffnen Sie bitte das Adminpanel: <a href="https://come-prima.de/Projekte/geocycle/admindashboardteam.php">Hier klicken</a></p>';
    $mail ->isHTML(true);
    return $mail -> send();
}

function sendMailToTeamUser($to, $from, $msg, $textfield){
    $mail = new PHPMailer();
    $mail ->setFrom($from);
    $mail ->addAddress($to);
    $mail ->Subject = 'Ihre Anfrage bei uns';
    $mail ->Body = '<h2>Status&auml;nderung </h2><p>Der Status deiner Anfrage hat sich ge&auml;ndert</p><p>Ihre Anfrage wurde '.$msg.'<br><h4>Grund:</h4>'.$textfield.'</p>';
    $mail ->isHTML(true);
    return $mail -> send();
}
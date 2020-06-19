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

function sendMailToUser($to, $from, $msg, $textfield){
    $mail = new PHPMailer();
    $mail ->setFrom($from);
    $mail ->addAddress($to);
    $mail ->Subject = 'Ihre Anfrage bei uns';
    $mail ->Body = '<h2>Status&auml;nderung </h2><p>Der Status deiner Anfrage hat sich ge&auml;ndert</p><p>Ihre Anfrage wurde '.$msg.'<br><h4>Grund:</h4>'.$textfield.'</p>';
    $mail ->isHTML(true);
    return $mail -> send();
}

function sendSecCodeToNewMember($to, $secCode){
    $mail = new PHPMailer();
    $mail ->CharSet = 'UTF-8';
    $mail ->setFrom("admin@geocycle.com");
    $mail ->addAddress($to);
    $mail ->Subject = 'Geocycle - Einladungslink';
    $mail ->Body = "<body><h2>Einladung</h2><p>Hallo, Sie wurden authoriesiert sich auf dem Geocycle-Anfrageportal anzumelden.</p><p>Bitte geben Sie auf der <a href='https://www.come-prima.de/projekte/geocycle/loginTeam.php'> Regristrieungsseite</a> folgenden Code ein:</p><p>Securitycode: <em>$secCode</em></p><p>Bitte benutzen Sie bei der registrierung die Geocycle Email und wählen Sie ein sicheres Passwort.</p><p>Viele Grüße <br>Ihr Admin</p></body>";
    $mail ->isHTML(true);
    return $mail -> send();
}
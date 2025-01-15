<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webmail";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function getMailInstance() {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'mail.votre-domaine.fr';
    $mail->SMTPAuth = true;
    $mail->Username = 'votre-mail-d-envoie';
    $mail->Password = 'votre-mot-de-passe';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    return $mail;
}


function sendVerificationEmail($email, $token) {
    $mail = getMailInstance();
    $subject = "Vérification de votre email";
    $message = "Cliquez sur ce lien pour vérifier votre email: http://localhost/webmail/controllers/verify_email.php?token=$token";

    try {
 
        $mail->setFrom('votre-mail-d-envoie', 'nom-de-votre-site');
        $mail->addAddress($email);


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

    
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur lors de l'envoi : {$mail->ErrorInfo}");
        return false;
    }
}


function sendVerificationCode($email, $code) {
    $mail = getMailInstance();
    $subject = "Code de vérification";
    $message = "Votre code de vérification est : $code";

    try {
       
        $mail->setFrom('votre-mail-d-envoie', 'nom-de-votre-site');
        $mail->addAddress($email);


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

 
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur lors de l'envoi : {$mail->ErrorInfo}");
        return false;
    }
}
?>

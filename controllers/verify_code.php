<?php
require '../config/config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $codeEntered = $_POST['code'];
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT user_id, expires_at FROM verification_codes WHERE token = ? AND code = ?");
    if (!$stmt) {
        die("Erreur dans la préparation de la requête SELECT : " . $conn->error);
    }
    $stmt->bind_param("ss", $token, $codeEntered);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $expiresAt);
        $stmt->fetch();

    
        if (strtotime($expiresAt) < time()) {

            $stmt = $conn->prepare("UPDATE verification_codes SET expires_at = NOW() WHERE token = ?");
            if (!$stmt) {
                die("Erreur dans la préparation de la requête UPDATE : " . $conn->error);
            }
            $stmt->bind_param("s", $token);
            $stmt->execute();

            echo "Le code a expiré. Veuillez demander un nouveau code.";
        } else {

            $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
            if (!$stmt) {
                die("Erreur dans la préparation de la requête SELECT : " . $conn->error);
            }
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->bind_result($email);
            $stmt->fetch();

    
            $_SESSION['user_id'] = $userId;
            $_SESSION['email'] = $email;


            header("Location: ../views/dashboard.php");
            exit();
        }
    } else {
        echo "Code incorrect. Veuillez vérifier et essayer à nouveau.";
    }
    $stmt->close();
}
?>
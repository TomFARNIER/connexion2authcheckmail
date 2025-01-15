<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32)); 

    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    if (!$stmt) {
        die("Erreur dans la préparation de la requête INSERT : " . $conn->error);
    }
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
        $userId = $stmt->insert_id;

        $stmt = $conn->prepare("INSERT INTO verification_tokens (user_id, token, created_at, expires_at) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
        if (!$stmt) {
            die("Erreur dans la préparation de la requête INSERT : " . $conn->error);
        }
        $stmt->bind_param("is", $userId, $token);

        if ($stmt->execute()) {
            if (sendVerificationEmail($email, $token)) {
                echo "Un email de vérification a été envoyé.";
            } else {
                echo "Erreur lors de l'envoi de l'email.";
            }
        } else {
            echo "Erreur lors de l'enregistrement du token de vérification : " . $stmt->error;
        }
    } else {
        echo "Erreur lors de l'enregistrement de l'utilisateur : " . $stmt->error;
    }
    $stmt->close();
} else {
    include '../views/register_form.php';
}
?>
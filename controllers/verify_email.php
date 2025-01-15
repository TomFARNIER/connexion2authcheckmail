<?php
require '../config/config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT user_id FROM verification_tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId);
        $stmt->fetch();

        $stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        echo "Votre email a été vérifié avec succès!";
    } else {
        echo "Token invalide.";
    }
    $stmt->close();
}
?>
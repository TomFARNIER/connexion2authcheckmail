<?php
require '../config/config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparer la requête de sélection pour l'utilisateur
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("Erreur dans la préparation de la requête SELECT : " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $dbEmail, $dbPassword);
        $stmt->fetch();

     
        if (password_verify($password, $dbPassword)) {
            
            $code = rand(100000, 999999);
            $token = bin2hex(random_bytes(32)); 

            
            $stmt = $conn->prepare("INSERT INTO verification_codes (user_id, code, token, expires_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 2 MINUTE)) ON DUPLICATE KEY UPDATE code = VALUES(code), token = VALUES(token), expires_at = DATE_ADD(NOW(), INTERVAL 2 MINUTE)");
            if (!$stmt) {
                die("Erreur dans la préparation de la requête INSERT : " . $conn->error);
            }
            $stmt->bind_param("iss", $id, $code, $token);
            $stmt->execute();

           
            if (sendVerificationCode($dbEmail, $code)) {
        
                $_SESSION['verification_token'] = $token;

               
                header("Location: ../views/verify_code_form.php?token=$token");
                exit();
            } else {
                echo "Erreur lors de l'envoi du code de vérification.";
            }
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Cet email n'est pas enregistré.";
    }
    $stmt->close();
} else {
    include '../views/login_form.php';
}
?>
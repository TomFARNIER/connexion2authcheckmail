<?php
session_start();


if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    
    header("Location: login.php");
    exit();
}


$userId = $_SESSION['user_id'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Bienvenue sur votre tableau de bord</h1>
    <p>ID utilisateur : <?php echo htmlspecialchars($userId); ?></p>
    <p>Email : <?php echo htmlspecialchars($email); ?></p>
</body>
</html>
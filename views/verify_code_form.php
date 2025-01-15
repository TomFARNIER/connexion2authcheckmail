<!DOCTYPE html>
<html>
<head>
    <title>Vérification du Code</title>
</head>
<body>
    <h1>Vérification du Code</h1>
    <form method="POST" action="../controllers/verify_code.php?token=<?php echo htmlspecialchars($_GET['token']); ?>">
        Code de vérification: <input type="text" name="code" required><br>
        <button type="submit">Valider le code</button>
    </form>
</body>
</html>
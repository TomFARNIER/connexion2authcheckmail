<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="../controllers/login.php">
        Email: <input type="email" name="email" required><br>
        Mot de passe: <input type="password" name="password" required><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
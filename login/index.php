<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Masyarakat</title>
    <link rel="stylesheet" href="styleLogin.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="ProsesLogin.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="#">Registrasi</a></p>
    </div>
</body>
</html>

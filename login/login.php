<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Login Masyarakat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: sans-serif;
      background: linear-gradient(to bottom, #212529, #000000);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background: #1c1c1c;
      padding: 40px;
      border-radius: 20px;
      width: 100%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    }

    .login-container h2 {
      margin-bottom: 20px;
      color: #2ca8ff;
    }

    .form-control {
      border: none;
      border-radius: 25px;
      background: #333;
      color: #fff;
    }

    .form-control::placeholder {
      color: #ccc;
    }

    .btn-custom {
      border-radius: 25px;
      background-color: #2ca8ff;
      color: white;
      font-weight: bold;
    }

    .btn-custom:hover {
      background-color: #1b91e6;
    }

    .login-container p {
      margin-top: 20px;
      font-size: 14px;
      color: #ccc;
    }

    .login-container a {
      color: #2ca8ff;
      font-weight: bold;
      text-decoration: none;
    }

    .login-container a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <div class="login-container">
    <h2>Login</h2>
    <form action="prosesLogin.php" method="POST">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button type="submit" class="btn btn-custom w-100">Login</button>
    </form>
    <p>Belum punya akun? <a href="registrasi.php">Registrasi</a></p>
  </div>

</body>

</html>
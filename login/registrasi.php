<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Registrasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom, #000000, #212529);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: Arial, sans-serif;
    }

    .register-box {
      background-color: #1c1c1c;
      color: white;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      width: 100%;
      max-width: 400px;
    }

    .register-box h3 {
      text-align: center;
      margin-bottom: 30px;
      color: #0d6efd;
    }

    .form-control {
      border: none;
      border-radius: 20px;
      padding: 12px 15px;
      background-color: #333;
      color: #ffffff;
    }

    .form-control::placeholder {
      color: #cccccc;
    }

    .form-control:focus {
      background-color: #444;
      color: #ffffff;
      box-shadow: none;
    }

    .btn-register {
      background-color: #0d6efd;
      border: none;
      border-radius: 20px;
      padding: 10px;
      font-weight: bold;
      width: 100%;
      transition: 0.3s;
    }

    .btn-register:hover {
      background-color: #0b5ed7;
    }

    .text-link {
      text-align: center;
      margin-top: 15px;
    }

    .text-link a {
      color: #0d6efd;
      text-decoration: none;
    }

    .text-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <div class="register-box">
    <h3>Registrasi</h3>
    <form action="../login/prosesRegistrasi.php" method="POST">
      <div class="mb-3">
        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap" required>
      </div>
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="mb-3">
        <input type="text" name="telp" class="form-control" placeholder="Telepon" required>
      </div>
      <button type="submit" class="btn btn-register">Daftar</button>
    </form>
    <div class="text-link">
      Sudah punya akun? <a href="login.php">Login</a>
    </div>
  </div>

</body>

</html>
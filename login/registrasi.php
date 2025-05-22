<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Registrasi</title>
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
    position: relative;
    overflow: hidden;
  }

  .register-container {
    background: #1c1c1c;
    padding: 40px;
    border-radius: 20px;
    width: 100%;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    z-index: 1;
  }

  .register-container h3 {
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

  .form-control:focus {
    background-color: #444;
    color: #fff;
  }

  /* Autofill fix untuk Chrome agar tidak putih */
  input:-webkit-autofill,
  input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0px 1000px #333 inset !important;
    -webkit-text-fill-color: #fff !important;
    transition: background-color 5000s ease-in-out 0s;
  }

  .btn-register {
    border-radius: 25px;
    background-color: #2ca8ff;
    color: white;
    font-weight: bold;
    width: 100%;
    padding: 10px;
    border: none;
  }

  .btn-register:hover {
    background-color: #1b91e6;
  }

  .register-container p {
    margin-top: 20px;
    font-size: 14px;
    color: #ccc;
  }

  .register-container a {
    color: #2ca8ff;
    font-weight: bold;
    text-decoration: none;
  }

  .register-container a:hover {
    text-decoration: underline;
  }

  .maskot {
    position: fixed;
    left: 20px;
    bottom: 20px;
    width: 80px;
    height: auto;
    z-index: 0;
    opacity: 0.9;
    transition: all 0.3s ease;
  }

  .maskot:hover {
    opacity: 1;
    transform: scale(1.05);
  }

  @media (max-width: 576px) {
    .maskot {
      width: 60px;
      left: 10px;
      bottom: 10px;
    }
  }
</style>

</head>

<body>

  <div class="register-container">
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
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
  </div>

  <!-- Tambahkan gambar maskot di sini -->
  <img src="../dashboard/img/logoLelon.PNG" alt="Lelon" class="maskot">

</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Login Gagal</title>
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

    .error-container {
      background: #1c1c1c;
      padding: 40px;
      border-radius: 20px;
      width: 100%;
      max-width: 500px;
      text-align: center;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
      z-index: 1;
      animation: fadeIn 0.5s ease;
    }

    .error-container h2 {
      margin-bottom: 20px;
      color: #ff4d4d;
    }

    .error-message {
      color: #ff9999;
      margin-bottom: 30px;
      font-size: 18px;
    }

    .error-maskot {
      width: 150px;
      height: auto;
      margin: 20px auto;
      display: block;
      opacity: 0.8;
    }

    .btn-custom {
      border-radius: 25px;
      background-color: #2ca8ff;
      color: white;
      font-weight: bold;
      padding: 10px 25px;
      text-decoration: none;
      display: inline-block;
      margin-top: 20px;
      transition: all 0.3s ease;
    }

    .btn-custom:hover {
      background-color: #1b91e6;
      transform: translateY(-2px);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .shake {
      animation: shake 0.5s ease;
    }
  </style>
</head>

<body>
  <div class="error-container">
    <img src="../maskot/maskotExplain.png" alt="Maskot" class="error-maskot shake">
    <h2>Login Gagal...</h2>
    <div class="error-message">
      Cek kembali username dan password nya!<br>
      Jika tidak punya akun, harap Registrasi.
    </div>
    <a href="login.php" class="btn btn-custom">Kembali ke Login</a>
  </div>
</body>

</html>
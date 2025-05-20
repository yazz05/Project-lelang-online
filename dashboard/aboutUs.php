<?php
session_start();

if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Us - LeLon!</title>

  <!-- Import Google Fonts Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <style>

    .about-me-container {
      max-width: 800px;
      margin: 100px auto 150px auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgb(0 0 0 / 0.1);
    }

    .about-me-header h1 {
      font-weight: 600;
      font-size: 2.5rem;
      color: #222;
      margin-bottom: 20px;
      text-align: center;
    }

    .about-me-photo {
      display: block;
      max-width: 180px;
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 50%;
      margin: 0 auto 30px auto;
      box-shadow: 0 0 10px rgb(0 0 0 / 0.15);
    }

    .about-me-content p {
      font-weight: 400;
      font-size: 1.1rem;
      color: #555;
      line-height: 1.7;
      margin-bottom: 1.3rem;
    }

    @media (max-width: 576px) {
      .about-me-container {
        margin: 60px 15px 100px 15px;
        padding: 20px;
      }
    }
    
  </style>
</head>

<body>

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <section class="about-me-container">
    <img src="img/profile.jpg" alt="Profile Photo" class="about-me-photo" />
    <div class="about-me-header">
      <h1>About Us</h1>
    </div>
    <div class="about-me-content">
      <p>Hello! I’m <strong><?php echo htmlspecialchars($_SESSION['nama']); ?></strong>, a passionate web developer who loves crafting beautiful, functional websites.</p>
      <p>With a background in design and programming, I aim to build intuitive and user-friendly experiences. When I’m not coding, I enjoy reading, traveling, and exploring new technologies.</p>
      <p>Feel free to connect with me or explore more of my projects here on LeLon!</p>
    </div>
  </section>

  <!-- Footer -->
  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
session_start();
require '../login/koneksi.php';

if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit();
}

$id_user = $_SESSION['id_user'];

// Mark notification as read if ID is provided
if (isset($_GET['id_notif'])) {
  $id_notif = intval($_GET['id_notif']);
  
  // Verify notification belongs to user
  $stmt = $koneksi->prepare("UPDATE tb_notif SET status_baca = 'terbaca' 
                            WHERE id_notif = ? AND id_user = ?");
  $stmt->bind_param("ii", $id_notif, $id_user);
  $stmt->execute();
  $stmt->close();
  
  // Get the specific notification
  $stmt = $koneksi->prepare("SELECT * FROM tb_notif WHERE id_notif = ?");
  $stmt->bind_param("i", $id_notif);
  $stmt->execute();
  $notif = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

// Get all notifications for the user
$query = "SELECT * FROM tb_notif WHERE id_user = ? ORDER BY created_at DESC";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$all_notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesan Notifikasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  
  <div class="container mt-5 pt-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <h3 class="mb-4">Notifikasi Anda</h3>
        
        <?php if (isset($notif)): ?>
          <!-- Single Notification Detail -->
          <div class="card mb-4">
            <div class="card-header bg-light">
              <h5>Detail Notifikasi</h5>
            </div>
            <div class="card-body">
              <p><?= htmlspecialchars($notif['pesan']) ?></p>
              <small class="text-muted">
                <?= date('d-m-Y H:i', strtotime($notif['created_at'])) ?>
              </small>
            </div>
          </div>
        <?php endif; ?>
        
        <!-- All Notifications List -->
        <div class="list-group">
          <?php foreach ($all_notifications as $notification): ?>
            <a href="pesan.php?id_notif=<?= $notification['id_notif'] ?>" 
               class="list-group-item list-group-item-action <?= $notification['status_baca'] === 'belum terbaca' ? 'fw-bold' : '' ?>">
              <div class="d-flex w-100 justify-content-between">
                <p class="mb-1"><?= htmlspecialchars(substr($notification['pesan'], 0, 100)) ?></p>
                <small><?= date('d-m-Y H:i', strtotime($notification['created_at'])) ?></small>
              </div>
              <small class="text-muted">
                Status: <?= $notification['status_baca'] === 'belum terbaca' ? 'Baru' : 'Sudah dibaca' ?>
              </small>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
session_start();
session_destroy();
header("Location: ../login/login.php"); // ganti ke halaman login kamu
exit();

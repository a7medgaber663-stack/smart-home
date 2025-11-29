<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  die("❌ يجب تسجيل الدخول.");
}

$cart_id = intval($_POST['cart_id'] ?? 0);

if ($cart_id > 0) {
  // ✅ استخدم العمود id بدل cart_id
  $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
header("Location: cart.php");
exit;

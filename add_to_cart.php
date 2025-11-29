<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  die("❌ يجب تسجيل الدخول لإضافة منتجات إلى السلة.");
}

// تأكد إن البيانات جاية من POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
  $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

  if ($product_id <= 0 || $quantity <= 0) {
    die("❌ بيانات غير صالحة.");
  }

  // تحقق هل المنتج موجود بالفعل في السلة
  $check = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
  $check->bind_param("ii", $user_id, $product_id);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    // لو موجود، زود الكمية
    $update = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
    $update->bind_param("iii", $quantity, $user_id, $product_id);
    $update->execute();
    $update->close();
  } else {
    // لو مش موجود، أضفه جديد
    $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $user_id, $product_id, $quantity);
    $insert->execute();
    $insert->close();
  }

  $check->close();
  $conn->close();

  // ✅ إعادة التوجيه للصفحة الرئيسية أو السلة
  header("Location: cart.php");
  exit;
} else {
  die("❌ الطلب غير صالح.");
}

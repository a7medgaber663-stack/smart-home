<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  die("❌ يجب تسجيل الدخول لتأكيد الطلب.");
}

$user_id = $_SESSION['user_id'];

// ✅ تأكد أن البيانات جاية من POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products'])) {
    $products = $_POST['products'];

    foreach ($products as $product_id => $details) {
        $quantity = intval($details['quantity']);
        $price = floatval($details['price']);
        $total_price = $quantity * $price;

        // ✅ إدخال الطلب في جدول orders
        $stmt = $conn->prepare("
            INSERT INTO orders (user_id, product_id, quantity, total_price, status)
            VALUES (?, ?, ?, ?, 'pending')
        ");
        $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $total_price);
        $stmt->execute();
        $stmt->close();
    }

    // ✅ بعد تأكيد الطلب، امسح السلة
    $delete = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $delete->bind_param("i", $user_id);
    $delete->execute();
    $delete->close();

    $conn->close();

    // ✅ إعادة التوجيه لصفحة عرض الطلبات
    header("Location: my_orders.php");
    exit;
} else {
    die("❌ لا توجد منتجات لتأكيد الطلب.");
}

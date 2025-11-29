<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  die("<h2 style='color:red; text-align:center;'>❌ يجب تسجيل الدخول أولاً لعرض الطلبات.</h2>");
}

$user_id = $_SESSION['user_id'];

// ✅ الاستعلام لجلب الطلبات الخاصة بالمستخدم
$query = "
  SELECT o.order_id, o.quantity, o.total_price, o.status, o.order_date, p.name
  FROM orders o
  JOIN products p ON o.product_id = p.product_id
  WHERE o.user_id = ?
  ORDER BY o.order_date DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>طلباتي</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .orders-table {
      width: 85%;
      margin: 40px auto;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .orders-table th, .orders-table td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }
    .orders-table th {
      background-color: #f2f2f2;
      font-size: 18px;
    }
    .orders-table td {
      font-size: 16px;
    }
    .status-pending { color: orange; font-weight: bold; }
    .status-confirmed { color: blue; font-weight: bold; }
    .status-shipped { color: green; font-weight: bold; }
  </style>
</head>
<body>
  <h2 style="text-align:center; margin-top:30px;">📦 طلباتي</h2>
  <table class="orders-table">
    <tr>
      <th>رقم الطلب</th>
      <th>المنتج</th>
      <th>الكمية</th>
      <th>السعر الكلي</th>
      <th>الحالة</th>
      <th>تاريخ الطلب</th>
    </tr>
    <?php while($row = $result->fetch_assoc()){ ?>
      <tr>
        <td><?= $row['order_id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= number_format($row['total_price'], 2) ?> جنيه</td>
        <td class="status-<?= strtolower($row['status']) ?>">
          <?= $row['status'] ?>
        </td>
        <td><?= $row['order_date'] ?></td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>

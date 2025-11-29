<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
  die("<h2 style='color:red; text-align:center;'>❌ يجب تسجيل الدخول أولاً لعرض السلة.</h2>");
}

$user_id = $_SESSION['user_id'];

// ✅ الاستعلام لجلب بيانات السلة الخاصة بالمستخدم
$query = "
  SELECT p.name, p.price, c.quantity, c.id AS cart_id, p.product_id
  FROM cart c
  JOIN products p ON c.product_id = p.product_id
  WHERE c.user_id = $user_id
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>سلة المشتريات</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .cart-table {
      width: 80%;
      margin: 50px auto;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .cart-table th, .cart-table td {
      padding: 15px;
      border: 1px solid #ddd;
      text-align: center;
    }
    .cart-table th {
      background-color: #f2f2f2;
      font-size: 18px;
    }
    .cart-table td {
      font-size: 16px;
    }
    .confirm-btn {
      display: block;
      margin: 30px auto;
      padding: 10px 20px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 18px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h2 style="text-align:center; margin-top:30px;">🛒 سلة المشتريات</h2>
  <form method="POST" action="confirm_order.php">
    <table class="cart-table">
      <tr>
        <th>المنتج</th>
        <th>الكمية</th>
        <th>سعر الوحدة</th>
        <th>السعر الكلي</th>
        <th>حذف</th>
      </tr>
      <?php
      $total_cart = 0;
      while($row = mysqli_fetch_assoc($result)){
        $item_total = $row['price'] * $row['quantity'];
        $total_cart += $item_total;
      ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= $row['quantity'] ?></td>
          <td><?= number_format($row['price'], 2) ?> جنيه</td>
          <td><?= number_format($item_total, 2) ?> جنيه</td>
          <td>
            <form method="POST" action="remove_from_cart.php" onsubmit="return confirm('هل أنت متأكد من حذف المنتج؟');">
              <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
              <button type="submit" style="color:red;">🗑️ حذف</button>
            </form>
          </td>
        </tr>

        <!-- ✅ تمرير بيانات المنتج لتأكيد الطلب -->
        <input type="hidden" name="products[<?= $row['product_id'] ?>][quantity]" value="<?= $row['quantity'] ?>">
        <input type="hidden" name="products[<?= $row['product_id'] ?>][price]" value="<?= $row['price'] ?>">
      <?php } ?>
    </table>

    <!-- ✅ عرض إجمالي السلة -->
    <h3 style="text-align:center; margin-top:20px; color:green;">
      الإجمالي: <?= number_format($total_cart, 2) ?> جنيه
    </h3>

    <!-- ✅ زر تأكيد الطلب -->
    <button type="submit" class="confirm-btn">✅ تأكيد الطلب</button>
  </form>
</body>
</html>

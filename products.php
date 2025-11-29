<?php
require 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>المنتجات</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      direction: rtl;
      background-color: #f9f9f9;
      padding: 20px;
      text-align: center;
    }

    h1 {
      color: #333;
      margin-bottom: 30px;
    }

    .products-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center; /* علشان العناصر تبقى في النص */
    }

    .product {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      width: 300px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
      text-align: center;
      display: flex !important;
      flex-direction: column !important;
     }
     
    .product-title {
     background-color: #f1f1f1;
     padding: 8px;
     border-radius: 8px;
     margin-bottom: 10px;
     font-weight: bold;
     text-align: center;
    }

    .product:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .product h3 {
      margin-bottom: 10px;
      font-size: 18px;
      color: #333;
    }

    .product img {
      width: 100%;
      border-radius: 5px;
      margin-bottom: 10px;
    }

    .product p {
      margin: 5px 0;
      color: #555;
    }
  </style>
</head>
<body>

  <h1>قائمة المنتجات</h1>

  <div class="products-container">
    <?php
    $result = mysqli_query($conn, "
      SELECT p.name, p.price, p.image_url, c.category_name
      FROM products p
      LEFT JOIN categories c ON p.category_id = c.category_id
    ");

    while($row = mysqli_fetch_assoc($result)){
      echo "<div class='product-card'>";  // بدل product بـ product-card
  
      // 👇 الاسم فوق الصورة
      echo "<h3>".$row['name']."</h3>";
  
      if(!empty($row['image_url'])){
          echo "<img src='".$row['image_url']."' alt='".$row['name']."'>";
      }
  
      echo "<p>السعر: ".$row['price']." جنيه</p>";
      echo "<p>التصنيف: ".($row['category_name'] ?? 'غير محدد')."</p>";
  
      echo "</div>";
  }
  

    ?>
  </div>

</body>
</html>

<?php
require 'db_connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>Smart Home</title>
  <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- الهيدر المنفصل -->
    <nav class="nav_bar">
    <div class="logo-container">
      <img class="img1" src="images/Logo.jpeg" alt="logo">
      <h1>Smart Home</h1>
    </div>
    <ul class="links">
      <li><a href="index.html">الرئيسية</a></li>
      <li><a href="#products">المنتجات</a></li>
      <li><a href="contact.html">اتصل بنا</a></li>
      <li><a href="login.html">تسجيل الدخول</a></li>
      <li><a href="create_account.php">انشاء حساب</a></li>
    </ul>
  </nav>

  <!-- المحتوى الرئيسي مع الخلفية والبحث -->
  <main>
    <div class="content">
      <img class="main-bg" src="./background.jpg" alt="خلفية">
      <div class="cont">
        <h1 class="cont-h">مرحباً بكم في Smart Home</h1>
        <p class="cont-p">اكتشف أحدث منتجات المنزل الذكي التي تجعل حياتك أسهل وأكثر راحة</p>
                
          <!-- خانة البحث في الخلفية -->
          <div class="search-container">
            <input class="input" type="text" id="searchInput" placeholder="ابحث عن منتج...">
            <button class="button_search" name="btn_search">ابحث</button>
          </div>
      </div>
    </div>
  </main>

    <!-- ✅ عرض اسم المستخدم بعد تسجيل الدخول -->
    <?php
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        echo "<p style='text-align:center; font-weight:bold; color:#000000; margin-top:10px;
 font-size: 20px;'>
                مرحبًا " . htmlspecialchars($_SESSION['username']) . " 👋
              </p>";
    }
    ?>

  <!-- زر القائمة الجانبية -->
  <div class="menu-icon" onclick="toggleMenu()">☰</div>
  <div class="sidebar" id="sidebar">
    <span class="close-btn" onclick="toggleMenu()">✖</span>
    <h3>الأقسام</h3>
    <ul class="categories">
      <li><a href="#" class="filter-btn" data-category="all">الكل</a></li>
      <li><a href="#" class="filter-btn" data-category="fridge">ثلاجات</a></li>
      <li><a href="#" class="filter-btn" data-category="washer">غسالات</a></li>
      <li><a href="#" class="filter-btn" data-category="ac">تكييفات</a></li>
      <li><a href="#" class="filter-btn" data-category="oven">بوتجازات</a></li>
      <li><a href="#" class="filter-btn" data-category="tv">تلفزيونات</a></li>
    </ul>
  </div>

  <section id="products">
    <h2>المنتجات</h2>
    <div class="product-grid">
      <?php
      $category_classes = [
        'ثلاجات'     => 'fridge',
        'غسالات'     => 'washer',
        'تكييفات'    => 'ac',
        'بوتجازات'   => 'oven',
        'تلفزيونات'  => 'tv'
      ];

      $result = mysqli_query($conn, "
        SELECT p.product_id, p.name, p.description, p.price, p.image_url, c.category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        ORDER BY p.product_id DESC
      ");

      while($row = mysqli_fetch_assoc($result)){
        $class = $category_classes[$row['category_name']] ?? 'uncategorized';
        echo "<div class='product-card ".$class."'>";
        
        // 👑 الاسم أول عنصر
        echo "<h3>".$row['name']."</h3>";
        
        // 🖼️ الصورة بعد الاسم
        if(!empty($row['image_url'])){
          echo "<img src='".$row['image_url']."' alt='".$row['name']."'>";
        }
      
        // 📄 الوصف
        echo "<p>".$row['description']."</p>";
      
        // 💰 السعر + زر السلة
        echo "<div class='product-footer'>";
        echo "<p class='price'>السعر: ".$row['price']." جنيه</p>";

        // ✅ نموذج إضافة إلى السلة
        echo "<form method='POST' action='add_to_cart.php'>";
        echo "<input type='hidden' name='product_id' value='".$row['product_id']."'>";
        echo "<input type='number' name='quantity' value='1' min='1' style='width:50px;'>";
        echo "<button type='submit' style='background-color: #1a68e8;'>أضف إلى السلة</button>" ;
        echo "</form>";

        echo "</div>";
      
        echo "</div>";
      }
      ?>
    </div>
  </section>

  <footer>
    <p>جميع الحقوق محفوظة &copy; 2025</p>
  </footer>

  <!-- زر البوت -->
  <div class="chat-circle" onclick="openBot()">💬</div>

  <script src="script.js"></script>
  <script type="module" src="chatbot.js"></script>
</body>
</html>
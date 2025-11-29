<?php
// استدعاء الاتصال بقاعدة البيانات
require 'db_connect.php';

// إضافة منتج جديد
if(isset($_POST['add'])){
    $name        = $_POST['name'];
    $price       = $_POST['price'];
    $category    = $_POST['category_id'];
    $description = $_POST['description'];

    // رفع الصورة
    $imageName = $_FILES['image']['name'];
    $imageTmp  = $_FILES['image']['tmp_name'];
    $targetDir = "images/" . basename($imageName);

    if(!empty($imageName) && move_uploaded_file($imageTmp, $targetDir)){
        $imagePath = $targetDir;
    } else {
        $imagePath = "";
    }

    $sql = "INSERT INTO products (name, price, category_id, image_url, description) 
            VALUES ('$name','$price','$category','$imagePath','$description')";
    mysqli_query($conn, $sql);

    header("Location: dashboard.php");
    exit();
}

// تعديل منتج
if(isset($_POST['update'])){
    $id          = $_POST['id'];
    $name        = $_POST['name'];
    $price       = $_POST['price'];
    $category    = $_POST['category_id'];
    $description = $_POST['description'];

    if(!empty($_FILES['image']['name'])){
        $imageName = $_FILES['image']['name'];
        $imageTmp  = $_FILES['image']['tmp_name'];
        $targetDir = "images/" . basename($imageName);
        move_uploaded_file($imageTmp, $targetDir);
        $imagePath = $targetDir;

        $sql = "UPDATE products 
                SET name='$name', price='$price', category_id='$category', image_url='$imagePath', description='$description' 
                WHERE product_id=$id";
    } else {
        $sql = "UPDATE products 
                SET name='$name', price='$price', category_id='$category', description='$description' 
                WHERE product_id=$id";
    }

    mysqli_query($conn, $sql);
    header("Location: dashboard.php");
    exit();
}

// حذف منتج
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE product_id=$id";
    mysqli_query($conn, $sql);

    header("Location: dashboard.php");
    exit();
}

// جلب المنتجات
$result = mysqli_query($conn, "SELECT p.product_id, p.name, p.price, p.image_url, p.description, c.category_name 
                               FROM products p 
                               LEFT JOIN categories c ON p.category_id = c.category_id");
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<title>لوحة التحكم</title>
<style>
body {
  font-family: "Cairo", Tahoma, Arial, sans-serif;
  background: #f2f4f8;
  margin: 0;
  padding: 20px;
  direction: rtl;
}
h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}
.form-add {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  margin-bottom: 30px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.form-add input, .form-add select, .form-add textarea {
  display: block;
  width: 100%;
  margin: 10px 0;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}
.form-add button {
  background: #2575fc;
  color: #fff;
  border: none;
  padding: 10px 15px;
  border-radius: 6px;
  cursor: pointer;
}
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}
.product-card {
  background: #fff;
  padding: 15px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  text-align: center;
}
.product-card img {
  max-width: 100%;
  border-radius: 8px;
  margin-bottom: 10px;
}
.product-card h3 {
  margin: 10px 0;
  color: #333;
}
.product-card .price {
  color: #28a745;
  font-weight: bold;
}
.actions {
  margin-top: 15px;
}
.actions form {
  margin-top: 10px;
}
.btn-delete {
  background: #dc3545;
  color: #fff;
  padding: 8px 12px;
  border-radius: 6px;
  text-decoration: none;
}
.btn-update {
  background: #2575fc;
  color: #fff;
  padding: 8px 12px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
}
</style>
</head>
<body>

<h2>إضافة منتج جديد</h2>
<form method="POST" enctype="multipart/form-data" class="form-add">
  <input type="text" name="name" placeholder="اسم المنتج" required>
  <input type="number" name="price" placeholder="السعر" required>
  <select name="category_id">
    <?php
    $cats = mysqli_query($conn, "SELECT * FROM categories");
    while($cat = mysqli_fetch_assoc($cats)){
        echo "<option value='".$cat['category_id']."'>".$cat['category_name']."</option>";
    }
    ?>
  </select>
  <textarea name="description" placeholder="وصف المنتج"></textarea>
  <input type="file" name="image" accept="image/*">
  <button type="submit" name="add">إضافة المنتج</button>
</form>

<h2>قائمة المنتجات</h2>
<div class="product-grid">
  <?php while($row = mysqli_fetch_assoc($result)){ ?>
    <div class="product-card">
      <?php if(!empty($row['image_url'])){ ?>
        <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
      <?php } ?>
      <h3><?php echo $row['name']; ?></h3>
      <p><?php echo $row['description']; ?></p>
      <p class="price"><?php echo $row['price']; ?> جنيه</p>
      <p class="category"><?php echo $row['category_name']; ?></p>
      <div class="actions">
        <a href="?delete=<?php echo $row['product_id']; ?>" class="btn-delete">حذف</a>
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo $row['product_id']; ?>">
          <input type="text" name="name" value="<?php echo $row['name']; ?>">
          <input type="number" name="price" value="<?php echo $row['price']; ?>">
          <select name="category_id">
            <?php
            $cats = mysqli_query($conn, "SELECT * FROM categories");
            while($cat = mysqli_fetch_assoc($cats)){
              $selected = ($cat['category_name'] == $row['category_name']) ? "selected" : "";
              echo "<option value='".$cat['category_id']."' $selected>".$cat['category_name']."</option>";
            }
            ?>
          </select>
          <textarea name="description"><?php echo $row['description']; ?></textarea>
          <input type="file" name="image" accept="image/*">
          <button type="submit" name="update" class="btn-update">تعديل</button>
        </form>
      </div>
    </div>
  <?php } ?>
</div>

</body>
</html>

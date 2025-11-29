<?php
session_start(); // ضروري لتفعيل الجلسة
require 'db_connect.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("فشل الاتصال: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $stored_password);
    $stmt->fetch();

    if (password_verify($password, $stored_password)) {
      // تخزين بيانات المستخدم في الجلسة
      $_SESSION['user_id'] = $user_id;       // معرف المستخدم
      $_SESSION['username'] = $username;     // اسم المستخدم
  
      // ✅ إعادة التوجيه مباشرة للصفحة الرئيسية
      header("Location: index.php");
      exit;
  } else {
      echo "<h2 style='color:red;'>❌ كلمة المرور غير صحيحة.</h2>";
  }
  } else {
      echo "<h2 style='color:red;'>❌ اسم المستخدم غير موجود.</h2>";
  }
  
  $stmt->close();
} else {
  echo '
  <form method="POST" action="login.php" style="text-align:center; margin-top:50px;">
    <input type="text" name="username" placeholder="اسم المستخدم" required><br><br>
    <input type="password" name="password" placeholder="كلمة المرور" required><br><br>
    <button type="submit">تسجيل الدخول</button>
  </form>
  ';
}

$conn->close();
?>

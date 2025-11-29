<?php
// أول حاجة: استدعاء ملف الاتصال
require 'db_connect.php';
// عرض الأخطاء للمساعدة في التصحيح
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("فشل الاتصال: " . $conn->connect_error);
}

// التحقق من أن الطلب من نوع POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // استقبال البيانات مع التحقق من وجودها
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm-password'] ?? '';

  // التحقق من البيانات
  if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
    die("يرجى ملء جميع الحقول.");
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("البريد الإلكتروني غير صالح.");
  }

  if ($password !== $confirm) {
    die("كلمة المرور غير متطابقة.");
  }

  // التحقق من وجود البريد مسبقًا
  $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    die("هذا البريد الإلكتروني مسجل بالفعل.");
  }

  $check->close();

  // تشفير كلمة المرور
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // إدخال البيانات
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, $hashed_password);

  if ($stmt->execute()) {
    // إعادة التوجيه لصفحة نجاح أو تسجيل الدخول
    header("Location: success.php");
    exit;
  } else {
    echo "حدث خطأ أثناء التسجيل: " . $stmt->error;
  }

  $stmt->close();
}

$conn->close();
?>

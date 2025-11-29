<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إنشاء حساب جديد</title>
  <link rel="stylesheet" href="create_account.css">
</head>
<body>
  <div class="form-container">
    <h2>إنشاء حساب</h2>
    <form action="handle_signup.php" method="POST" id="signup-form">
      <label for="username">اسم المستخدم</label>
      <input type="text" id="username" name="username" required>

      <label for="email">البريد الإلكتروني</label>
      <input type="email" id="email" name="email" required>

      <label for="password">كلمة المرور</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm-password">تأكيد كلمة المرور</label>
      <input type="password" id="confirm-password" name="confirm-password" required>

      <button type="submit" name="submit">تسجيل</button>
      <p id="error-message"></p>
    </form>
  </div>
  <script src="create_account.js"></script>
</body>
</html>

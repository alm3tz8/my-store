<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: index1.php");
    exit;
}
$error = $_GET['error'] ?? '';
$registered = $_GET['registered'] ?? '';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تسجيل الدخول | AboAzz Store</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="brand">
    <div class="logo-bubble">A</div>
    <div>
      <div>AboAzz Store</div>
      <div class="subtext">متجر المنتجات الرقمية</div>
    </div>
  </div>
</header>

<main class="container" style="max-width:420px; margin:60px auto;">
  <div class="auth-card">
    <h2 style="margin-bottom:24px; color:var(--dark-blue);">تسجيل الدخول</h2>

    <?php if ($registered): ?>
      <div class="alert-success">تم إنشاء حسابك بنجاح! سجّل دخولك الآن.</div>
    <?php endif; ?>

    <?php if ($error === 'wrong'): ?>
      <div class="alert-danger">اسم المستخدم أو كلمة المرور غير صحيحة.</div>
    <?php elseif ($error === 'invalid'): ?>
      <div class="alert-danger">يرجى تعبئة جميع الحقول.</div>
    <?php endif; ?>

    <form method="post" action="authenticate.php">
      <div class="form-group">
        <label>اسم المستخدم</label>
        <input type="text" name="username" required autofocus>
      </div>
      <div class="form-group">
        <label>كلمة المرور</label>
        <input type="password" name="password" required>
      </div>
      <div class="form-group">
        <label>نوع الحساب</label>
        <div style="display:flex; gap:20px; margin-top:8px;">
          <label class="radio-label"><input type="radio" name="role" value="user" checked> مستخدم</label>
          <label class="radio-label"><input type="radio" name="role" value="admin"> مدير</label>
        </div>
      </div>
      <button type="submit" class="btn-primary">دخول</button>
    </form>

    <p style="margin-top:16px; text-align:center;">
      ليس لديك حساب؟ <a href="register.php">أنشئ حساباً</a>
    </p>
    <p style="text-align:center;">
      <a href="forgetpass.php">نسيت كلمة المرور؟</a>
    </p>
  </div>
</main>

</body>
</html>

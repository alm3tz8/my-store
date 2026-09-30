<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index1.php");
    exit;
}
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>إضافة منتج | AboAzz Store</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="brand">
    <div class="logo-bubble">A</div>
    <div><div>AboAzz Store</div><div class="subtext">لوحة الإدارة</div></div>
  </div>
  <nav class="navigation"><ul>
    <li><a class="nav-link" href="index1.php">الرئيسية</a></li>
    <li><a class="nav-link" href="view_products.php">المنتجات</a></li>
    <li><a class="nav-link" href="logout.php">خروج</a></li>
  </ul></nav>
</header>

<main class="container" style="max-width:540px; margin:40px auto;">
  <div class="auth-card">
    <h2 style="margin-bottom:24px; color:var(--dark-blue);">إضافة منتج جديد</h2>

    <?php if ($error === 'invalid'): ?>
      <div class="alert-danger">يرجى التحقق من البيانات المدخلة.</div>
    <?php endif; ?>

    <form action="save_product.php" method="POST">
      <div class="form-group">
        <label>اسم المنتج</label>
        <input type="text" name="p_name" required placeholder="مثال: كورس Python للمبتدئين">
      </div>
      <div class="form-group">
        <label>السعر (ريال)</label>
        <input type="number" step="0.01" min="0" name="p_price" required placeholder="0.00">
      </div>
      <div class="form-group">
        <label>الوصف</label>
        <textarea name="p_desc" required rows="4" placeholder="اكتب وصفاً مفصّلاً للمنتج..."></textarea>
      </div>
      <div class="form-group">
        <label>رابط الصورة (اختياري)</label>
        <input type="url" name="p_image" placeholder="https://...">
      </div>
      <button type="submit" class="btn-primary">حفظ المنتج</button>
    </form>
    <p style="margin-top:16px;"><a href="view_products.php">← عرض كافة المنتجات</a></p>
  </div>
</main>
</body>
</html>

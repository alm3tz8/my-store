<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'dbconnection.php';

$uid    = (int)$_SESSION['uid'];
$errors = [];
$success = false;

$stmt = $conn->prepare("SELECT * FROM users WHERE id = :a");
$stmt->execute([':a' => $uid]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fn = trim($_POST['fullname'] ?? '');
    $em = trim($_POST['email']    ?? '');
    $ph = trim($_POST['phone']    ?? '');

    if (empty($fn)) $errors[] = "الاسم الكامل مطلوب.";
    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) $errors[] = "البريد الإلكتروني غير صحيح.";
    if (!preg_match("/^[0-9]{8}$/", $ph)) $errors[] = "رقم الهاتف يجب أن يكون 8 أرقام.";

    if (empty($errors)) {
        $upd = $conn->prepare("UPDATE users SET fullname=:a, email=:b, phone=:c WHERE id=:d");
        $upd->execute([':a'=>$fn, ':b'=>$em, ':c'=>$ph, ':d'=>$uid]);
        $success = true;
        // تحديث بيانات الصفحة
        $user['fullname'] = $fn;
        $user['email']    = $em;
        $user['phone']    = $ph;
    }
}

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تحديث الملف الشخصي | AboAzz Store</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="brand">
    <div class="logo-bubble">A</div>
    <div><div>AboAzz Store</div><div class="subtext">متجر المنتجات الرقمية</div></div>
  </div>
  <nav class="navigation"><ul>
    <li><a class="nav-link" href="index1.php">الرئيسية</a></li>
    <li><a class="nav-link" href="view_cart.php">السلة 🛒</a></li>
    <li><a class="nav-link" href="logout.php">خروج</a></li>
  </ul></nav>
</header>

<main class="container" style="max-width:480px; margin:40px auto;">
  <div class="auth-card">
    <h2 style="color:var(--dark-blue); margin-bottom:20px;">تحديث الملف الشخصي</h2>

    <?php if ($success): ?>
      <div class="alert-success">تم تحديث بياناتك بنجاح!</div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
      <div class="alert-danger"><?php echo h($e); ?></div>
    <?php endforeach; ?>

    <form method="post">
      <div class="form-group">
        <label>اسم المستخدم</label>
        <input type="text" value="<?php echo h($user['username']); ?>" disabled style="opacity:0.6;">
      </div>
      <div class="form-group">
        <label>الاسم الكامل</label>
        <input type="text" name="fullname" value="<?php echo h($user['fullname']); ?>" required>
      </div>
      <div class="form-group">
        <label>البريد الإلكتروني</label>
        <input type="email" name="email" value="<?php echo h($user['email']); ?>" required>
      </div>
      <div class="form-group">
        <label>رقم الهاتف</label>
        <input type="text" name="phone" maxlength="8" value="<?php echo h($user['phone']); ?>" required>
      </div>
      <button type="submit" class="btn-primary">حفظ التغييرات</button>
    </form>
  </div>
</main>

<footer>
  <div class="footer-left">© 2025 AboAzz Store - جميع الحقوق محفوظة</div>
</footer>
</body>
</html>

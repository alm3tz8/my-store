<?php
include 'dbconnection.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $un  = trim($_POST['username'] ?? '');
    $pw  = $_POST['password'] ?? '';
    $fn  = trim($_POST['fullname'] ?? '');
    $cid = trim($_POST['civilid'] ?? '');
    $em  = trim($_POST['email'] ?? '');
    $ph  = trim($_POST['phone'] ?? '');

    if (empty($un) || empty($pw) || empty($fn) || empty($cid) || empty($em) || empty($ph))
        $errors[] = "يرجى تعبئة جميع الحقول.";

    if (!preg_match("/^(?=.*[A-Za-z])(?=.*[0-9]).{6,}$/", $pw))
        $errors[] = "كلمة المرور يجب أن تكون 6 أحرف على الأقل وتحتوي حروفاً وأرقاماً.";

    if (!preg_match("/^[0-9]{8}$/", $cid))
        $errors[] = "الرقم المدني يجب أن يكون 8 أرقام.";

    if (!filter_var($em, FILTER_VALIDATE_EMAIL))
        $errors[] = "البريد الإلكتروني غير صحيح.";

    if (!preg_match("/^[0-9]{8}$/", $ph))
        $errors[] = "رقم الهاتف يجب أن يكون 8 أرقام.";

    if (empty($errors)) {
        // التحقق من عدم تكرار اسم المستخدم
        $check = $conn->prepare("SELECT id FROM users WHERE username = :u");
        $check->execute([':u' => $un]);
        if ($check->fetch()) {
            $errors[] = "اسم المستخدم مستخدم بالفعل.";
        } else {
            $hashed = password_hash($pw, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users(username, password, fullname, civilid, email, phone)
                    VALUES(:a, :b, :c, :d, :e, :f)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':a'=>$un, ':b'=>$hashed, ':c'=>$fn, ':d'=>$cid, ':e'=>$em, ':f'=>$ph]);
            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>إنشاء حساب | AboAzz Store</title>
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

<main class="container" style="max-width:480px; margin:40px auto;">
  <div class="auth-card">
    <h2 style="margin-bottom:24px; color:var(--dark-blue);">إنشاء حساب جديد</h2>

    <?php if (!empty($errors)): ?>
      <div class="alert-danger">
        <?php foreach ($errors as $e): ?>
          <p><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="form-group">
        <label>اسم المستخدم</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
      </div>
      <div class="form-group">
        <label>كلمة المرور</label>
        <input type="password" name="password" required>
        <small>6 أحرف على الأقل، تحتوي حروفاً وأرقاماً</small>
      </div>
      <div class="form-group">
        <label>الاسم الكامل</label>
        <input type="text" name="fullname" value="<?php echo htmlspecialchars($_POST['fullname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
      </div>
      <div class="form-group">
        <label>الرقم المدني</label>
        <input type="text" name="civilid" maxlength="8" value="<?php echo htmlspecialchars($_POST['civilid'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
      </div>
      <div class="form-group">
        <label>البريد الإلكتروني</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
      </div>
      <div class="form-group">
        <label>رقم الهاتف</label>
        <input type="text" name="phone" maxlength="8" value="<?php echo htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
      </div>
      <button type="submit" class="btn-primary">إنشاء الحساب</button>
    </form>
    <p style="margin-top:16px; text-align:center;">لديك حساب؟ <a href="login.php">سجّل دخولك</a></p>
  </div>
</main>

</body>
</html>

<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index1.php");
    exit;
}
include 'dbconnection.php';

$sql  = "SELECT applications.*, jobs.title 
         FROM applications 
         JOIN jobs ON applications.job_id = jobs.id
         ORDER BY applications.id DESC";
$stmt = $conn->query($sql);
$rows = $stmt->fetchAll();

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>لوحة الإدارة | AboAzz Store</title>
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
    <li><a class="nav-link" href="add_product.php">إضافة منتج</a></li>
    <li><a class="nav-link" href="view_products.php">المنتجات</a></li>
    <li><a class="nav-link" href="logout.php">خروج</a></li>
  </ul></nav>
</header>

<main class="container" style="margin:32px auto;">
  <h2 style="color:var(--dark-blue); margin-bottom:20px;">جميع الطلبات</h2>

  <?php if (empty($rows)): ?>
    <p style="color:var(--muted);">لا توجد طلبات حالياً.</p>
  <?php else: ?>
  <div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; font-size:14px;">
      <thead>
        <tr style="background:var(--dark-blue); color:white;">
          <th style="padding:12px 10px;">#</th>
          <th style="padding:12px 10px;">الوظيفة</th>
          <th style="padding:12px 10px;">الاسم</th>
          <th style="padding:12px 10px;">الرقم المدني</th>
          <th style="padding:12px 10px;">البريد</th>
          <th style="padding:12px 10px;">الهاتف</th>
          <th style="padding:12px 10px;">الإجراء</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $i => $r): ?>
        <tr style="background:<?php echo $i % 2 === 0 ? '#fff' : '#f9f9f9'; ?>; border-bottom:1px solid #eee;">
          <td style="padding:10px;"><?php echo (int)$r['id']; ?></td>
          <td style="padding:10px;"><?php echo h($r['title']); ?></td>
          <td style="padding:10px;"><?php echo h($r['fullname']); ?></td>
          <td style="padding:10px;"><?php echo h($r['civilid']); ?></td>
          <td style="padding:10px;"><?php echo h($r['email']); ?></td>
          <td style="padding:10px;"><?php echo h($r['phone']); ?></td>
          <td style="padding:10px;">
            <a href="update_application.php?id=<?php echo (int)$r['id']; ?>" style="color:var(--dark-blue); margin-left:8px;">تعديل</a>
            <a href="delete_application.php?id=<?php echo (int)$r['id']; ?>" style="color:#c0392b;" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</main>

<footer>
  <div class="footer-left">© 2025 AboAzz Store - جميع الحقوق محفوظة</div>
</footer>
</body>
</html>

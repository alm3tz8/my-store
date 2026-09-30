<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'dbconnection.php';

$items = $conn->query("SELECT * FROM product ORDER BY id DESC")->fetchAll();
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>المنتجات | AboAzz Store</title>
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
    <?php if ($isAdmin): ?>
    <li><a class="nav-link" href="add_product.php">+ إضافة منتج</a></li>
    <?php endif; ?>
    <li><a class="nav-link" href="view_cart.php">السلة 🛒
      <?php $c = count($_SESSION['cart'] ?? []); if($c > 0) echo "<span class='cart-badge'>$c</span>"; ?>
    </a></li>
    <li><a class="nav-link" href="logout.php">خروج</a></li>
  </ul></nav>
</header>

<main class="container" style="margin:32px auto;">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="color:var(--dark-blue);">المنتجات المتوفرة</h2>
    <?php if ($isAdmin): ?>
      <a href="add_product.php" class="btn-primary" style="text-decoration:none;">+ منتج جديد</a>
    <?php endif; ?>
  </div>

  <?php if (isset($_GET['added'])): ?>
    <div class="alert-success" style="margin-bottom:16px;">تمت إضافة المنتج بنجاح!</div>
  <?php endif; ?>

  <?php if (empty($items)): ?>
    <p style="text-align:center; color:var(--muted); padding:40px;">لا توجد منتجات حالياً.</p>
  <?php else: ?>
  <div class="grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:20px;">
    <?php foreach ($items as $item):
      $img = !empty($item['image']) ? h($item['image']) : 'https://placehold.co/300x200/03294c/f7e8b8?text=' . urlencode($item['name']);
    ?>
    <div class="card product-card">
      <img src="<?php echo $img; ?>" alt="<?php echo h($item['name']); ?>">
      <div class="card-body">
        <div class="product-title"><?php echo h($item['name']); ?></div>
        <div class="product-meta">
          <div class="price"><?php echo number_format((float)$item['price'], 2); ?> ريال</div>
          <a class="buy" href="product_info.php?id=<?php echo (int)$item['id']; ?>">التفاصيل</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</main>

<footer>
  <div class="footer-left">© 2025 AboAzz Store - جميع الحقوق محفوظة</div>
</footer>
</body>
</html>

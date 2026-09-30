<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// إضافة منتج للسلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $name  = htmlspecialchars(trim($_POST['item_name']  ?? ''), ENT_QUOTES, 'UTF-8');
    $price = (float)($_POST['item_price'] ?? 0);
    $image = htmlspecialchars(trim($_POST['item_image'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (!empty($name) && $price > 0) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        // هل المنتج موجود؟ زِد الكمية
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $name) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }
        unset($item);
        if (!$found) {
            $_SESSION['cart'][] = ['name' => $name, 'price' => $price, 'image' => $image, 'quantity' => 1];
        }
    }
    header("Location: view_cart.php");
    exit;
}

// حذف عنصر
if (isset($_POST['remove_item'])) {
    $idx = (int)$_POST['remove_item'];
    if (isset($_SESSION['cart'][$idx])) {
        array_splice($_SESSION['cart'], $idx, 1);
    }
    header("Location: view_cart.php");
    exit;
}

// تفريغ السلة عبر POST
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    header("Location: view_cart.php");
    exit;
}

$cart  = $_SESSION['cart'] ?? [];
$total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>سلة المشتريات | AboAzz Store</title>
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
    <li><a class="nav-link" href="logout.php">خروج</a></li>
  </ul></nav>
</header>

<main class="container" style="max-width:700px; margin:40px auto;">
  <h2 style="color:var(--dark-blue); margin-bottom:24px;">🛒 سلة المشتريات</h2>

  <?php if (empty($cart)): ?>
    <div class="empty-cart">
      <p style="font-size:48px; text-align:center;">🛍️</p>
      <p style="text-align:center; color:var(--muted);">سلتك فارغة</p>
      <div style="text-align:center; margin-top:16px;">
        <a href="view_products.php" class="btn-primary" style="display:inline-block; text-decoration:none;">تسوّق الآن</a>
      </div>
    </div>
  <?php else: ?>

    <?php foreach ($cart as $idx => $item): ?>
    <div class="cart-item-row">
      <img src="<?php echo h($item['image'] ?: 'https://placehold.co/80x80/03294c/f7e8b8?text='.urlencode($item['name'])); ?>" width="70" style="border-radius:8px;">
      <div style="flex:1;">
        <div style="font-weight:500;"><?php echo h($item['name']); ?></div>
        <div style="color:var(--muted); font-size:14px;"><?php echo number_format($item['price'],2); ?> ريال × <?php echo $item['quantity']; ?></div>
      </div>
      <div style="font-weight:700; color:var(--dark-blue);"><?php echo number_format($item['price'] * $item['quantity'], 2); ?> ريال</div>
      <form method="POST" style="margin:0;">
        <input type="hidden" name="remove_item" value="<?php echo $idx; ?>">
        <button type="submit" class="delete-btn" title="حذف">🗑</button>
      </form>
    </div>
    <?php endforeach; ?>

    <div style="border-top:1px solid #eee; margin-top:20px; padding-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
      <form method="POST">
        <button type="submit" name="clear_cart" value="1" style="background:none; border:1px solid #ccc; padding:8px 16px; border-radius:8px; cursor:pointer; color:var(--muted);">تفريغ السلة</button>
      </form>
      <div style="font-size:20px; font-weight:700; color:var(--dark-blue);">
        المجموع: <?php echo number_format($total, 2); ?> ريال
      </div>
    </div>

    <div style="text-align:center; margin-top:24px;">
      <a href="checkout.php" class="btn-primary" style="display:inline-block; text-decoration:none; font-size:16px;">متابعة الدفع ←</a>
    </div>

  <?php endif; ?>
</main>

<footer>
  <div class="footer-left">© 2025 AboAzz Store - جميع الحقوق محفوظة</div>
</footer>
</body>
</html>

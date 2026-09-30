<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'dbconnection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: view_products.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: view_products.php?error=notfound");
    exit;
}

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo h($product['name']); ?> | AboAzz Store</title>
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
    <li><a class="nav-link" href="view_cart.php">السلة 🛒
      <?php $count = count($_SESSION['cart'] ?? []); if($count > 0) echo "<span class='cart-badge'>$count</span>"; ?>
    </a></li>
    <li><a class="nav-link" href="logout.php">خروج</a></li>
  </ul></nav>
</header>

<main class="container">
  <section class="product-details">
    <div class="product-page">
      <div class="product-image-container">
        <?php
          $img = !empty($product['image']) ? h($product['image']) : 'https://placehold.co/400x300/03294c/f7e8b8?text=' . urlencode($product['name']);
        ?>
        <img id="productImg" src="<?php echo $img; ?>" alt="<?php echo h($product['name']); ?>" class="product-image">
      </div>
      <div class="product-info">
        <h2 id="productName"><?php echo h($product['name']); ?></h2>
        <p id="productPrice"><?php echo number_format((float)$product['price'], 2); ?> ريال</p>
        <p id="productDesc"><?php echo h($product['description']); ?></p>

        <form action="view_cart.php" method="POST">
          <input type="hidden" name="add_to_cart" value="1">
          <input type="hidden" name="item_name"  value="<?php echo h($product['name']); ?>">
          <input type="hidden" name="item_price" value="<?php echo (float)$product['price']; ?>">
          <input type="hidden" name="item_image" value="<?php echo $img; ?>">
          <button type="submit" class="buy-now">🛒 إضافة إلى السلة</button>
        </form>

        <a href="view_products.php" class="back-home">← العودة للمتجر</a>
      </div>
    </div>
  </section>
</main>

<footer>
  <div class="footer-left">© 2025 AboAzz Store - جميع الحقوق محفوظة</div>
</footer>
</body>
</html>

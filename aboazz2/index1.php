<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'dbconnection.php';

$stmt = $conn->query("SELECT * FROM product ORDER BY id DESC");
$products = $stmt->fetchAll();
$isAdmin  = ($_SESSION['role'] ?? '') === 'admin';
$cartCount = count($_SESSION['cart'] ?? []);

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AboAzz Store</title>
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
  <nav class="navigation">
    <ul>
      <li><a class="nav-link" href="index1.php">الرئيسية</a></li>
      <li><a class="nav-link" href="update_profile.php">ملفي الشخصي</a></li>
      <?php if ($isAdmin): ?>
        <li><a class="nav-link" href="view_applications.php">الطلبات</a></li>
        <li><a class="nav-link" href="add_product.php">+ منتج</a></li>
        <li><a class="nav-link" href="view_products.php">إدارة المنتجات</a></li>
      <?php endif; ?>
      <li>
        <a class="nav-link" href="view_cart.php">
          السلة 🛒<?php if($cartCount > 0) echo " <span class='cart-badge'>$cartCount</span>"; ?>
        </a>
      </li>
      <li><a class="nav-link" href="logout.php">خروج</a></li>
    </ul>
  </nav>
</header>

<section class="hero">
  <div class="hero-left">
    <h1 class="headline">مرحباً، <?php echo h($_SESSION['user']); ?> 👋</h1>
    <p class="sub">اكتشف أفضل الكتب، القوالب، والكورسات الرقمية</p>
    <div class="search-box">
      <input id="search" class="search-input" placeholder="ابحث عن منتج...">
      <button id="searchBtn" class="search-btn">بحث</button>
    </div>
  </div>
  <div class="hero-right">
    <div class="promo-box">
      <div style="text-align:center;">
        <div style="font-size:40px;">📚</div>
        <div style="margin-top:8px; font-size:14px;">أكثر من <?php echo count($products); ?> منتج رقمي</div>
      </div>
    </div>
  </div>
</section>

<main class="container">
  <section class="works">
    <h2 class="section-title">منتجاتنا</h2>

    <div id="products" class="grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:20px; padding:0;">
      <?php if (empty($products)): ?>
        <p style="color:var(--muted);">لا توجد منتجات بعد.</p>
      <?php endif; ?>
      <?php foreach ($products as $job):
        $img = !empty($job['image']) ? h($job['image']) : 'https://placehold.co/300x200/03294c/f7e8b8?text=' . urlencode($job['name']);
      ?>
      <div class="card product-card" data-title="<?php echo h(strtolower($job['name'])); ?>">
        <img src="<?php echo $img; ?>" alt="<?php echo h($job['name']); ?>">
        <div class="card-body">
          <div class="product-title"><?php echo h($job['name']); ?></div>
          <div class="product-meta">
            <div class="price"><?php echo number_format((float)$job['price'], 2); ?> ريال</div>
            <a class="buy" href="product_info.php?id=<?php echo (int)$job['id']; ?>">شراء</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="about" class="about-section" style="margin-top:40px;">
    <h3>عن المتجر</h3>
    <p>AboAzz Store متجر رقمي متخصص في بيع الكتب، القوالب، والكورسات. نسعى لتوفير أفضل المنتجات الرقمية بأسعار مناسبة.</p>
  </section>
</main>

<footer>
  <div class="footer-left">© 2025 AboAzz Store - جميع الحقوق محفوظة</div>
  <div class="socials"><a href="https://www.tiktok.com/@alazzstore" target="_blank" rel="noopener">تابعنا على تيك توك</a></div>
</footer>

<script>
const searchInput = document.getElementById('search');
const searchBtn   = document.getElementById('searchBtn');
const cards = Array.from(document.querySelectorAll('.product-card'));

function doSearch() {
  const q = searchInput.value.trim().toLowerCase();
  let found = 0;
  cards.forEach(card => {
    const title = (card.dataset.title || '').toLowerCase();
    const show  = q === '' || title.includes(q);
    card.style.display = show ? '' : 'none';
    if (show) found++;
  });
}

searchBtn.addEventListener('click', doSearch);
searchInput.addEventListener('input', doSearch);
searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') doSearch(); });
</script>

</body>
</html>

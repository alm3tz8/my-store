<!-- الدفع --> 

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الدفع | إلهام</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h2 style="text-align:center; margin:25px 0;">الدفع</h2>

<div class="checkout-container">
  <div class="checkout-items" id="checkoutItems"></div>
  <h3>الإجمالي: <span id="checkoutTotal">0</span> ريال</h3>

  <h3>اختر طريقة الدفع:</h3>

  <button class="paypal-btn" onclick="payWithPayPal()">الدفع عبر PayPal</button>
  <button class="stripe-btn" onclick="payWithStripe()">الدفع عبر Stripe</button>

  <a class="back-home" href="index1.php">← رجوع للصفحة الرئيسية</a>
</div>

<script>
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let checkoutItems = document.getElementById("checkoutItems");
  let total = 0;

  // هذا الكود موجود عندك مسبقاً لعرض العناصر
  checkoutItems.innerHTML = ""; 

  cart.forEach(item => {
    total += item.price;
    // أضفنا وسم الـ img هنا ونسقنا عرض الاسم
    checkoutItems.innerHTML += `
      <div style="display:flex; align-items:center; gap:15px; padding:10px; border:1px solid #eee; border-radius:8px; margin-bottom:10px;">
        <img src="${item.image}" width="60" style="border-radius:5px;">
        <div>
          <h4 style="margin:0;">${item.name}</h4>
          <p style="margin:5px 0 0; color:#666;">${item.price} ريال</p>
        </div>
      </div>
    `;
  });
  
  document.getElementById("checkoutTotal").textContent = total;

  // --- هنا تبدأ الإضافة الجديدة ---

  function processOrder() {
    if (cart.length === 0) {
      alert("السلة فارغة!");
      return Promise.reject("empty_cart"); // لإيقاف العملية لو السلة فارغة
    }

    // نستخدم fetch لإرسال السلة إلى ملف PHP
    return fetch('save_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(cart) 
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log("تم حفظ الطلب في السيرفر");
            return true;
        } else {
            alert("خطأ في السيرفر: " + data.message);
            return false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        return false;
    });
  }

  // تعديل دالة PayPal لتقوم بالحفظ أولاً
  async function payWithPayPal() {
    const success = await processOrder(); // ننتظر حفظ الطلب في MySQL أولاً
    if(success) {
        alert("✅ تم تسجيل طلبك، جاري تحويلك للدفع...");
        localStorage.removeItem("cart"); // نمسح السلة من المتصفح بعد الحفظ
        window.location.href = "https://www.paypal.com/pay?amount=" + total;
    }
  }

  // نفس الشيء لدالة Stripe
  async function payWithStripe() {
    const success = await processOrder();
    if(success) {
        alert("✅ تم تسجيل طلبك، جاري تحويلك للدفع...");
        localStorage.removeItem("cart");
        window.location.href = "https://buy.stripe.com/test_XXXXXXXXXXXX?amount=" + total;
    }
  }
</script>

</body>
</html>
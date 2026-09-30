<?php
session_start();
include 'dbconnection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['uid'])) {
    echo json_encode(['status' => 'error', 'message' => 'يجب تسجيل الدخول أولاً']);
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo json_encode(['status' => 'error', 'message' => 'السلة فارغة']);
    exit;
}

$input  = json_decode(file_get_contents('php://input'), true);
$method = in_array($input['method'] ?? '', ['paypal', 'stripe']) ? $input['method'] : 'unknown';

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_name, price, quantity, payment_method) VALUES (:uid, :pname, :price, :qty, :method)");

    foreach ($cart as $item) {
        $stmt->execute([
            ':uid'    => $_SESSION['uid'],
            ':pname'  => $item['name'],
            ':price'  => $item['price'],
            ':qty'    => $item['quantity'],
            ':method' => $method
        ]);
    }

    $conn->commit();

    // تفريغ السلة بعد الحفظ
    $_SESSION['cart'] = [];

    echo json_encode(['status' => 'success', 'message' => 'تم حفظ الطلب بنجاح']);
} catch (Exception $e) {
    $conn->rollBack();
    error_log("Order error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'خطأ في حفظ الطلب']);
}
?>

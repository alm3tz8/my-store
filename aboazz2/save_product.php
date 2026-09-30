<?php
session_start();
include 'dbconnection.php';

// التحقق من صلاحية الأدمن
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("غير مصرح لك بهذه العملية.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST['p_name'] ?? '');
    $price = $_POST['p_price'] ?? '';
    $desc  = trim($_POST['p_desc'] ?? '');

    if (empty($name) || !is_numeric($price) || $price < 0 || empty($desc)) {
        header("Location: add_product.php?error=invalid");
        exit;
    }

    $sql  = "INSERT INTO product (name, price, description) VALUES (:n, :p, :d)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':n' => $name, ':p' => (float)$price, ':d' => $desc]);

    header("Location: view_products.php?added=1");
    exit;
}

header("Location: add_product.php");
exit;
?>

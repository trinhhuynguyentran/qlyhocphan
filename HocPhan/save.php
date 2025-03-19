<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['MaSV'])) {
    header("Location: ../login.php");
    exit();
}

$maSV = $_SESSION['MaSV'];
$ngayDK = date('Y-m-d');
$cart = $_SESSION['cart'] ?? [];

if (!empty($cart)) {
    $conn->beginTransaction();
    try {
        // Thêm vào bảng DangKy
        $stmt = $conn->prepare("INSERT INTO DangKy (NgayDK, MaSV) VALUES (?, ?)");
        $stmt->execute([$ngayDK, $maSV]);
        $maDK = $conn->lastInsertId();

        // Thêm từng học phần vào ChiTietDangKy và giảm SoLuongDuKien
        foreach ($cart as $maHP) {
            $stmt = $conn->prepare("SELECT SoLuongDuKien FROM HocPhan WHERE MaHP = ?");
            $stmt->execute([$maHP]);
            $soLuong = $stmt->fetchColumn();
            if ($soLuong > 0) {
                $stmt = $conn->prepare("INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)");
                $stmt->execute([$maDK, $maHP]);
                $stmt = $conn->prepare("UPDATE HocPhan SET SoLuongDuKien = SoLuongDuKien - 1 WHERE MaHP = ?");
                $stmt->execute([$maHP]);
            } else {
                throw new Exception("Học phần $maHP đã hết số lượng dự kiến!");
            }
        }
        $conn->commit();
        $_SESSION['cart'] = []; // Xóa giỏ hàng tạm thời
        $message = "Đăng ký thành công!";
    } catch (Exception $e) {
        $conn->rollBack();
        $error = "Lỗi: " . $e->getMessage();
    }
} else {
    $error = "Giỏ hàng trống!";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lưu đăng ký</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-save"></i> Kết quả đăng ký</h1>
        <p>Kết quả đăng ký học phần của bạn</p>
    </header>

    <div class="container">
        <div class="form-card">
            <?php if (isset($message)): ?>
                <p class="success-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <div class="button-group">
                <a href="index.php" class="button"><i class="fas fa-book-open"></i> Quay lại danh sách học phần</a>
                <a href="../index.php" class="button secondary"><i class="fas fa-home"></i> Quay lại trang chủ</a>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 Hệ thống Quản lý Học phần. All rights reserved.</p>
    </footer>
</body>
</html>
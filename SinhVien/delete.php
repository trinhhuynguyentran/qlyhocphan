<?php
include '../includes/db.php';

// Kiểm tra MaSV có được truyền qua URL không
if (!isset($_GET['MaSV']) || empty($_GET['MaSV'])) {
    header("Location: index.php");
    exit();
}

$maSV = $_GET['MaSV'];
try {
    // Lấy thông tin sinh viên để hiển thị
    $stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
    $stmt->execute([$maSV]);
    $sv = $stmt->fetch();

    // Kiểm tra nếu không tìm thấy sinh viên
    if (!$sv) {
        $error = "Không tìm thấy sinh viên với mã $maSV!";
    }
} catch (PDOException $e) {
    $error = "Lỗi: " . $e->getMessage();
}

// Xử lý xóa khi người dùng xác nhận
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($error)) {
    try {
        $stmt = $conn->prepare("DELETE FROM SinhVien WHERE MaSV = ?");
        $stmt->execute([$maSV]);
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Lỗi: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Sinh Viên</title>
    <link rel="stylesheet" href="../style.css">
    <!-- Thêm Font Awesome để sử dụng biểu tượng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-user-times"></i> Xóa Sinh Viên</h1>
        <p>Xác nhận xóa thông tin sinh viên</p>
    </header>

    <div class="container">
        <div class="detail-card">
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                <div class="button-group">
                    <a href="index.php" class="button secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
                    <a href="../index.php" class="button secondary"><i class="fas fa-home"></i> Quay lại trang chủ</a>
                </div>
            <?php else: ?>
                <div class="info">
                    <p><i class="fas fa-id-card"></i> <strong>Mã SV:</strong> <?php echo htmlspecialchars($sv['MaSV']); ?></p>
                    <p><i class="fas fa-user"></i> <strong>Họ Tên:</strong> <?php echo htmlspecialchars($sv['HoTen']); ?></p>
                    <p><i class="fas fa-venus-mars"></i> <strong>Giới Tính:</strong> <?php echo htmlspecialchars($sv['GioiTinh'] ?: 'Chưa xác định'); ?></p>
                    <p><i class="fas fa-calendar-alt"></i> <strong>Ngày Sinh:</strong> <?php echo htmlspecialchars($sv['NgaySinh'] ?: 'Chưa xác định'); ?></p>
                    <p><i class="fas fa-graduation-cap"></i> <strong>Mã Ngành:</strong> <?php echo htmlspecialchars($sv['MaNganh'] ?: 'Chưa xác định'); ?></p>
                </div>
                <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">
                    <div class="button-group">
                        <button type="submit" class="button delete"><i class="fas fa-trash-alt"></i> Xóa</button>
                        <a href="index.php" class="button secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
                        <a href="../index.php" class="button secondary"><i class="fas fa-home"></i> Quay lại trang chủ</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>© 2025 Hệ thống Quản lý Học phần. All rights reserved.</p>
    </footer>
</body>
</html>
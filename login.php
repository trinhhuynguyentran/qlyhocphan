<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maSV = $_POST['MaSV'];
    try {
        $stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
        $stmt->execute([$maSV]);
        $sinhVien = $stmt->fetch();
        
        if ($sinhVien) {
            $_SESSION['MaSV'] = $maSV;
            header("Location: index.php"); // Chuyển hướng đến trang chủ
            exit();
        } else {
            $error = "Mã SV không tồn tại!";
        }
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
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="style.css">
    <!-- Thêm Font Awesome để sử dụng biểu tượng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-sign-in-alt"></i> Đăng nhập</h1>
        <p>Vui lòng nhập mã sinh viên để đăng nhập vào hệ thống</p>
    </header>

    <div class="container">
        <div class="form-card">
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="MaSV"><i class="fas fa-id-card"></i> Mã Sinh Viên</label>
                    <input type="text" id="MaSV" name="MaSV" required placeholder="Nhập mã sinh viên">
                </div>

                <div class="button-group">
                    <button type="submit" class="button"><i class="fas fa-sign-in-alt"></i> Đăng nhập</button>
                    <a href="index.php" class="button secondary"><i class="fas fa-home"></i> Quay lại trang chủ</a>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>© 2025 Hệ thống Quản lý Học phần. All rights reserved.</p>
    </footer>
</body>
</html>
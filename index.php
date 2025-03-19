<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Quản lý học phần</title>
    <link rel="stylesheet" href="style.css">
    <!-- Thêm Font Awesome để sử dụng biểu tượng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-graduation-cap"></i> Quản lý học phần</h1>
        <p>Chào mừng bạn đến với hệ thống quản lý học phần và sinh viên</p>
        <div class="header-actions">
            <?php if (isset($_SESSION['MaSV'])): ?>
                <span>Xin chào, <?php echo htmlspecialchars($_SESSION['MaSV']); ?>!</span>
                <a href="logout.php" class="button logout" onclick="return confirmLogout();">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            <?php else: ?>
                <a href="login.php" class="button">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </a>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <!-- Phần Quản lý Sinh Viên -->
        <div class="section">
            <h2><i class="fas fa-users"></i> Quản lý Sinh Viên</h2>
            <div class="card">
                <p>Quản lý thông tin sinh viên một cách dễ dàng</p>
                <div class="button-group">
                    <a href="SinhVien/index.php" class="button">
                        <i class="fas fa-list"></i> Danh sách Sinh Viên
                    </a>
                    <a href="SinhVien/create.php" class="button">
                        <i class="fas fa-user-plus"></i> Thêm Sinh Viên
                    </a>
                </div>
            </div>
        </div>

        <!-- Phần Quản lý Học Phần -->
        <div class="section">
            <h2><i class="fas fa-book"></i> Quản lý Học Phần</h2>
            <div class="card">
                <p>Đăng ký và quản lý học phần cho sinh viên</p>
                <div class="button-group">
                    <a href="HocPhan/index.php" class="button">
                        <i class="fas fa-book-open"></i> Danh sách Học Phần
                    </a>
                    <a href="HocPhan/cart.php" class="button">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng Học Phần
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 Hệ thống Quản lý Học phần. All rights reserved.</p>
    </footer>

    <script>
        function confirmLogout() {
            return confirm("Bạn có chắc chắn muốn đăng xuất?");
        }
    </script>
</body>
</html>
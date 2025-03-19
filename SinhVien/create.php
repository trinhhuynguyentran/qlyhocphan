<?php
include '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maSV = $_POST['MaSV'];
    $hoTen = $_POST['HoTen'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $hinh = $_POST['Hinh'];
    $maNganh = $_POST['MaNganh'];

    try {
        $stmt = $conn->prepare("INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh]);
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "<p class='error-message'>Lỗi: " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh Viên</title>
    <link rel="stylesheet" href="../style.css">
    <!-- Thêm Font Awesome để sử dụng biểu tượng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-user-plus"></i> Thêm Sinh Viên</h1>
        <p>Nhập thông tin sinh viên để thêm vào hệ thống</p>
    </header>

    <div class="container">
        <div class="form-card">
            <form method="POST">
                <div class="form-group">
                    <label for="MaSV"><i class="fas fa-id-card"></i> Mã SV</label>
                    <input type="text" id="MaSV" name="MaSV" required placeholder="Nhập mã sinh viên">
                </div>

                <div class="form-group">
                    <label for="HoTen"><i class="fas fa-user"></i> Họ Tên</label>
                    <input type="text" id="HoTen" name="HoTen" required placeholder="Nhập họ tên">
                </div>

                <div class="form-group">
                    <label for="GioiTinh"><i class="fas fa-venus-mars"></i> Giới Tính</label>
                    <select id="GioiTinh" name="GioiTinh">
                        <option value="">Chọn giới tính</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="NgaySinh"><i class="fas fa-calendar-alt"></i> Ngày Sinh</label>
                    <input type="date" id="NgaySinh" name="NgaySinh">
                </div>

                <div class="form-group">
                    <label for="Hinh"><i class="fas fa-image"></i> Hình</label>
                    <input type="text" id="Hinh" name="Hinh" placeholder="Nhập đường dẫn hình ảnh">
                </div>

                <div class="form-group">
                    <label for="MaNganh"><i class="fas fa-graduation-cap"></i> Mã Ngành</label>
                    <input type="text" id="MaNganh" name="MaNganh" placeholder="Nhập mã ngành">
                </div>

                <div class="button-group">
                    <button type="submit" class="button"><i class="fas fa-save"></i> Thêm Sinh Viên</button>
                    <a href="index.php" class="button secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
                    <a href="../index.php" class="button secondary"><i class="fas fa-home"></i> Quay lại trang chủ</a>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>© 2025 Hệ thống Quản lý Học phần. All rights reserved.</p>
    </footer>
</body>
</html>
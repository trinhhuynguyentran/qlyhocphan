<?php
include '../includes/db.php';

// Kiểm tra MaSV có được truyền qua URL không
if (!isset($_GET['MaSV']) || empty($_GET['MaSV'])) {
    header("Location: index.php");
    exit();
}

$maSV = $_GET['MaSV'];
try {
    $stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
    $stmt->execute([$maSV]);
    $sv = $stmt->fetch();

    // Kiểm tra nếu không tìm thấy sinh viên
    if (!$sv) {
        echo "<p class='error-message'>Không tìm thấy sinh viên với mã $maSV!</p>";
        exit();
    }
} catch (PDOException $e) {
    echo "<p class='error-message'>Lỗi: " . $e->getMessage() . "</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hoTen = $_POST['HoTen'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $hinh = $_POST['Hinh'];
    $maNganh = $_POST['MaNganh'];

    try {
        $stmt = $conn->prepare("UPDATE SinhVien SET HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=?, MaNganh=? WHERE MaSV=?");
        $stmt->execute([$hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh, $maSV]);
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
    <title>Sửa Sinh Viên</title>
    <link rel="stylesheet" href="../style.css">
    <!-- Thêm Font Awesome để sử dụng biểu tượng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-user-edit"></i> Sửa Sinh Viên</h1>
        <p>Chỉnh sửa thông tin sinh viên</p>
    </header>

    <div class="container">
        <div class="form-card">
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="MaSV"><i class="fas fa-id-card"></i> Mã SV</label>
                    <input type="text" id="MaSV" name="MaSV" value="<?php echo htmlspecialchars($sv['MaSV']); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="HoTen"><i class="fas fa-user"></i> Họ Tên</label>
                    <input type="text" id="HoTen" name="HoTen" value="<?php echo htmlspecialchars($sv['HoTen']); ?>" required placeholder="Nhập họ tên">
                </div>

                <div class="form-group">
                    <label for="GioiTinh"><i class="fas fa-venus-mars"></i> Giới Tính</label>
                    <select id="GioiTinh" name="GioiTinh">
                        <option value="">Chọn giới tính</option>
                        <option value="Nam" <?php echo $sv['GioiTinh'] === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                        <option value="Nữ" <?php echo $sv['GioiTinh'] === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                        <option value="Khác" <?php echo $sv['GioiTinh'] === 'Khác' ? 'selected' : ''; ?>>Khác</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="NgaySinh"><i class="fas fa-calendar-alt"></i> Ngày Sinh</label>
                    <input type="date" id="NgaySinh" name="NgaySinh" value="<?php echo htmlspecialchars($sv['NgaySinh']); ?>">
                </div>

                <div class="form-group">
                    <label for="Hinh"><i class="fas fa-image"></i> Hình</label>
                    <input type="text" id="Hinh" name="Hinh" value="<?php echo htmlspecialchars($sv['Hinh']); ?>" placeholder="Nhập đường dẫn hình ảnh">
                </div>

                <div class="form-group">
                    <label for="MaNganh"><i class="fas fa-graduation-cap"></i> Mã Ngành</label>
                    <input type="text" id="MaNganh" name="MaNganh" value="<?php echo htmlspecialchars($sv['MaNganh']); ?>" placeholder="Nhập mã ngành">
                </div>

                <div class="button-group">
                    <button type="submit" class="button"><i class="fas fa-save"></i> Lưu</button>
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
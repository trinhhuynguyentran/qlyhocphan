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

// Gán link hình trực tiếp cho mỗi sinh viên
$imageLinks = [
    '2180600527' => '../Content/images/huy.jpg',
    '21806123' => '../Content/images/nam 1.jpg',
    '21806222' => '../Content/images/nu 1.jpg',
    '21806234' => '../Content/images/nam 2.jpg',
    '21806456' => '../Content/images/nu 2.jpg',
    '21806555' => '../Content/images/nam 3.jpg',
    // Thêm các sinh viên khác và link hình tương ứng
    // Ví dụ: '1234567890' => '../Content/images/sv3.jpg',
];

// Lấy link hình dựa trên MaSV, nếu không có thì dùng hình mặc định
$imagePath = isset($imageLinks[$maSV]) ? $imageLinks[$maSV] : '../Content/images/default.jpg';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Sinh Viên</title>
    <link rel="stylesheet" href="../style.css">
    <!-- Thêm Font Awesome để sử dụng biểu tượng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-user"></i> Chi tiết Sinh Viên</h1>
        <p>Thông tin chi tiết của sinh viên</p>
    </header>

    <div class="container">
        <div class="detail-card">
            <div class="profile-image">
                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Hình sinh viên" onerror="this.src='../Content/images/default.jpg'">
            </div>
            <div class="info">
                <p><i class="fas fa-id-card"></i> <strong>Mã SV:</strong> <?php echo htmlspecialchars($sv['MaSV']); ?></p>
                <p><i class="fas fa-user"></i> <strong>Họ Tên:</strong> <?php echo htmlspecialchars($sv['HoTen']); ?></p>
                <p><i class="fas fa-venus-mars"></i> <strong>Giới Tính:</strong> <?php echo htmlspecialchars($sv['GioiTinh'] ?: 'Chưa xác định'); ?></p>
                <p><i class="fas fa-calendar-alt"></i> <strong>Ngày Sinh:</strong> <?php echo htmlspecialchars($sv['NgaySinh'] ?: 'Chưa xác định'); ?></p>
                <p><i class="fas fa-graduation-cap"></i> <strong>Mã Ngành:</strong> <?php echo htmlspecialchars($sv['MaNganh'] ?: 'Chưa xác định'); ?></p>
            </div>
            <div class="button-group">
                <a href="index.php" class="button secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
                <a href="../index.php" class="button secondary"><i class="fas fa-home"></i> Quay lại trang chủ</a>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 Hệ thống Quản lý Học phần. All rights reserved.</p>
    </footer>
</body>
</html>
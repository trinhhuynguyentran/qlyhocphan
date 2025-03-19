<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['MaSV'])) {
    header("Location: ../login.php");
    exit();
}

$maSV = $_SESSION['MaSV'];
$cart = $_SESSION['cart'] ?? [];

// Xử lý xóa học phần khỏi giỏ hàng tạm thời
if (isset($_GET['remove']) && !empty($cart)) {
    $maHP = $_GET['remove'];
    if (($key = array_search($maHP, $cart)) !== false) {
        unset($cart[$key]);
        $_SESSION['cart'] = array_values($cart); // Sắp xếp lại mảng
    }
    header("Location: cart.php");
    exit();
}

// Xử lý xóa toàn bộ giỏ hàng tạm thời
if (isset($_GET['clear']) && !empty($cart)) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit();
}

// Xử lý xóa học phần đã đăng ký từ cơ sở dữ liệu
if (isset($_GET['remove_registered'])) {
    $maHP = $_GET['remove_registered'];
    $stmt = $conn->prepare("
        DELETE FROM ChiTietDangKy 
        WHERE MaHP = ? AND MaDK IN (SELECT MaDK FROM DangKy WHERE MaSV = ?)
    ");
    $stmt->execute([$maHP, $maSV]);
    
    // Tăng lại SoLuongDuKien
    $stmt = $conn->prepare("UPDATE HocPhan SET SoLuongDuKien = SoLuongDuKien + 1 WHERE MaHP = ?");
    $stmt->execute([$maHP]);
    
    header("Location: cart.php");
    exit();
}

// Xử lý xóa toàn bộ học phần đã đăng ký
if (isset($_GET['clear_registered'])) {
    // Lấy tất cả MaHP để tăng lại SoLuongDuKien
    $stmt = $conn->prepare("
        SELECT MaHP FROM ChiTietDangKy 
        WHERE MaDK IN (SELECT MaDK FROM DangKy WHERE MaSV = ?)
    ");
    $stmt->execute([$maSV]);
    $hocPhans = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Tăng lại SoLuongDuKien cho từng học phần
    foreach ($hocPhans as $maHP) {
        $stmt = $conn->prepare("UPDATE HocPhan SET SoLuongDuKien = SoLuongDuKien + 1 WHERE MaHP = ?");
        $stmt->execute([$maHP]);
    }

    // Xóa tất cả đăng ký của sinh viên
    $stmt = $conn->prepare("DELETE FROM ChiTietDangKy WHERE MaDK IN (SELECT MaDK FROM DangKy WHERE MaSV = ?)");
    $stmt->execute([$maSV]);
    $stmt = $conn->prepare("DELETE FROM DangKy WHERE MaSV = ?");
    $stmt->execute([$maSV]);
    
    header("Location: cart.php");
    exit();
}

// Lấy danh sách học phần trong giỏ hàng tạm thời
$hocphans_temp = [];
if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $conn->prepare("SELECT * FROM HocPhan WHERE MaHP IN ($placeholders)");
    $stmt->execute($cart);
    $hocphans_temp = $stmt->fetchAll();
}

// Lấy danh sách học phần đã đăng ký của sinh viên
$stmt = $conn->prepare("
    SELECT hp.* 
    FROM HocPhan hp
    JOIN ChiTietDangKy ctdk ON hp.MaHP = ctdk.MaHP
    JOIN DangKy dk ON ctdk.MaDK = dk.MaDK
    WHERE dk.MaSV = ?
");
$stmt->execute([$maSV]);
$hocphans_registered = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng học phần</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-shopping-cart"></i> Giỏ hàng học phần</h1>
        <p>Danh sách học phần của bạn</p>
    </header>

    <div class="container">
        <div class="form-card">
            <!-- Hiển thị giỏ hàng tạm thời -->
            <?php if (!empty($hocphans_temp)): ?>
                <h3><i class="fas fa-cart-plus"></i> Học phần trong giỏ hàng (Chưa lưu)</h3>
                <table>
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên HP</th>
                        <th>Số tín chỉ</th>
                        <th>Thao tác</th>
                    </tr>
                    <?php foreach ($hocphans_temp as $hp): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hp['MaHP']); ?></td>
                        <td><?php echo htmlspecialchars($hp['TenHP']); ?></td>
                        <td><?php echo htmlspecialchars($hp['SoTinChi']); ?></td>
                        <td>
                            <a href="?remove=<?php echo urlencode($hp['MaHP']); ?>" class="button secondary" onclick="return confirm('Bạn có chắc chắn muốn xóa học phần này khỏi giỏ hàng?')">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <div class="button-group">
                    <a href="save.php" class="button"><i class="fas fa-save"></i> Lưu đăng ký</a>
                    <a href="?clear=1" class="button secondary" onclick="return confirm('Bạn có chắc chắn muốn xóa hết học phần trong giỏ hàng?')">
                        <i class="fas fa-trash-alt"></i> Xóa hết
                    </a>
                </div>
            <?php endif; ?>

            <!-- Hiển thị học phần đã đăng ký -->
            <h3><i class="fas fa-check-circle"></i> Học phần đã đăng ký</h3>
            <?php if (empty($hocphans_registered)): ?>
                <p class="error-message">-----</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên HP</th>
                        <th>Số tín chỉ</th>
                        <th>Thao tác</th>
                    </tr>
                    <?php foreach ($hocphans_registered as $hp): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hp['MaHP']); ?></td>
                        <td><?php echo htmlspecialchars($hp['TenHP']); ?></td>
                        <td><?php echo htmlspecialchars($hp['SoTinChi']); ?></td>
                        <td>
                            <a href="?remove_registered=<?php echo urlencode($hp['MaHP']); ?>" class="button secondary" onclick="return confirm('Bạn có chắc chắn muốn xóa học phần đã đăng ký này?')">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <div class="button-group">
                    <a href="?clear_registered=1" class="button secondary" onclick="return confirm('Bạn có chắc chắn muốn xóa hết học phần đã đăng ký?')">
                        <i class="fas fa-trash-alt"></i> Xóa hết
                    </a>
                </div>
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
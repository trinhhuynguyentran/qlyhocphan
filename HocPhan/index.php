<?php
session_start();
include '../includes/db.php';
$stmt = $conn->prepare("SELECT * FROM HocPhan");
$stmt->execute();
$hocphans = $stmt->fetchAll();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maHP = $_POST['MaHP'];
    if (!in_array($maHP, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $maHP;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký học phần</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Danh sách học phần</h1>
    <table>
        <tr>
            <th>Mã HP</th>
            <th>Tên HP</th>
            <th>Số tín chỉ</th>
            <th>Số lượng dự kiến</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($hocphans as $hp): ?>
        <tr>
            <td><?php echo $hp['MaHP']; ?></td>
            <td><?php echo $hp['TenHP']; ?></td>
            <td><?php echo $hp['SoTinChi']; ?></td>
            <td><?php echo isset($hp['SoLuongDuKien']) ? $hp['SoLuongDuKien'] : 0; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="MaHP" value="<?php echo $hp['MaHP']; ?>">
                    <button type="submit">Thêm vào giỏ</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="cart.php">Xem giỏ hàng</a>
    <a href="../index.php" class="button">Quay lại</a>
</body>
</html>
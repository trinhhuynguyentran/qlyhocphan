<?php
include '../includes/db.php';
$stmt = $conn->prepare("SELECT * FROM SinhVien");
$stmt->execute();
$sinhviens = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sinh viên</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Danh sách sinh viên</h1>
    <a href="create.php" class="button">Thêm sinh viên</a>
    <table>
        <tr>
            <th>Mã SV</th>
            <th>Họ Tên</th>
            <th>Giới Tính</th>
            <th>Ngày Sinh</th>
            <th>Ngành</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($sinhviens as $sv): ?>
        <tr>
            <td><?php echo $sv['MaSV']; ?></td>
            <td><?php echo $sv['HoTen']; ?></td>
            <td><?php echo $sv['GioiTinh']; ?></td>
            <td><?php echo $sv['NgaySinh']; ?></td>
            <td><?php echo $sv['MaNganh']; ?></td>
            <td>
                <a href="edit.php?MaSV=<?php echo $sv['MaSV']; ?>">Sửa</a>
                <a href="delete.php?MaSV=<?php echo $sv['MaSV']; ?>" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
                <a href="detail.php?MaSV=<?php echo $sv['MaSV']; ?>">Chi tiết</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="../index.php" class="button">Quay lại trang chủ</a>
</body>
</html>
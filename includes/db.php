<?php
$host = "localhost";
$dbname = "Test1";
$username = "root"; // Mặc định XAMPP
$password = "";     // Mặc định XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
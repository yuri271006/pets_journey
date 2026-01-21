<?php
/*
 * File cấu hình kết nối Cơ sở dữ liệu (Database)
 * Vị trí: config/db.php
 */

// 1. Thông tin cấu hình (Bạn sửa lại cho đúng với Host của bạn nhé)
$db_host = 'localhost';      // Máy chủ (Thường là localhost)
$db_name = 'petsiovn_pets_journey';   // Tên Database của game
$db_user = 'petsiovn_sever';           // Tên đăng nhập (XAMPP mặc định là root)
$db_pass = '#QPzm1027@@';               // Mật khẩu (XAMPP mặc định để trống)

try {
    // 2. Chuỗi kết nối (DSN) - Có sẵn charset utf8mb4 để không lỗi font Tiếng Việt
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    
    // Các tùy chọn tối ưu
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Báo lỗi chi tiết nếu có sự cố
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Lấy dữ liệu dạng mảng ['ten' => 'Gia Huy']
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Tăng bảo mật chống Hack SQL Injection
    ];

    // 3. Tạo kết nối
    $conn = new PDO($dsn, $db_user, $db_pass, $options);

    // Nếu code chạy đến đây nghĩa là kết nối thành công!
    // Biến $conn sẽ được dùng để truy vấn ở các file khác.

} catch (\PDOException $e) {
    // 4. Nếu lỗi thì dừng trang và báo lỗi
    // Khi chạy thật (Production) thì nên ẩn dòng $e->getMessage() đi nhé
    die("❌ Lỗi kết nối Database: " . $e->getMessage());
}
?>
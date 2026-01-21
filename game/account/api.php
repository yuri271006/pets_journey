<?php
// game/account/api.php
session_start();
require_once '../../config/db.php'; // Gọi kết nối DB

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // 1. Tìm user trong DB
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $data = $stmt->fetch();

    // 2. Kiểm tra mật khẩu
    if ($data && password_verify($pass, $data['password'])) {
        // Login thành công -> Lưu session
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['nickname'] = $data['nickname'];
        
        echo json_encode(['status' => 'success', 'msg' => 'Đăng nhập thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Sai tài khoản hoặc mật khẩu!']);
    }
    exit;
}

if ($action === 'register') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $nick = $_POST['nickname'] ?? 'Nhà Lữ Hành';

    // Kiểm tra trùng user
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$user]);
    if ($check->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'msg' => 'Tài khoản đã tồn tại!']);
        exit;
    }

    // Mã hóa mật khẩu & Lưu
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, nickname) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$user, $hash, $nick])) {
        echo json_encode(['status' => 'success', 'msg' => 'Đăng ký thành công! Hãy đăng nhập.']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Lỗi hệ thống!']);
    }
    exit;
}

// Mặc định trả về lỗi nếu không trúng action nào
echo json_encode(['status' => 'error', 'msg' => 'Hành động không hợp lệ']);
?>
<?php
/*
 * File: game/check_login.php
 * Nhiệm vụ: Kiểm tra session user. Nếu chưa đăng nhập -> Đá về app.php
 */

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Thay 'user_id' bằng tên biến session bạn dùng khi login thành công
if (!isset($_SESSION['user_id'])) {

    // 1. Kiểm tra xem đây có phải là request API (AJAX) không?
    $is_ajax = false;
    
    // Check header chuẩn hoặc tham số do bạn tự quy định
    if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
        || (isset($_REQUEST['is_api']) && $_REQUEST['is_api'] == 'true')) {
        $is_ajax = true;
    }

    // 2. Xử lý từ chối truy cập
    if ($is_ajax) {
        // ==> NẾU LÀ API: Trả về JSON để JS xử lý
        header('Content-Type: application/json');
        http_response_code(401); // Mã 401: Unauthorized
        echo json_encode([
            'status' => 'error',
            'msg' => 'Bạn chưa đăng nhập hoặc phiên đã hết hạn!',
            'action' => 'redirect',
            'target' => 'app.php'
        ]);
    } else {
        // ==> NẾU LÀ UI: Chuyển hướng trình duyệt
        // Lưu ý: Dùng đường dẫn tuyệt đối (/) để đảm bảo chạy đúng từ mọi thư mục con
        // Nếu web bạn nằm trong thư mục con (vd: pets.io.vn/game1), hãy sửa thành /game1/app.php
        header("Location: /app.php"); 
    }
    
    exit; // Dừng ngay, không cho chạy code phía sau
}

// Nếu đã đăng nhập, code sẽ chạy tiếp bình thường...
?>
<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../../auth.php';

// Kiểm tra đăng nhập
if (!check_login()) exit(json_encode(['status' => 'error', 'msg' => 'Unauthorized']));

// Tinh chỉnh: Chấp nhận cả POST và GET để hỗ trợ xem media trực tiếp
$request = array_merge($_POST, $_GET);

// Xác định Base Path dựa trên quyền hạn
$is_full = $_SESSION['file_full_access'] ?? false;
$root_path = realpath(__DIR__ . "/../../.."); 
$src_path = realpath($root_path . "/src");
$base_path = $is_full ? $root_path : $src_path;

$rel_path = $request['path'] ?? ''; // Chỉnh sửa: Sử dụng $request thay vì $_POST
$target_dir = realpath($base_path . DIRECTORY_SEPARATOR . $rel_path);

// Bảo mật
if (!$target_dir || strpos($target_dir, $base_path) !== 0) { 
    $target_dir = $base_path; 
}

$action = $request['action'] ?? ''; // Chỉnh sửa: Sử dụng $request
$response = ['status' => 'error', 'msg' => 'Hành động không xác định'];

// CHỨC NĂNG THÊM MỚI: Phục vụ tệp tin thô cho hình ảnh/âm thanh/video
if ($action === 'view_raw') {
    $file = $target_dir . DIRECTORY_SEPARATOR . ($request['name'] ?? '');
    if (file_exists($file) && !is_dir($file)) {
        $mime = mime_content_type($file); // Tự động nhận diện định dạng (image/png, video/mp4,...)
        header("Content-Type: " . $mime);
        header("Content-Length: " . filesize($file));
        readfile($file);
        exit;
    }
}

// Hàm hỗ trợ sao chép thư mục đệ quy (Giữ nguyên code cũ)
function recursive_copy($src, $dst) {
    if (is_dir($src)) {
        if (!file_exists($dst)) mkdir($dst, 0777, true);
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") recursive_copy("$src/$file", "$dst/$file");
        }
    } else {
        copy($src, $dst);
    }
}

switch ($action) {
    case 'list':
        $items = [];
        if (file_exists($target_dir)) {
            $files = array_diff(scandir($target_dir), ['.', '..']);
            foreach ($files as $file) {
                $full_path = $target_dir . DIRECTORY_SEPARATOR . $file;
                $items[] = [
                    'name' => $file,
                    'is_dir' => is_dir($full_path),
                    'ext' => strtolower(pathinfo($file, PATHINFO_EXTENSION))
                ];
            }
        }
        $response = ['status' => 'success', 'data' => $items];
        break;

    case 'create_folder':
        $new_dir = $target_dir . DIRECTORY_SEPARATOR . ($_POST['name'] ?? 'New Folder');
        if (!file_exists($new_dir)) { 
            mkdir($new_dir, 0777, true); 
            $response = ['status' => 'success', 'msg' => 'Đã tạo thư mục']; 
        } else {
            $response = ['status' => 'error', 'msg' => 'Thư mục đã tồn tại'];
        }
        break;

    case 'create_file':
        $new_file = $target_dir . DIRECTORY_SEPARATOR . ($_POST['name'] ?? 'new_file.txt');
        if (!file_exists($new_file)) {
            file_put_contents($new_file, '');
            $response = ['status' => 'success', 'msg' => 'Đã tạo tệp tin'];
        } else {
            $response = ['status' => 'error', 'msg' => 'Tệp tin đã tồn tại hoặc lỗi đặt tên'];
        }
        break;

    case 'copy':
    case 'cut':
        $source = $target_dir . DIRECTORY_SEPARATOR . $_POST['name'];
        if (file_exists($source)) {
            $_SESSION['clipboard'] = [
                'type' => $action,
                'source' => $source,
                'name' => $_POST['name']
            ];
            $response = ['status' => 'success', 'msg' => 'Đã lưu vào bộ nhớ tạm'];
        }
        break;

    case 'paste':
        $cb = $_SESSION['clipboard'] ?? null;
        if ($cb && file_exists($cb['source'])) {
            $dest = $target_dir . DIRECTORY_SEPARATOR . $cb['name'];
            if (file_exists($dest)) {
                $info = pathinfo($cb['name']);
                $dest = $target_dir . DIRECTORY_SEPARATOR . $info['filename'] . '_copy.' . ($info['extension'] ?? '');
            }
            if ($cb['type'] === 'copy') {
                recursive_copy($cb['source'], $dest);
                $response = ['status' => 'success', 'msg' => 'Đã sao chép'];
            } else {
                if (rename($cb['source'], $dest)) {
                    unset($_SESSION['clipboard']);
                    $response = ['status' => 'success', 'msg' => 'Đã di chuyển'];
                }
            }
        } else {
            $response = ['status' => 'error', 'msg' => 'Bộ nhớ tạm trống hoặc tệp gốc không tồn tại'];
        }
        break;

    case 'delete':
        $path = $target_dir . DIRECTORY_SEPARATOR . $_POST['name'];
        if (file_exists($path)) {
            if (is_dir($path)) {
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($files as $f) { $f->isDir() ? rmdir($f->getRealPath()) : unlink($f->getRealPath()); }
                rmdir($path);
            } else { unlink($path); }
            $response = ['status' => 'success', 'msg' => 'Đã xóa'];
        }
        break;

    case 'rename':
        rename($target_dir . DIRECTORY_SEPARATOR . $_POST['old'], $target_dir . DIRECTORY_SEPARATOR . $_POST['new']);
        $response = ['status' => 'success'];
        break;

    case 'read':
        $file = $target_dir . DIRECTORY_SEPARATOR . $_POST['name'];
        if (file_exists($file)) $response = ['status' => 'success', 'content' => file_get_contents($file)];
        break;

    case 'write':
        file_put_contents($target_dir . DIRECTORY_SEPARATOR . $_POST['name'], $_POST['content'] ?? '');
        $response = ['status' => 'success', 'msg' => 'Đã lưu'];
        break;

    case 'upload':
        if (isset($_FILES['file'])) {
            move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . DIRECTORY_SEPARATOR . $_FILES['file']['name']);
            $response = ['status' => 'success'];
        }
        break;
}

header('Content-Type: application/json');
echo json_encode($response);
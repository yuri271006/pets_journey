/**
 * config/check_app.js
 * Nhiệm vụ: Buộc người dùng phải cài App (Add to Home Screen) mới được chơi.
 * Nếu phát hiện đang chạy trên trình duyệt -> Chuyển hướng về trang hướng dẫn (index.php).
 */

(function() {
    // Hàm kiểm tra chế độ hiển thị
    function isRunningStandalone() {
        // 1. Kiểm tra chuẩn PWA (Android/PC)
        const isPWA = window.matchMedia('(display-mode: standalone)').matches;
        
        // 2. Kiểm tra iOS Safari (Khi đã thêm vào màn hình chính)
        const isIOS = window.navigator.standalone === true;
        
        // 3. Kiểm tra trường hợp Android TWA (Trusted Web Activity)
        const isTWA = document.referrer.includes('android-app://');

        return isPWA || isIOS || isTWA;
    }

    // Thực hiện kiểm tra ngay khi script chạy
    if (!isRunningStandalone()) {
        // Nếu phát hiện KHÔNG PHẢI APP (đang chạy trên trình duyệt)
        // console.warn("Truy cập trái phép từ trình duyệt. Đang chuyển hướng về trang cài đặt...");
        
        // Dùng replace() để thay thế lịch sử duyệt web 
        // (Người dùng bấm nút Back sẽ không quay lại được trang game này nữa)
        window.location.replace("index.php");
    } else {
        // console.log("Chế độ App đã được kích hoạt. Chúc mừng bạn!");
    }

})();
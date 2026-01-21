/*
 * config/fixed_keyboard.js
 * Chức năng: Giữ nguyên bố cục Game khi bàn phím ảo xuất hiện trên Mobile.
 */

(function() {
    // 1. Lưu lại kích thước màn hình chuẩn ban đầu
    let originalHeight = window.innerHeight;
    let originalWidth = window.innerWidth;

    // Hàm khóa body để không bị trình duyệt tự động đẩy lên
    function lockLayout() {
        // Chỉ áp dụng cho thiết bị di động (chiều rộng nhỏ hơn 1024px)
        if (window.innerWidth < 1024) {
            document.documentElement.style.height = originalHeight + 'px';
            document.body.style.height = originalHeight + 'px';
            document.body.style.width = '100%';
            
            // QUAN TRỌNG: position fixed giúp body "miễn nhiễm" với việc bàn phím đẩy layout
            document.body.style.position = 'fixed'; 
            document.body.style.overflow = 'hidden';
            document.body.style.top = '0';
            document.body.style.left = '0';
        }
    }

    // 2. Cập nhật lại kích thước chuẩn CHỈ KHI xoay màn hình
    window.addEventListener('orientationchange', function() {
        // Đợi 500ms để xoay xong hẳn rồi mới lấy kích thước mới
        setTimeout(() => {
            originalHeight = window.innerHeight;
            originalWidth = window.innerWidth;
            lockLayout();
            window.scrollTo(0, 0); // Reset scroll
        }, 500);
    });

    // 3. Xử lý khi người dùng bấm vào ô nhập liệu (Input/Textarea)
    document.addEventListener('focusin', function(e) {
        const target = e.target;
        if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA') {
            
            // Đánh dấu trạng thái đang nhập liệu
            document.body.classList.add('keyboard-active');

            // Đợi bàn phím nẩy lên hết (300ms) rồi cuộn ô input vào tầm nhìn
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }
    });

    // 4. Xử lý khi bàn phím đóng lại (Mất focus)
    document.addEventListener('focusout', function(e) {
        const target = e.target;
        if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA') {
            
            document.body.classList.remove('keyboard-active');
            
            // QUAN TRỌNG: Kéo màn hình về lại vị trí (0,0) để Game căn giữa
            window.scrollTo(0, 0);
            
            // Khóa lại layout lần nữa cho chắc chắn
            setTimeout(lockLayout, 100);
        }
    });

    // Chạy khóa layout ngay khi tải file
    lockLayout();

})();
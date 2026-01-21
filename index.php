<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Pets Journey | Cài đặt</title>
    <link rel="shortcut icon" href="https://pets.io.vn/src/logo/logo.jpg" type="image/x-icon">
    <link rel="manifest" href="manifest.json">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #ff9f43;
            --secondary: #ee5253;
            --bg-color: #2f3640;
            --text-color: #f5f6fa;
        }

        body, html {
            margin: 0; padding: 0;
            width: 100%; height: 100%;
            background-color: var(--bg-color);
            background-image: linear-gradient(135deg, #2f3640 0%, #192a56 100%);
            font-family: 'Fredoka', sans-serif;
            color: var(--text-color);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Hiệu ứng nền */
        .bg-pattern {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0.1;
            background-image: radial-gradient(#ffffff 2px, transparent 2px);
            background-size: 30px 30px;
            z-index: 0;
        }

        /* Container chính */
        .container {
            position: relative;
            z-index: 10;
            max-width: 600px;
            width: 90%;
            padding: 30px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .logo {
            width: 120px;
            height: 120px;
            border-radius: 25px;
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(255, 159, 67, 0.5);
            border: 3px solid #fff;
        }

        h1 {
            font-size: 28px;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary);
            text-shadow: 2px 2px 0 #000;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
            color: #dcdde1;
        }

        /* Hướng dẫn từng bước */
        .guide-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: left;
            display: none; /* Mặc định ẩn, JS sẽ hiện theo OS */
        }

        .guide-step {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 15px;
        }
        .guide-step:last-child { margin-bottom: 0; }

        .icon-box {
            width: 30px; height: 30px;
            background: #fff;
            color: #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Nút kiểm tra */
        .btn-check {
            background: linear-gradient(to bottom, #ff9f43, #e67e22);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 5px 0 #d35400, 0 10px 10px rgba(0,0,0,0.3);
            transition: all 0.2s;
            text-transform: uppercase;
            width: 100%;
            font-family: 'Fredoka', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .btn-check:active {
            transform: translateY(5px);
            box-shadow: 0 0 0 #d35400, 0 5px 5px rgba(0,0,0,0.3);
        }

        .btn-check i { margin-right: 8px; }

        /* Thông báo lỗi */
        .error-toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #ee5253;
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            font-weight: 600;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            width: max-content;
            max-width: 90%;
            z-index: 100;
        }
        .error-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

    </style>
</head>
<body>

    <div class="bg-pattern"></div>

    <div class="container">
        <img src="src/logo/logo.jpg" alt="Pets Journey" class="logo">
        
        <h1>Pets Journey</h1>
        <p id="sub-text">Ít phút để cài đặt cho trải nghiệm tối ưu hơn!</p>

        <div id="ios-guide" class="guide-box">
            <div class="guide-step">
                <div class="icon-box"><i class="fas fa-share-square"></i></div>
                <span>1. Nhấn nút <b>Chia sẻ</b> (ở dưới cùng)</span>
            </div>
            <div class="guide-step">
                <div class="icon-box"><i class="fas fa-plus-square"></i></div>
                <span>2. Chọn <b>Thêm vào MH chính</b></span>
            </div>
            <div class="guide-step">
                <div class="icon-box">3</div>
                <span>3. Nhấn <b>Thêm</b> và mở App ngoài màn hình</span>
            </div>
        </div>

        <div id="android-guide" class="guide-box">
            <div class="guide-step">
                <div class="icon-box"><i class="fas fa-ellipsis-v"></i></div>
                <span>1. Nhấn nút <b>Menu</b> (3 chấm góc trên)</span>
            </div>
            <div class="guide-step">
                <div class="icon-box"><i class="fas fa-mobile-alt"></i></div>
                <span>2. Chọn <b>Cài đặt ứng dụng</b> hoặc <b>Thêm vào màn hình chính</b></span>
            </div>
        </div>

        <div id="pc-guide" class="guide-box">
            <div class="guide-step">
                <div class="icon-box"><i class="fas fa-qrcode"></i></div>
                <span>Vui lòng sử dụng điện thoại hoặc mở trong ứng dụng để trải nghiệm tốt nhất!</span>
            </div>
        </div>

        <button class="btn-check" onclick="checkAppMode()">
            <i class="fas fa-play-circle"></i> Vào Game Ngay
        </button>
    </div>

    <div id="toast" class="error-toast">
        <i class="fas fa-exclamation-triangle"></i> Vui lòng cài đặt để chơi!
    </div>

    <script>
        // 1. Kiểm tra xem đang chạy trên Browser hay App
        function isRunningStandalone() {
            return (window.matchMedia('(display-mode: standalone)').matches) || 
                   (window.navigator.standalone) || 
                   document.referrer.includes('android-app://');
        }

        // 2. Tự động chuyển trang nếu đã là App
        if (isRunningStandalone()) {
            window.location.href = "app.php";
        }

        // 3. Detect thiết bị để hiện hướng dẫn phù hợp
        function detectDevice() {
            const userAgent = navigator.userAgent || navigator.vendor || window.opera;
            const iosGuide = document.getElementById('ios-guide');
            const androidGuide = document.getElementById('android-guide');
            const pcGuide = document.getElementById('pc-guide');
            const subText = document.getElementById('sub-text');

            if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                // Là iOS
                iosGuide.style.display = 'block';
            } else if (/android/i.test(userAgent)) {
                // Là Android
                androidGuide.style.display = 'block';
            } else {
                // Là PC hoặc khác
                pcGuide.style.display = 'block';
                subText.innerText = "Game thiết kế cho moblie, trải nghiệm tốt nhất trên điện thoại!";
            }
        }

        // 4. Hàm xử lý nút bấm
        function checkAppMode() {
            if (isRunningStandalone()) {
                // Nếu đã là App (Lý thuyết code trên đã redirect, nhưng check lại cho chắc)
                window.location.href = "app.php";
            } else {
                // Nếu vẫn ở trình duyệt -> Báo lỗi
                showToast("Vui lòng làm theo hướng dẫn ở trên để cài đặt!");
                
                // Rung nhẹ điện thoại để nhắc nhở (nếu hỗ trợ)
                if (navigator.vibrate) navigator.vibrate(200);
            }
        }

        function showToast(msg) {
            const t = document.getElementById('toast');
            t.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${msg}`;
            t.classList.add('show');
            setTimeout(() => { t.classList.remove('show'); }, 3000);
        }

        // Chạy detect khi tải trang
        detectDevice();

    </script>
</body>
</html>
<?php
// Khởi động session ngay đầu file
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <!--Check App-->
    <script src="config/check_app.js"></script>
    <link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Pets Journey</title>
    <!--Liên kết các css-->
    <link rel="stylesheet" href="config/screen.css">
    <link rel="stylesheet" href="config/touch.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="https://pets.io.vn/src/logo/logo.png" type="image/x-icon">
</head>

<body>

    <div id="rotate-warning">
        <i class="fas fa-mobile-alt"></i>
        <h2>Xoay ngang thiết bị!</h2>
        <p>Trò chơi chỉ hỗ trợ màn hình ngang</p>
    </div>

    <div id="game-container">

        <div id="safegame">
            <?php if (!isset($_SESSION['user_id'])): ?>

                <?php include 'game/account/ui.php'; ?>

            <?php else: ?>

                <div style="position: absolute; top: 20px; left: 20px; color: white; font-weight: bold; font-size: 24px; text-shadow: 2px 2px 0 #000;">
                    <i class="fas fa-user-circle"></i>
                    Xin chào, <?php echo htmlspecialchars($_SESSION['nickname']); ?>!
                </div>

                <a href="game/account/logout.php" style="position: absolute; top: 20px; right: 20px; color: #ff6b6b; text-decoration: none; font-weight: bold; font-size: 20px;">
                    <i class="fas fa-sign-out-alt"></i> Thoát
                </a>

            <?php endif; ?>
        </div>

    </div>
    <!--Liên kết các mô đun-->
    <script src="config/screen.js"></script>
    <script src="config/touch.js"></script>
</body>

</html>
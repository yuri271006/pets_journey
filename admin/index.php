<?php 
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets Journey | QUẢN TRỊ</title>
    <link rel="shortcut icon" href="https://pets.io.vn/src/logo/logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-text: #94a3b8;
            --bg-main: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --border-color: #e2e8f0;
        }

        * { box-sizing: border-box; }

        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0; 
            display: flex; 
            height: 100vh; 
            background: var(--bg-main); 
            color: var(--text-main);
            overflow: hidden;
        }

        /* Sidebar Styles */
        .sidebar { 
            width: 260px; 
            background: var(--sidebar-bg); 
            color: white; 
            display: flex; 
            flex-direction: column;
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            z-index: 100;
        }

        .sidebar-header {
            padding: 30px 25px;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 1px;
            border-bottom: 1px solid #1e293b;
            color: #fff;
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 0;
        }

        .sidebar a { 
            color: var(--sidebar-text); 
            text-decoration: none; 
            display: flex; 
            align-items: center;
            gap: 12px;
            padding: 14px 25px; 
            transition: all 0.2s;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .sidebar a i { width: 20px; font-size: 1.1rem; }

        .sidebar a:hover, .sidebar a.active { 
            background: var(--sidebar-hover); 
            color: white;
            border-left: 4px solid var(--primary);
            padding-left: 21px;
        }

        .sidebar a.logout-btn {
            color: #fb7185;
            margin-top: auto;
            border-top: 1px solid #1e293b;
        }

        .sidebar a.logout-btn:hover {
            background: #451a1a;
            color: #fff;
        }

        /* Main Content Styles */
        .main { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            overflow: hidden;
        }

        .top-bar {
            height: 70px;
            background: white;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 40px;
            justify-content: space-between;
        }

        .view-area { 
            flex: 1;
            padding: 40px; 
            overflow-y: auto;
            scrollbar-width: thin;
        }

        /* Login Form Styles */
        .login-overlay {
            position: fixed;
            inset: 0;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .login-card { 
            background: white; 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            border: 1px solid var(--border-color);
        }

        .login-card h2 { margin: 0 0 10px 0; font-weight: 700; color: #0f172a; }
        .login-card p { margin-bottom: 30px; color: #64748b; font-size: 0.9rem; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 0.85rem; font-weight: 600; color: #475569; }

        input[type="password"] { 
            width: 100%; 
            padding: 12px 16px; 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            font-size: 1rem;
            transition: border-color 0.2s;
            outline: none;
        }

        input[type="password"]:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

        .btn-login { 
            width: 100%; 
            padding: 12px; 
            background: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s;
        }

        .btn-login:hover { background: var(--primary-hover); }

        .error-msg {
            background: #fff1f2;
            color: #e11d48;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-top: 15px;
            text-align: center;
            border: 1px solid #ffe4e6;
        }

        h1 { margin: 0; font-size: 1.75rem; font-weight: 700; color: #0f172a; }
    </style>
</head>
<body>

<?php if (!check_login()): ?>
    <div class="login-overlay">
        <div class="login-card">
            <h2>HỆ THỐNG QUẢN TRỊ</h2>
            <p>Vui lòng xác nhận để tiếp tục!</p>
            <form method="POST">
                <div class="form-group">
                    <label>MẬT KHẨU TRUY CẬP</label>
                    <input type="password" name="password" placeholder="••••••••" required autofocus>
                </div>
                <button type="submit" name="login" class="btn-login">Vào hệ thống</button>
                <?php if (isset($error)): ?>
                    <div class="error-msg"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-paw"></i> PETS JOURNEY
        </div>
        <div class="sidebar-menu">
            <?php $view = $_GET['view'] ?? 'dashboard'; ?>
            <a href="?view=dashboard" class="<?php echo $view === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-columns"></i> TỔNG QUAN
            </a>
            <a href="?view=files" class="<?php echo $view === 'files' ? 'active' : ''; ?>">
                <i class="fas fa-folder-tree"></i> MÃ NGUỒN
            </a>
            <a href="?logout=1" class="logout-btn">
                <i class="fas fa-power-off"></i> ĐĂNG XUẤT
            </a>
        </div>
    </div>

    <div class="main">
        <div class="top-bar">
            <h1><?php echo $view === 'files' ? 'Quản lý File' : 'SYSTEM'; ?></h1>
            <div style="font-size: 0.9rem; color: #64748b;">
                <i class="fas fa-user-shield"></i> ADMIN 
            </div>
        </div>
        <div class="view-area">
            <?php 
                if ($view === 'files') {
                    include 'function/file/ui_file.php';
                } else {
                    include "note.php";
                }
            ?>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
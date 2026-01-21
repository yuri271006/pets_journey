<style>
    /* Wrapper để căn giữa màn hình */
    .auth-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Hộp thoại chính hiệu ứng kính mờ */
    .auth-box {
        width: 900px; /* Rộng hơn để thoáng */
        padding: 60px 80px;
        border-radius: 40px;
        
        /* Hiệu ứng nền trắng mờ trong suốt */
        background: rgba(255, 255, 255, 0.15); 
        backdrop-filter: blur(20px); /* Làm mờ hậu cảnh */
        -webkit-backdrop-filter: blur(20px); /* Cho Safari */
        
        /* Viền sáng nhẹ tạo khối */
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        
        text-align: center;
        color: white;
        transition: height 0.3s ease;
    }

    /* Tiêu đề */
    .auth-title {
        font-size: 80px; /* Font to theo chuẩn Game */
        font-weight: 800;
        margin-bottom: 40px;
        text-transform: uppercase;
        color: #fff;
        text-shadow: 0 5px 15px rgba(0,0,0,0.2);
        letter-spacing: 2px;
    }

    /* Ô nhập liệu hiện đại */
    .auth-input-group {
        position: relative;
        margin-bottom: 30px;
    }

    .auth-input {
        width: 100%;
        padding: 25px 30px 25px 80px; /* Padding trái lớn để chứa icon */
        font-size: 40px;
        border-radius: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(0, 0, 0, 0.2); /* Nền tối nhẹ để chữ trắng nổi bật */
        color: #fff;
        outline: none;
        transition: all 0.3s;
        font-family: 'Nunito', sans-serif;
    }

    .auth-input::placeholder { color: rgba(255, 255, 255, 0.6); }

    .auth-input:focus {
        background: rgba(0, 0, 0, 0.4);
        border-color: #ff9f43;
        box-shadow: 0 0 15px rgba(255, 159, 67, 0.4);
    }

    /* Icon bên trong input */
    .input-icon {
        position: absolute;
        left: 25px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 40px;
        color: rgba(255, 255, 255, 0.7);
    }

    /* Nút bấm Gradient */
    .auth-btn {
        width: 100%;
        padding: 25px;
        font-size: 45px;
        font-weight: 800;
        border-radius: 20px;
        border: none;
        cursor: pointer;
        margin-top: 20px;
        text-transform: uppercase;
        color: white;
        transition: transform 0.2s, box-shadow 0.2s;
        font-family: 'Nunito', sans-serif;
        
        /* Gradient cam chủ đạo */
        background: linear-gradient(135deg, #ff9f43, #ee5253);
        box-shadow: 0 10px 20px rgba(238, 82, 83, 0.3);
    }

    .auth-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(238, 82, 83, 0.4);
    }

    .auth-btn:active { transform: scale(0.98); }

    .btn-green {
        background: linear-gradient(135deg, #2ed573, #26de81);
        box-shadow: 0 10px 20px rgba(46, 213, 115, 0.3);
    }

    /* Link chuyển đổi */
    .switch-text {
        margin-top: 40px;
        font-size: 35px;
        color: rgba(255, 255, 255, 0.9);
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
    }
    .switch-text:hover { color: #ff9f43; text-decoration: underline; }

    /* Khu vực thông báo lỗi/thành công (Nằm ngay trong hộp) */
    .auth-msg {
        margin-bottom: 30px;
        padding: 20px;
        border-radius: 15px;
        font-size: 35px;
        font-weight: 700;
        display: none; /* Mặc định ẩn */
        animation: fadeIn 0.3s ease-in-out;
    }
    
    .msg-error { background: rgba(235, 77, 75, 0.2); color: #ff6b6b; border: 2px solid #ff6b6b; }
    .msg-success { background: rgba(46, 213, 115, 0.2); color: #7bed9f; border: 2px solid #7bed9f; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="auth-wrapper">
    <div class="auth-box">
        
        <div id="auth-msg" class="auth-msg"></div>

        <div id="login-form">
            <div class="auth-title"><i class="fas fa-sign-in-alt"></i> Đăng Nhập</div>
            
            <div class="auth-input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" id="l_user" class="auth-input" placeholder="Tên tài khoản...">
            </div>
            
            <div class="auth-input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="l_pass" class="auth-input" placeholder="Mật khẩu...">
            </div>

            <button class="auth-btn" onclick="doLogin()">Vào Game Ngay</button>
            
            <div class="switch-text" onclick="toggleForm('register')">
                Chưa có tài khoản? <b>Đăng ký miễn phí</b>
            </div>
        </div>

        <div id="register-form" style="display: none;">
            <div class="auth-title"><i class="fas fa-user-plus"></i> Tạo Tài Khoản</div>
            
            <div class="auth-input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" id="r_user" class="auth-input" placeholder="Tên đăng nhập...">
            </div>
            
            <div class="auth-input-group">
                <i class="fas fa-id-card input-icon"></i>
                <input type="text" id="r_nick" class="auth-input" placeholder="Biệt danh nhân vật...">
            </div>

            <div class="auth-input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="r_pass" class="auth-input" placeholder="Mật khẩu bảo vệ...">
            </div>

            <button class="auth-btn btn-green" onclick="doRegister()">Hoàn Tất Đăng Ký</button>
            
            <div class="switch-text" onclick="toggleForm('login')">
                Đã có tài khoản? <b>Quay lại đăng nhập</b>
            </div>
        </div>

    </div>
</div>

<script>
    // Hàm hiển thị thông báo trực tiếp trong hộp thoại
    function showMsg(text, type = 'error') {
        const msgBox = document.getElementById('auth-msg');
        msgBox.innerHTML = type === 'success' ? `<i class="fas fa-check-circle"></i> ${text}` : `<i class="fas fa-exclamation-triangle"></i> ${text}`;
        
        // Reset class
        msgBox.className = 'auth-msg';
        msgBox.classList.add(type === 'success' ? 'msg-success' : 'msg-error');
        
        // Hiện lên
        msgBox.style.display = 'block';
    }

    // Hàm chuyển đổi giữa 2 form
    function toggleForm(target) {
        // Ẩn thông báo cũ
        document.getElementById('auth-msg').style.display = 'none';
        
        const l = document.getElementById('login-form');
        const r = document.getElementById('register-form');

        if (target === 'register') {
            l.style.display = 'none';
            r.style.display = 'block';
        } else {
            l.style.display = 'block';
            r.style.display = 'none';
        }
    }

    // Xử lý Đăng Nhập
    async function doLogin() {
        const u = document.getElementById('l_user').value.trim();
        const p = document.getElementById('l_pass').value.trim();

        if(!u || !p) return showMsg("Vui lòng nhập đầy đủ thông tin!");

        // Hiện trạng thái đang xử lý
        showMsg("Đang kết nối đến máy chủ...", "success");

        const fd = new FormData();
        fd.append('action', 'login');
        fd.append('username', u);
        fd.append('password', p);

        try {
            const res = await fetch('game/account/api.php', { method: 'POST', body: fd });
            const json = await res.json();
            
            if(json.status === 'success') {
                showMsg(json.msg, 'success');
                setTimeout(() => location.reload(), 1000); // Reload sau 1s để người dùng đọc thông báo
            } else {
                showMsg(json.msg, 'error');
            }
        } catch (e) {
            showMsg("Lỗi kết nối mạng!", 'error');
        }
    }

    // Xử lý Đăng Ký
    async function doRegister() {
        const u = document.getElementById('r_user').value.trim();
        const n = document.getElementById('r_nick').value.trim();
        const p = document.getElementById('r_pass').value.trim();

        if(!u || !p || !n) return showMsg("Vui lòng nhập đầy đủ thông tin!");

        showMsg("Đang tạo nhân vật...", "success");

        const fd = new FormData();
        fd.append('action', 'register');
        fd.append('username', u);
        fd.append('nickname', n);
        fd.append('password', p);

        try {
            const res = await fetch('game/account/api.php', { method: 'POST', body: fd });
            const json = await res.json();
            
            if(json.status === 'success') {
                showMsg(json.msg, 'success');
                // Chuyển ngay về form đăng nhập sau 1.5s
                setTimeout(() => {
                    toggleForm('login');
                    document.getElementById('l_user').value = u; // Điền sẵn user cho tiện
                }, 1500);
            } else {
                showMsg(json.msg, 'error');
            }
        } catch (e) {
            showMsg("Lỗi kết nối mạng!", 'error');
        }
    }
</script>
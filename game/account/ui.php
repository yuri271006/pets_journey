<style>
    .login-box {
        background: rgba(0, 0, 0, 0.8);
        padding: 40px;
        border-radius: 20px;
        border: 2px solid #ff9f43;
        text-align: center;
        width: 400px;
        color: white;
        box-shadow: 0 0 20px rgba(255, 159, 67, 0.5);
        backdrop-filter: blur(5px);
    }
    .game-input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid #576574;
        color: white;
        border-radius: 8px;
        outline: none;
    }
    .game-input:focus { border-color: #ff9f43; }
    .game-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(to bottom, #ff9f43, #e67e22);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
        text-shadow: 1px 1px 0 #d35400;
    }
    .game-btn:active { transform: scale(0.98); }
    .switch-link {
        margin-top: 15px;
        font-size: 14px;
        color: #c8d6e5;
        cursor: pointer;
        text-decoration: underline;
    }
</style>

<div class="login-box" id="login-form">
    <h2 style="margin-top:0; color:#ff9f43">ĐĂNG NHẬP</h2>
    <input type="text" id="l_user" class="game-input" placeholder="Tên tài khoản">
    <input type="password" id="l_pass" class="game-input" placeholder="Mật khẩu">
    <button class="game-btn" onclick="doLogin()">VÀO GAME</button>
    <div class="switch-link" onclick="toggleForm()">Chưa có tài khoản? Đăng ký ngay</div>
</div>

<div class="login-box" id="register-form" style="display:none">
    <h2 style="margin-top:0; color:#2ecc71">ĐĂNG KÝ</h2>
    <input type="text" id="r_user" class="game-input" placeholder="Tên tài khoản mới">
    <input type="text" id="r_nick" class="game-input" placeholder="Tên nhân vật (Nickname)">
    <input type="password" id="r_pass" class="game-input" placeholder="Mật khẩu">
    <button class="game-btn" style="background: linear-gradient(to bottom, #2ecc71, #27ae60);" onclick="doRegister()">TẠO TÀI KHOẢN</button>
    <div class="switch-link" onclick="toggleForm()">Quay lại Đăng nhập</div>
</div>

<script>
    function toggleForm() {
        const l = document.getElementById('login-form');
        const r = document.getElementById('register-form');
        if (l.style.display === 'none') { l.style.display = 'block'; r.style.display = 'none'; }
        else { l.style.display = 'none'; r.style.display = 'block'; }
    }

    async function doLogin() {
        const u = document.getElementById('l_user').value;
        const p = document.getElementById('l_pass').value;
        if(!u || !p) return alert("Vui lòng nhập đủ thông tin!");

        const fd = new FormData();
        fd.append('action', 'login');
        fd.append('username', u);
        fd.append('password', p);

        const res = await fetch('game/account/api.php', { method: 'POST', body: fd });
        const json = await res.json();
        
        if(json.status === 'success') {
            location.reload(); // Load lại trang để vào game
        } else {
            alert(json.msg);
        }
    }

    async function doRegister() {
        const u = document.getElementById('r_user').value;
        const n = document.getElementById('r_nick').value;
        const p = document.getElementById('r_pass').value;
        if(!u || !p) return alert("Vui lòng nhập đủ thông tin!");

        const fd = new FormData();
        fd.append('action', 'register');
        fd.append('username', u);
        fd.append('nickname', n);
        fd.append('password', p);

        const res = await fetch('game/account/api.php', { method: 'POST', body: fd });
        const json = await res.json();
        alert(json.msg);
        if(json.status === 'success') toggleForm();
    }
</script>
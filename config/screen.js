(function() {
    const GAME_WIDTH = 2796;
    const GAME_HEIGHT = 1290;
    const container = document.getElementById('game-container');
    const warning = document.getElementById('rotate-warning');

    function resizeGame() {
        const w = window.innerWidth;
        const h = window.innerHeight;

        // 1. Kiểm tra hướng màn hình
        if (h > w) { 
            warning.style.display = 'flex';
            container.style.visibility = 'hidden';
            return;
        } else { 
            warning.style.display = 'none';
            container.style.visibility = 'visible';
        }

        // 2. Tính toán tỷ lệ Scale để game vừa khít màn hình (Fit)
        // Dùng Math.max nếu muốn game lấp đầy màn hình (mất một chút cạnh)
        // Dùng Math.min nếu muốn hiển thị trọn vẹn (có thể có viền đen)
        const scale = Math.min(w / GAME_WIDTH, h / GAME_HEIGHT);

        // 3. Áp dụng
        container.style.transform = `translate(-50%, -50%) scale(${scale})`;
    }

    window.addEventListener('resize', resizeGame);
    window.addEventListener('orientationchange', resizeGame);
    window.addEventListener('DOMContentLoaded', resizeGame);
})();
// ... (Code cũ giữ nguyên)

// --- BỔ SUNG: CHẶN ZOOM & MENU ---

// 1. Chặn menu chuột phải (Right Click)
document.addEventListener("contextmenu", (event) => event.preventDefault());

// 2. Chặn cử chỉ Zoom đa điểm (Pinch to Zoom) - Quan trọng cho iOS
// iOS bỏ qua user-scalable=no, nên phải dùng JS để chặn sự kiện gesture
document.addEventListener("gesturestart", function (e) {
  e.preventDefault();
});
document.addEventListener("gesturechange", function (e) {
  e.preventDefault();
});
document.addEventListener("gestureend", function (e) {
  e.preventDefault();
});

// 3. Chặn Double Tap (Nhấp đúp để zoom)
let lastTouchEnd = 0;
document.addEventListener(
  "touchend",
  function (event) {
    const now = new Date().getTime();
    // Nếu 2 lần tap cách nhau dưới 300ms -> Chặn
    if (now - lastTouchEnd <= 300) {
      event.preventDefault();
    }
    lastTouchEnd = now;
  },
  { passive: false },
); // passive: false để cho phép preventDefault

// 4. Chặn Zoom bằng bàn phím (Ctrl + Scroll) trên PC (nếu cần)
document.addEventListener(
  "wheel",
  function (e) {
    if (e.ctrlKey) e.preventDefault();
  },
  { passive: false },
);

// 5. Chặn Zoom bằng phím tắt (Ctrl + / Ctrl -)
document.addEventListener("keydown", function (e) {
  if (
    (e.ctrlKey || e.metaKey) &&
    (e.key === "+" || e.key === "-" || e.key === "=")
  ) {
    e.preventDefault();
  }
});

<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Sửa lỗi đường dẫn: Lấy đường dẫn tuyệt đối đến auth.php
require_once dirname(__DIR__, 2) . '/auth.php';
// Kiểm tra đăng nhập
if (!check_login()) exit(json_encode(['status' => 'error', 'msg' => 'Unauthorized']));
if (isset($_POST['unlock_files']) && ($_POST['file_pass'] ?? '') === "dexs") { $_SESSION['file_full_access'] = true; }
$is_full = $_SESSION['file_full_access'] ?? false;
$current_rel_path = $_GET['path'] ?? '';
?>

<style>
    :root {
        --fm-primary: #2563eb;
        --fm-bg: #ffffff;
        --fm-border: #e2e8f0;
        --fm-text: #1e293b;
        --fm-text-muted: #64748b;
        --folder-color: #f59e0b;
        --file-color: #6366f1;
        --radius: 10px;
    }

    .fm-wrapper { 
        background: var(--fm-bg); border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); 
        display: flex; flex-direction: column; height: 85vh; border: 1px solid var(--fm-border);
    }

    .fm-toolbar { 
        padding: 12px 20px; background: #f8fafc; border-bottom: 1px solid var(--fm-border); 
        display: flex; gap: 8px; align-items: center; flex-wrap: wrap;
    }

    .breadcrumb-nav {
        display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--fm-text-muted);
        flex: 1; overflow-x: auto; white-space: nowrap;
    }

    .breadcrumb-item { cursor: pointer; padding: 4px 8px; border-radius: 4px; transition: 0.2s; }
    .breadcrumb-item:hover { background: #e2e8f0; color: var(--fm-primary); }
    .breadcrumb-item.active { font-weight: 600; color: var(--fm-text); cursor: default; }

    .fm-grid { 
        display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); 
        gap: 16px; padding: 24px; overflow-y: auto; 
    }

    .file-card { 
        padding: 16px; border-radius: var(--radius); border: 1px solid transparent; 
        text-align: center; cursor: pointer; transition: 0.2s; background: #fff;
    }

    .file-card:hover { background: #f0f7ff; border-color: #bfdbfe; transform: translateY(-2px); }
    .file-card i { font-size: 48px; display: block; margin-bottom: 12px; }
    .file-card .name { font-size: 13px; color: var(--fm-text); font-weight: 500; word-break: break-all; }

    .c-modal { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); display: none; z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
    .c-modal-box { background: white; width: 95%; max-width: 900px; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .c-modal-header { padding: 16px 24px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
    .c-modal-body { padding: 24px; max-height: 75vh; overflow-y: auto; width: 100%; }
    
    #code-editor { 
        width: 100%; height: 500px; font-family: 'Fira Code', 'Consolas', monospace; 
        padding: 16px; border-radius: 8px; background: #0f172a; color: #e2e8f0; 
        line-height: 1.6; resize: none; border: none; font-size: 14px; white-space: pre;
    }

    .btn { border: none; padding: 10px 14px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; transition: 0.2s; }
    .btn-primary { background: var(--fm-primary); color: white; }
    .btn-outline { background: white; border: 1px solid var(--fm-border); color: var(--fm-text-muted); }
    .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; color: var(--fm-text); }

    .fa-folder { color: var(--folder-color); }
    .fa-file-code { color: var(--file-color); }
    .fa-file-image { color: #ec4899; }
    .fa-file-video { color: #ef4444; }
    .fa-file-audio { color: #10b981; }

    .c-toast { position: fixed; top: 24px; left: 50%; transform: translateX(-50%) translateY(-100px); padding: 12px 24px; border-radius: 50px; color: white; transition: 0.4s; z-index: 10000; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-weight: 500; }
</style>

<div class="fm-wrapper">
    <div class="fm-toolbar">
        <button class="btn btn-outline" onclick="goBack()" <?php echo empty($current_rel_path) ? 'disabled' : ''; ?> title="Quay lại"><i class="fas fa-chevron-left"></i></button>
        <nav class="breadcrumb-nav" id="breadcrumb-list"></nav>
        <button class="btn btn-outline" onclick="promptCreateFile()" title="Tạo tệp mới"></i>Tạo tệp</button>
        <button class="btn btn-outline" onclick="promptCreateFolder()" title="Tạo thư mục mới"></i>Tạo thư mục</button>
        <button class="btn btn-outline" onclick="doPaste()" title="Dán"><i class="fas fa-paste"></i> Dán</button>
        <button class="btn btn-primary" onclick="triggerUpload()"><i class="fas fa-upload"></i> Tải lên</button>
    </div>

    <?php if (!$is_full): ?>
    <div style="padding: 10px 24px; background: #fffbeb; font-size: 12px; border-bottom: 1px solid #fde68a; color: #92400e; display: flex; align-items: center; justify-content: space-between;">
        <span><i class="fas fa-shield-alt"></i> Giới hạn: src/</span>
        <form method="POST" style="display:flex; gap:8px;">
            <input type="password" name="file_pass" placeholder="ROOT" style="width:120px; border:1px solid #fde68a; border-radius:4px; padding:4px 8px;"> 
            <button type="submit" name="unlock_files" class="btn btn-primary" style="padding:4px 12px; font-size:11px">Root</button>
        </form>
    </div>
    <?php endif; ?>

    <div class="fm-grid" id="fm-list"></div>
</div>

<div id="main-modal" class="c-modal">
    <div class="c-modal-box">
        <div class="c-modal-header">
            <h4 id="modal-title" style="margin:0; font-size:16px;">Tiêu đề</h4>
            <span style="cursor:pointer; font-size:20px;" onclick="closeModal()">&times;</span>
        </div>
        <div class="c-modal-body" id="modal-body"></div>
        <div id="modal-footer" style="padding:16px 24px; border-top:1px solid #eee; display:flex; justify-content:flex-end; gap:12px; background: #f8fafc;"></div>
    </div>
</div>

<input type="file" id="hidden-upload" hidden onchange="doUpload(this)">
<div id="toast" class="c-toast">Thông báo</div>

<script>
let currentPath = "<?php echo $current_rel_path; ?>";
let activeFile = "";

// Hàm xử lý an toàn nội dung tệp (Tránh lỗi vỡ giao diện)
function escapeHTML(str) {
    return str.replace(/[&<>"']/g, function(m) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[m];
    });
}

function toast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.innerText = msg;
    t.style.background = type === 'success' ? '#059669' : '#dc2626';
    t.style.transform = 'translateX(-50%) translateY(0)';
    setTimeout(() => { t.style.transform = 'translateX(-50%) translateY(-100px)'; }, 3000);
}

function showModal(title, content, footerHtml = '') {
    document.getElementById('modal-title').innerText = title;
    document.getElementById('modal-body').innerHTML = content;
    document.getElementById('modal-footer').innerHTML = footerHtml;
    document.getElementById('main-modal').style.display = 'flex';
}

function closeModal() { document.getElementById('main-modal').style.display = 'none'; }

function renderBreadcrumbs() {
    const list = document.getElementById('breadcrumb-list');
    list.innerHTML = `<span class="breadcrumb-item" onclick="navigateToPath('')">Root</span>`;
    if (currentPath) {
        const parts = currentPath.split('/');
        let pathAccumulator = '';
        parts.forEach((part, index) => {
            pathAccumulator += (index === 0 ? '' : '/') + part;
            list.innerHTML += ` <i class="fas fa-chevron-right" style="font-size:10px; opacity:0.5"></i> `;
            const isActive = index === parts.length - 1;
            list.innerHTML += `<span class="breadcrumb-item ${isActive ? 'active' : ''}" 
                                     onclick="${isActive ? '' : `MapsToPath('${pathAccumulator}')` }">${part}</span>`;
        });
    }
}

async function api(action, params = {}) {
    const fd = new FormData();
    fd.append('action', action);
    fd.append('path', currentPath);
    for (const key in params) {
        if (params[key] instanceof File) fd.append(key, params[key]);
        else fd.append(key, params[key]);
    }
    const res = await fetch('function/file/api_file.php', { method: 'POST', body: fd });
    return await res.json();
}

async function loadFiles() {
    renderBreadcrumbs();
    const json = await api('list');
    const container = document.getElementById('fm-list');
    container.innerHTML = '';
    
    if (json.data) {
        json.data.forEach(item => {
            let icon = item.is_dir ? 'fa-folder' : 'fa-file';
            const exts = {
                image: ['jpg','png','webp','gif'],
                video: ['mp4','webm'],
                audio: ['mp3','wav'],
                code: ['php','js','css','html','txt','json','py','sql']
            };
            if(exts.image.includes(item.ext)) icon = 'fa-file-image';
            else if(exts.video.includes(item.ext)) icon = 'fa-file-video';
            else if(exts.audio.includes(item.ext)) icon = 'fa-file-audio';
            else if(exts.code.includes(item.ext)) icon = 'fa-file-code';

            const div = document.createElement('div');
            div.className = 'file-card';
            div.innerHTML = `<i class="fas ${icon}"></i><div class="name">${item.name}</div>`;
            div.onclick = () => item.is_dir ? openFolder(item.name) : previewFile(item.name, item.ext);
            div.oncontextmenu = (e) => { e.preventDefault(); showFileOptions(item.name); };
            container.appendChild(div);
        });
    }
}

function navigateToPath(path) { window.location.href = "?view=files&path=" + path; }
function openFolder(name) { navigateToPath(currentPath ? currentPath + '/' + name : name); }
function goBack() { const parts = currentPath.split('/'); parts.pop(); navigateToPath(parts.join('/')); }
function triggerUpload() { document.getElementById('hidden-upload').click(); }

async function doUpload(input) {
    if(!input.files[0]) return;
    const res = await api('upload', { file: input.files[0] });
    toast(res.msg || 'Thành công'); loadFiles();
}

function showFileOptions(name) {
    showModal(`Quản lý: ${name}`, `<p style="color:var(--fm-text-muted)">Bạn muốn thực hiện?</p>`, `
        <button class="btn btn-outline" onclick="doCopy('${name}')">Sao chép</button>
        <button class="btn btn-outline" onclick="doCut('${name}')">Cắt</button>
        <button class="btn btn-outline" onclick="promptRename('${name}')">Đổi tên</button>
        <button class="btn btn-outline" style="color:#dc2626" onclick="confirmDelete('${name}')">Xóa</button>
        <button class="btn btn-primary" onclick="closeModal()">Đóng</button>
    `);
}

function promptCreateFolder() {
    showModal('Tạo thư mục', `<input type="text" id="new-name" class="btn btn-outline" style="width:100%; text-align:left" placeholder="Tên thư mục...">`, 
    `<button class="btn btn-primary" onclick="executeCreate('create_folder')">Tạo</button>`);
}

function promptCreateFile() {
    showModal('Tạo tệp mới', `<input type="text" id="new-name" class="btn btn-outline" style="width:100%; text-align:left" placeholder="ten_file.txt">`, 
    `<button class="btn btn-primary" onclick="executeCreate('create_file')">Tạo</button>`);
}

async function executeCreate(action) {
    const name = document.getElementById('new-name').value;
    const res = await api(action, { name: name });
    toast(res.msg || 'Thành công'); loadFiles(); closeModal();
}

async function doCopy(name) { const res = await api('copy', { name: name }); toast(res.msg); closeModal(); }
async function doCut(name) { const res = await api('cut', { name: name }); toast(res.msg); closeModal(); }
async function doPaste() { const res = await api('paste'); toast(res.msg); loadFiles(); }

function promptRename(name) {
    showModal('Đổi tên', `<input type="text" id="rename-input" class="btn btn-outline" style="width:100%; text-align:left" value="${name}">`, 
    `<button class="btn btn-primary" onclick="doRename('${name}')">Lưu</button>`);
}

async function doRename(oldName) {
    const newName = document.getElementById('rename-input').value;
    const res = await api('rename', { old: oldName, new: newName });
    toast('Đã đổi tên'); loadFiles(); closeModal();
}

function confirmDelete(name) {
    showModal('Xác nhận xóa', `<p>Bạn có chắc muốn xóa <b>${name}</b>?</p>`, 
    `<button class="btn btn-outline" onclick="closeModal()">Hủy</button><button class="btn btn-primary" style="background:red" onclick="doDelete('${name}')">Xóa</button>`);
}

async function doDelete(name) {
    await api('delete', { name: name });
    toast('Đã xóa'); loadFiles(); closeModal();
}

async function previewFile(name, ext) {
    activeFile = name;
    
    // Tạo đường dẫn động thông qua API Proxy
    const mediaUrl = `function/file/api_file.php?action=view_raw&name=${encodeURIComponent(name)}&path=${encodeURIComponent(currentPath)}`;
    
    let content = '';
    if (['jpg', 'png', 'webp', 'gif'].includes(ext)) {
        content = `<img src="${mediaUrl}" style="max-width:100%; max-height:60vh; border-radius:8px;">`;
    } else if (['mp4', 'webm'].includes(ext)) {
        content = `<video src="${mediaUrl}" controls style="width:100%; border-radius:8px;"></video>`;
    } else if (['mp3', 'wav'].includes(ext)) {
        content = `<div style="text-align:center; padding:20px; width:100%"><i class="fas fa-music fa-3x" style="color:#10b981; margin-bottom:15px"></i><audio src="${mediaUrl}" controls style="width:100%"></audio></div>`;
    } else {
        const json = await api('read', { name: name });
        content = `<textarea id="code-editor">${escapeHTML(json.content)}</textarea>`;
    }
    
    const footer = (['jpg','png','webp','gif','mp4','webm','mp3','wav'].includes(ext)) 
        ? `<button class="btn btn-primary" onclick="closeModal()">Đóng</button>`
        : `<button class="btn btn-primary" onclick="saveCode()">Lưu</button><button class="btn btn-outline" onclick="closeModal()">Đóng</button>`;
    
    showModal(name, content, footer);
}

async function saveCode() {
    const code = document.getElementById('code-editor').value;
    // Sửa lỗi tham chiếu json -> res
    const res = await api('write', { name: activeFile, content: code });
    if(res.status === 'success') { 
        toast('Đã lưu'); 
        closeModal(); 
        loadFiles(); // Tải lại danh sách sau khi lưu
    }
}

loadFiles();
</script>
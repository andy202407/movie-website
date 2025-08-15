<?php
// 引入必要的类
require_once dirname(__DIR__) . '/../../Database.php';
require_once dirname(__DIR__) . '/../../VideoModel.php';

// 启动会话
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 获取父分类数据
$videoModel = new VideoModel();
$parentCategories = $videoModel->getParentCategories();

// 检查用户登录状态
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
$userInfo = null;

if ($isLoggedIn) {
    try {
        $db = Database::getInstance();
        $userInfo = $db->fetchOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
    } catch (Exception $e) {
        error_log("获取用户信息失败: " . $e->getMessage());
        $isLoggedIn = false;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
    <meta name="theme-color" content="#0a0a0a" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <title>星海影院 - 会员中心</title>
<!-- ======== 头部样式（替换你的 <style> 整段） ======== -->
    <style>
    :root{
        --bg:#0a0a0a;
        --layer:linear-gradient(135deg,#1a1a1a 0%,#0a0a0a 100%);
        --border:rgba(255,255,255,.08);
        --text:#fff;
        --muted:#9aa0aa;
        --primary:#667eea;
        --primary-2:#764ba2;
        --danger:#ff6b6b;
        --radius:14px;
        --maxw:1200px;
        --px:clamp(12px,3vw,24px);
        --h:56px; /* 头部最小高度（桌面） */
    }

    /* 基础 */
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
        margin:0;
        background:var(--bg);
        color:var(--text);
        font-family: "Microsoft YaHei",system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Arial,sans-serif;
        -webkit-font-smoothing:antialiased;
        -webkit-tap-highlight-color:transparent;
    }

    /* ====== Header ====== */
    .member-header{
        position:sticky; top:0; z-index:1000;
        background:var(--layer);
        border-bottom:1px solid var(--border);
        backdrop-filter:saturate(140%) blur(12px);
        padding: max(8px, env(safe-area-inset-top)) 0 8px;
    }

    .header-inner{
        width:min(var(--maxw), 100% - 2*var(--px));
        margin:0 auto;
        display:grid;
        grid-template-columns:auto 1fr auto;
        grid-template-areas:"brand spacer actions";
        align-items:center;
        column-gap:16px;
        min-height:var(--h);
        padding:0 max(env(safe-area-inset-right), 0px) 0 max(env(safe-area-inset-left), 0px);
    }

    /* 品牌区 */
    .brand{grid-area:brand; display:flex; align-items:center; gap:10px; text-decoration:none}
    .logo-ico{
        width:34px; height:34px; border-radius:50%;
        display:inline-flex; align-items:center; justify-content:center;
        background:linear-gradient(135deg,var(--primary),var(--primary-2));
        color:#fff; font-size:16px; box-shadow:0 0 0 2px rgba(102,126,234,.25) inset;
        flex:0 0 34px;
    }
    .brand-text{display:flex; flex-direction:column; line-height:1.05}
    .brand-cn{font-weight:700; font-size:18px; color:var(--primary)}
    .brand-en{font-style:normal; font-size:11px; color:#8a8f99; letter-spacing:.3px}

    /* 右侧区（退出 + 用户） */
    .actions{grid-area:actions; display:flex; align-items:center; gap:14px}
    .logout{
        appearance:none; border:0; cursor:pointer;
        padding:8px 14px; border-radius:999px;
        color:#fff; font-weight:600; font-size:13px;
        background:linear-gradient(135deg,var(--danger),#ee5a52);
        box-shadow:0 4px 18px rgba(255,107,107,.25);
        transition:transform .15s ease, box-shadow .2s ease;
        white-space:nowrap;
    }
    .logout:hover{ transform:translateY(-1px); box-shadow:0 6px 22px rgba(255,107,107,.35) }

    /* 用户下拉 */
    .dropdown{ position:relative }
    .user-btn{
        display:flex; align-items:center; gap:10px;
        background:transparent; color:#fff; border:1px solid transparent;
        padding:6px 8px; border-radius:10px; cursor:pointer;
    }
    .user-btn:focus-visible{ outline:2px solid rgba(102,126,234,.6); outline-offset:2px }
    .avatar{
        width:34px; height:34px; flex:0 0 34px;
        border-radius:50%;
        display:inline-flex; align-items:center; justify-content:center;
        font-weight:800; color:#fff;
        background:linear-gradient(135deg,var(--primary),var(--primary-2));
        box-shadow:0 0 0 2px rgba(102,126,234,.28) inset;
    }
    .meta{display:flex; flex-direction:column; align-items:flex-start; min-width:0}
    .name{max-width:120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:700; font-size:14px}
    .vip{font-size:11px; color:var(--primary)}

    .menu{
        position:absolute; top:calc(100% + 10px); right:0;
        width:180px; padding:8px;
        background:rgba(20,20,20,.95);
        border:1px solid var(--border);
        border-radius:12px; box-shadow:0 12px 30px rgba(0,0,0,.45);
        opacity:0; transform:translateY(-8px) scale(.98); pointer-events:none;
        transition:opacity .18s ease, transform .18s ease;
        backdrop-filter: blur(18px);
    }
    .dropdown.open .menu{ opacity:1; transform:translateY(0) scale(1); pointer-events:auto }

    .menu a{
        display:flex; align-items:center; gap:8px;
        padding:9px 10px; font-size:13px; color:#cfd3da; text-decoration:none; border-radius:8px;
    }
    .menu a:hover{ background:rgba(102,126,234,.12); color:var(--primary) }

    /* 可选：导航（如果你启用 nav） */
    .nav{ grid-area:spacer; display:flex; gap:14px; align-items:center }
    .nav-link{
        color:#c7cbd3; text-decoration:none; font-size:14px; padding:8px 10px; border-radius:8px;
    }
    .nav-link:hover{ background:rgba(255,255,255,.04); color:#fff }
    .nav-link.active{ color:var(--primary); background:rgba(102,126,234,.12) }

    /* ====== 响应式 ====== */
    @media (max-width: 900px){
        .name{max-width:100px}
    }

    @media (max-width: 767px){
        .header-inner{
        grid-template-columns: 1fr auto;
        grid-template-areas:
            "brand actions"
            "user  user";
        row-gap:10px;
        min-height:unset;
        }
        .actions{ grid-area:actions }
        .dropdown{ grid-area:user; justify-self:center }
        .user-btn{ padding:6px 10px }
        .avatar{ width:28px; height:28px; flex-basis:28px; font-size:12px }
        .name{ font-size:13px; max-width:140px }
        .vip{ font-size:10px }
        .logout{ padding:6px 12px; font-size:12px }
    }

    @media (max-width: 480px){
        .brand-cn{ font-size:16px }
        .brand-en{ font-size:10px }
        .logo-ico{ width:28px; height:28px; font-size:14px; flex-basis:28px }
        .name{ max-width:120px }
    }
    </style>
</head>
<body>
    <!-- 会员中心专用Header -->
<!-- ======== 头部 HTML（替换你的 <header> 整段） ======== -->
<header class="member-header">
  <div class="header-inner">
    <!-- 品牌 -->
    <a href="/" class="brand" aria-label="返回首页">
      <span class="logo-ico">⭐</span>
      <span class="brand-text">
        <span class="brand-cn">会员中心</span>
        <span class="brand-en">XINGHAI CINEMA</span>
      </span>
    </a>

    <!-- 可选：导航（按需启用）
    <nav class="nav">
      <a href="/" class="nav-link">首页</a>
      <a href="/?page=list&category=1" class="nav-link">电影</a>
      <a href="/?page=list&category=2" class="nav-link">电视剧</a>
      <a href="/?page=list&category=3" class="nav-link">动漫</a>
      <a href="/?page=list&category=4" class="nav-link">综艺</a>
    </nav>
    -->

    <!-- 右侧：退出 + 用户 -->
    <div class="actions">
      <?php if ($isLoggedIn && $userInfo): ?>

        <div class="dropdown" id="userDropdown">
          <button class="user-btn" type="button" aria-haspopup="true" aria-expanded="false" id="userBtn">
            <span class="avatar"><?= strtoupper(substr($userInfo['username'], 0, 1)) ?></span>
            <span class="meta">
              <span class="name"><?= htmlspecialchars(substr($userInfo['username'], 0, 16)) ?></span>
              <span class="vip">VIP会员</span>
            </span>
          </button>
          <div class="menu" role="menu">
            <a href="/user" role="menuitem">👤 会员中心</a>
            <a href="/user?tab=favorites" role="menuitem">⭐ 我的收藏</a>
            <a href="/user?tab=history" role="menuitem">📺 观看历史</a>
            <a href="javascript:void(0)" onclick="logout()" role="menuitem">🚪 退出登录</a>
          </div>
        </div>
      <?php else: ?>
        <div class="auth">
          <a href="/user/login" class="nav-link">登录</a>
          <a href="/user/register" class="nav-link">注册</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>
    
    <script>
        // 退出登录函数
        function logout() {
            if (confirm('确定要退出登录吗？')) {
                fetch('/api/user.php?action=logout')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/';
                        }
                    })
                    .catch(error => {
                        console.error('退出登录失败:', error);
                        window.location.href = '/';
                    });
            }
        }
        
        // 设置当前页面导航高亮
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
            
            // 用户头像点击事件
            const userBtn = document.getElementById('userBtn');
            const userDropdown = document.getElementById('userDropdown');
            
            if (userBtn && userDropdown) {
                userBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const isExpanded = userBtn.getAttribute('aria-expanded') === 'true';
                    
                    // 切换菜单显示状态
                    if (isExpanded) {
                        userDropdown.classList.remove('open');
                        userBtn.setAttribute('aria-expanded', 'false');
                    } else {
                        userDropdown.classList.add('open');
                        userBtn.setAttribute('aria-expanded', 'true');
                    }
                });
                
                // 点击其他地方时隐藏菜单
                document.addEventListener('click', function(e) {
                    if (!userDropdown.contains(e.target)) {
                        userDropdown.classList.remove('open');
                        userBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    </script>

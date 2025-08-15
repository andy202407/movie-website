<?php
// 会员中心模板文件
$pageTitle = '会员中心 - 星海影院';
$pageKeywords = '会员中心,个人中心,账户管理,观看记录,收藏夹,星海影院';
$pageDescription = '星海影院会员中心,管理您的个人账户、观看记录、收藏夹、观看历史等个人信息,享受个性化的观影体验。';
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <meta name="theme-color" content="#0a0a0a" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <title><?= $pageTitle ?></title>
  <meta name="keywords" content="<?= $pageKeywords ?>">
  <meta name="description" content="<?= $pageDescription ?>">
  <link rel="stylesheet" href="/template/yuyuyy/asset/css/common.css">
  <script src="/template/yuyuyy/asset/js/jquery.js"></script>
  <script src="/template/yuyuyy/asset/js/assembly.js"></script>

  <style>
    :root{
      --bg:#0a0a0a;
      --panel-a:rgba(26,26,26,.9);
      --panel-b:rgba(10,10,10,.9);
      --border:rgba(255,255,255,.1);
      --muted:#888;
      --txt:#fff;
      --pri:#667eea;
      --pri2:#764ba2;
      --radius:20px;
      --maxw:1200px;
      --px:clamp(12px,3vw,30px);
    }

    /* 基础 */
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      background:var(--bg);
      color:var(--txt);
      font-family:'Microsoft YaHei',system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Arial,sans-serif;
      margin:0; padding:0;
      -webkit-font-smoothing:antialiased;
    }

    /* 容器 */
    .member-center-container{
      min-height:100vh;
      background:linear-gradient(135deg,#0a0a0a 0%,#1a1a1a 50%,#0a0a0a 100%);
      padding: clamp(18px, 3vw, 35px) 0;
    }
    .member-content{
      width:min(var(--maxw), 100% - 2*var(--px));
      margin:0 auto;
    }

    /* 信息卡片 */
    .member-info-card{
      position:relative; overflow:hidden;
      background:linear-gradient(135deg,var(--panel-a) 0%,var(--panel-b) 100%);
      backdrop-filter: blur(20px);
      border:1px solid var(--border);
      border-radius:var(--radius);
      padding: clamp(20px, 4vw, 45px) clamp(16px, 3vw, 40px);
      margin-bottom: clamp(16px, 3vw, 35px);
      box-shadow:0 15px 30px rgba(0,0,0,.5);
    }

    .member-info-card::after{
      content:""; position:absolute; right:-20%; top:-20%;
      width:120px; height:120px; border-radius:50%;
      background:radial-gradient(circle, rgba(102,126,234,.12) 0%, transparent 70%);
      animation:float 6s ease-in-out infinite;
    }
    @keyframes float{0%,100%{transform:translateY(0) rotate(0)} 50%{transform:translateY(-10px) rotate(180deg)}}

    .member-avatar{
      width:110px; height:110px; border-radius:50%;
      background:linear-gradient(135deg,var(--pri),var(--pri2));
      color:#fff; display:flex; align-items:center; justify-content:center;
      font-size:44px; font-weight:800;
      box-shadow:0 10px 25px rgba(102,126,234,.4);
      border:3px solid rgba(255,255,255,.2);
      position:relative; z-index:1; margin:0 auto 25px;
    }
    .member-avatar::before{
      content:""; position:absolute; inset:-3px; border-radius:50%;
      background:linear-gradient(45deg,var(--pri),var(--pri2),#f093fb,var(--pri));
      z-index:-1; animation:rotate 3s linear infinite;
    }
    @keyframes rotate{from{transform:rotate(0)} to{transform:rotate(360deg)}}

    .member-name{
      font-size:30px; font-weight:800; text-align:center;
      text-shadow:0 2px 10px rgba(0,0,0,.5); margin-bottom:10px;
    }
    .member-password{ font-size:14px; color:var(--muted); text-align:center; margin-bottom:32px }

    /* 统计 */
    .member-stats{ display:grid; gap: clamp(12px, 2.2vw, 25px); margin-bottom: clamp(18px, 2.8vw, 35px) }
    .stat-item{
      text-align:center; padding: clamp(14px, 2.4vw, 22px) clamp(10px, 2vw, 18px);
      background:rgba(255,255,255,.05);
      border-radius:12px; border:1px solid var(--border);
      transition:.25s ease;
    }
    .stat-item:hover{
      transform:translateY(-3px);
      background:rgba(255,255,255,.08);
      border-color:rgba(102,126,234,.28);
      box-shadow:0 8px 20px rgba(102,126,234,.18);
    }
    .stat-number{
      display:block; font-size:20px; font-weight:800; color:var(--pri);
      text-shadow:0 0 15px rgba(102,126,234,.5); margin-bottom:6px;
    }
    .stat-label{ font-size:13px; color:#aaa; font-weight:600 }

    /* Tabs 面板 */
    .member-tabs{
      background:linear-gradient(135deg,var(--panel-a) 0%,var(--panel-b) 100%);
      border:1px solid var(--border);
      border-radius:var(--radius);
      padding: clamp(18px, 3vw, 35px);
      box-shadow:0 15px 30px rgba(0,0,0,.5);
      backdrop-filter: blur(20px);
    }

    /* —— 桌面：居中的横向 tab —— */
    .tab-nav{
      display:flex; justify-content:center; align-items:center; gap:8px;
      border-bottom:2px solid var(--border); margin-bottom: clamp(18px, 2.4vw, 25px);
    }
    .tab-nav-item{
      padding: 12px 24px; min-width:100px;
      font-weight:600; font-size:15px; color:#9aa0aa; text-align:center;
      border-radius:10px 10px 0 0; border-bottom:3px solid transparent;
      transition:.2s ease; cursor:pointer; user-select:none;
    }
    .tab-nav-item:hover{ color:var(--pri); background:rgba(102,126,234,.1) }
    .tab-nav-item.active{ color:var(--pri); background:rgba(102,126,234,.12); border-bottom-color:var(--pri) }

    .tab-content{ min-height:350px }
    .tab-pane{ display:none }
    .tab-pane.active{ display:block; animation:fade .35s ease }
    @keyframes fade{from{opacity:0; transform:translateY(12px)} to{opacity:1; transform:translateY(0)}}

    .empty-state{ text-align:center; padding: clamp(40px, 6vw, 70px) 20px }
    .empty-state-icon{ font-size: clamp(40px, 6vw, 70px); margin-bottom: 18px; opacity:.9 }
    .empty-state-text{ font-size: clamp(13px, 2.2vw, 17px); color:#9aa0aa; margin-bottom: clamp(20px, 2.8vw, 35px); line-height:1.6 }
    .discover-btn{
      display:inline-block; background:linear-gradient(135deg,var(--pri),var(--pri2)); color:#fff;
      padding: 10px 26px; border-radius:22px; font-weight:700; font-size:15px;
      text-decoration:none; box-shadow:0 6px 20px rgba(102,126,234,.3); transition:.2s ease;
    }
    .discover-btn:hover{ transform:translateY(-2px); box-shadow:0 8px 24px rgba(102,126,234,.38) }

    /* ====== 收藏列表和观看历史样式 ====== */
    .favorites-list, .history-list {
      display: grid;
      gap: 16px;
      margin-top: 20px;
    }

    .favorite-item, .history-item {
      display: flex;
      gap: 16px;
      padding: 16px;
      background: rgba(255,255,255,.05);
      border: 1px solid var(--border);
      border-radius: 12px;
      transition: .25s ease;
    }

    .favorite-item:hover, .history-item:hover {
      background: rgba(255,255,255,.08);
      border-color: rgba(102,126,234,.28);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(102,126,234,.18);
    }

    .favorite-poster, .history-poster {
      width: 80px;
      height: 120px;
      border-radius: 8px;
      overflow: hidden;
      flex-shrink: 0;
    }

    .favorite-poster img, .history-poster img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .favorite-info, .history-info {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .favorite-title, .history-title {
      font-size: 16px;
      font-weight: 600;
      color: #fff;
      margin-bottom: 8px;
      line-height: 1.4;
    }

    .favorite-meta, .history-meta {
      font-size: 13px;
      color: #9aa0aa;
      margin-bottom: 12px;
      line-height: 1.5;
    }

    .favorite-actions, .history-actions {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .btn-play, .btn-remove {
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      transition: .2s ease;
      cursor: pointer;
    }

    .btn-play {
      background: linear-gradient(135deg, var(--pri), var(--pri2));
      color: #fff;
    }

    .btn-play:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(102,126,234,.3);
    }

    .btn-remove {
      background: rgba(255,255,255,.1);
      color: #9aa0aa;
      border: 1px solid rgba(255,255,255,.2);
    }

    .btn-remove:hover {
      background: rgba(255,59,48,.2);
      color: #ff3b30;
      border-color: rgba(255,59,48,.3);
    }

    /* 移动端优化 */
    @media (max-width: 767px) {
      .favorite-item, .history-item {
        flex-direction: column;
        gap: 12px;
        padding: 12px;
      }

      .favorite-poster, .history-poster {
        width: 100%;
        height: 160px;
      }

    }

    /* ====== 大屏细化 ====== */
    @media (min-width: 1024px){
      .member-stats{ grid-template-columns: repeat(4, 1fr); max-width:640px; margin-inline:auto }
    }
    @media (min-width: 768px) and (max-width: 1023px){
      .member-stats{ grid-template-columns: repeat(4, 1fr); }
    }
    
    /* 强制一行至少显示两个统计卡片，同时保持宽度自适应 */
    .member-stats {
      grid-template-columns: repeat(2, 1fr);
      gap: clamp(12px, 2vw, 20px);
      width: 100%;  
    }
    
    /* 确保统计卡片能够自适应容器宽度 */
    .stat-item {
      min-width: 0;
      width: 100%;
    }
    
    /* 大屏幕显示4列 */
    @media (min-width: 1024px) {
      .member-stats {
        grid-template-columns: repeat(4, 1fr);
        max-width: 640px;
        margin-inline: auto;
      }
    }
    
    /* 中等屏幕显示4列 */
    @media (min-width: 768px) and (max-width: 1023px) {
      .member-stats {
        grid-template-columns: repeat(4, 1fr);
      }
    }
    
    /* 小屏幕强制2列，保持自适应 */
    @media (max-width: 767px) {
      .member-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        width: 100%;
      }
    }
    
    /* 超小屏幕优化间距 */
    @media (max-width: 480px) {
      .member-stats {
        gap: 8px;
      }
      
      .stat-item {
        padding: 10px 6px;
      }
      
      .stat-number {
        font-size: 14px;
        margin-bottom: 4px;
      }
      
      .stat-label {
        font-size: 10px;
        line-height: 1.2;
      }
    }

    /* ====== 移动端：独立布局优化 ====== */
    @media (max-width: 767px){
      /* 信息卡片顶部：两列布局（左头像，右昵称+密码），下方统计满行 */
      .member-info-card{
        /* display:grid; */
        grid-template-columns: auto 1fr;
        grid-template-rows:auto auto auto;
        column-gap:14px; row-gap:6px;
        align-items:center;
      }
      .member-avatar{
        grid-column:1; grid-row:1 / span 2;
        width:75px; height:75px; font-size:30px; margin:0; /* 取消居中 */
      }
      .member-name{
        grid-column:2; grid-row:1; text-align:left; font-size:22px; margin:0 0 4px 0;
      }
      .member-password{
        grid-column:2; grid-row:2; text-align:left; font-size:12px; color:#9aa0aa; margin:0;
      }


      /* Tabs：顶部横向胶囊滚动，scroll-snap，吸附当前项；底部边线隐藏 */
      .tab-nav{
        border-bottom:none;
        justify-content:flex-start;
        gap:8px; margin-bottom:14px;
        overflow-x:auto; -webkit-overflow-scrolling:touch;
        scroll-snap-type:x mandatory; padding-bottom:6px;
        padding-left: env(safe-area-inset-left, 0);
        padding-right: env(safe-area-inset-right, 0);
      }
      .tab-nav::-webkit-scrollbar{ display:none }
      .tab-nav-item{
        flex:0 0 auto; scroll-snap-align:center;
        border-radius:999px; border-bottom:none;
        padding:8px 14px; font-size:13px; min-width:unset;
        background:rgba(255,255,255,.04);
      }
      .tab-nav-item.active{
        color:#fff; background:linear-gradient(135deg,var(--pri),var(--pri2));
        box-shadow:0 6px 18px rgba(102,126,234,.35);
      }

      .member-tabs{ padding:18px 14px; border-radius:16px }
      .tab-content{ min-height:240px }
      .empty-state{ padding: 32px 12px }
      .empty-state-text{ font-size:13px }
      .discover-btn{ padding:8px 18px; font-size:13px; border-radius:18px }
    }

    /* 超小屏（<=480px）：保持两列布局 */
    @media (max-width: 480px){
      .member-name{ font-size:20px }
      .member-avatar{ width:65px; height:65px; font-size:26px }
    }

    /* ====== 消息提示动画 ====== */
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(100%);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .message-tip {
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    /* ====== 加载动画样式 ====== */
    .loading-spinner {
      width: 40px;
      height: 40px;
      border: 3px solid rgba(255,255,255,0.1);
      border-top: 3px solid var(--pri);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* 刷新按钮样式 */
    .discover-btn + .discover-btn {
      margin-left: 10px;
    }

    /* 新的列表样式 */
    .history-item, .favorite-item {
      display: flex;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 16px;
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.1);
      align-items: center;
    }

    .history-item:hover, .favorite-item:hover {
      background: rgba(255, 255, 255, 0.08);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .history-poster, .favorite-poster {
      position: relative;
      width: 120px;
      height: 160px;
      margin-right: 16px;
      flex-shrink: 0;
    }

    .history-poster img, .favorite-poster img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .episode-badge, .favorite-badge {
      position: absolute;
      top: 8px;
      right: 8px;
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 500;
    }

    .history-info, .favorite-info {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .history-title, .favorite-title {
      font-size: 18px;
      font-weight: 600;
      color: white;
      margin-bottom: 12px;
      line-height: 1.3;
    }

    .history-meta, .favorite-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 16px;
    }

    .time-badge, .progress-badge, .year-badge, .region-badge, .actor-badge {
      background: rgba(255, 255, 255, 0.1);
      color: #9aa0aa;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
    }

    /* 移动端徽章样式 */
    @media (max-width: 767px) {
      .time-badge, .progress-badge, .year-badge, .region-badge, .actor-badge {
        padding: 4px 8px;
        font-size: 11px;
        border-radius: 16px;
      }
    }

    .progress-badge {
      background: rgba(0, 122, 255, 0.2);
      color: #007aff;
    }

    .history-actions, .favorite-actions {
      display: flex;
      gap: 12px;
    }

    .btn-play {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 10px 20px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .btn-play:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-remove {
      background: rgba(255, 59, 48, 0.2);
      color: #ff3b30;
      padding: 10px 20px;
      border-radius: 25px;
      border: 1px solid rgba(255, 59, 48, 0.3);
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-remove:hover {
      background: rgba(255, 59, 48, 0.3);
      transform: translateY(-2px);
    }

    /* 清除全部按钮样式 */
    .btn-clear-all {
      background: rgba(255, 149, 0, 0.2);
      color: #ff9500;
      padding: 8px 16px;
      border-radius: 20px;
      border: 1px solid rgba(255, 149, 0, 0.3);
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 14px;
    }

    .btn-clear-all:hover {
      background: rgba(255, 149, 0, 0.3);
      transform: translateY(-1px);
    }

    /* 分页样式 */
    .pagination {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      margin-top: 24px;
      padding: 20px 0;
    }

    .pagination-info {
      color: #9aa0aa;
      font-size: 14px;
      margin-bottom: 8px;
    }

    .pagination-buttons {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
    }

    .page-btn {
      background: rgba(255, 255, 255, 0.1);
      color: #9aa0aa;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 8px 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 14px;
      font-weight: 500;
    }

    .page-btn:hover:not(.current) {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      transform: translateY(-1px);
    }

    .page-btn.current {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-color: transparent;
      cursor: default;
    }

    .page-btn.prev, .page-btn.next {
      background: rgba(255, 255, 255, 0.15);
      color: white;
      font-weight: 600;
    }

    /* 移动端适配 */
    @media (max-width: 767px) {
      .history-item, .favorite-item {
        flex-direction: row;
        padding: 12px;
        align-items: center;
      }

      .history-poster, .favorite-poster {
        width: 80px;
        height: 120px;
        margin-right: 12px;
        margin-bottom: 0;
        flex-shrink: 0;
      }

      .history-title, .favorite-title {
        font-size: 14px;
        margin-bottom: 6px;
        line-height: 1.2;
      }

      .history-meta, .favorite-meta {
        margin-bottom: 8px;
        gap: 4px;
      }

      .history-actions, .favorite-actions {
        flex-direction: row;
        gap: 6px;
      }

      .btn-play, .btn-remove {
        text-align: center;
        padding: 8px 12px;
        font-size: 12px;
        border-radius: 20px;
      }

      .pagination {
        flex-wrap: wrap;
        gap: 6px;
      }

              .page-btn {
          padding: 6px 12px;
          font-size: 13px;
        }
      }

      /* 超小屏幕适配 */
      @media (max-width: 480px) {
        .history-item, .favorite-item {
          padding: 8px;
        }

        .history-poster, .favorite-poster {
          width: 60px;
          height: 90px;
          margin-right: 8px;
        }

        .history-title, .favorite-title {
          font-size: 13px;
          margin-bottom: 4px;
        }

        .history-meta, .favorite-meta {
          margin-bottom: 6px;
          gap: 3px;
        }

        .history-actions, .favorite-actions {
          gap: 4px;
        }

        .btn-play, .btn-remove {
          padding: 6px 10px;
          font-size: 11px;
        }

        .time-badge, .progress-badge, .year-badge, .region-badge, .actor-badge {
          padding: 3px 6px;
          font-size: 10px;
        }
    }
  </style>
</head>
<body>
  <!-- Header/ Footer 仍用你的组件 -->
  <?php include dirname(__DIR__) . '/user/components/header.php'; ?>

  <div class="member-center-container">
    <div class="member-content">
      <!-- 信息卡片（移动端左图右文） -->
      <div class="member-info-card">

        <div class="member-stats">
          <div class="stat-item">
            <span class="stat-number" id="favoritesCount">0</span>
            <div class="stat-label">我的收藏</div>
          </div>
          <div class="stat-item">
            <span class="stat-number" id="historyCount">0</span>
            <div class="stat-label">观看记录</div>
          </div>
          <div class="stat-item">
            <span class="stat-number">永久</span>
            <div class="stat-label">会员截止日期</div>
          </div>
          <div class="stat-item">
            <span class="stat-number">VIP</span>
            <div class="stat-label">会员等级</div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="member-tabs">
        <div class="tab-nav" id="tabNav">
          <div class="tab-nav-item active" data-tab="favorites">⭐ 我的收藏</div>
          <div class="tab-nav-item" data-tab="history">📺 观看历史</div>
          <!-- <div class="tab-nav-item" data-tab="profile">账户信息</div> -->
          <div class="tab-nav-item" data-tab="vip">💎 VIP特权</div>
        </div>

        <div class="tab-content">
          <div class="tab-pane active" id="favorites">
            <div class="empty-state" id="favoritesEmpty">
              <div class="empty-state-icon">💫</div>
              <div class="empty-state-text">您还没有收藏任何影片<br>开始收藏您喜欢的影视作品吧！</div>
              <a href="/" class="discover-btn">🔍 去发现更多好片</a>
            </div>
            <div class="favorites-list" id="favoritesList" style="display: none;">
              <!-- 收藏列表将通过JavaScript动态加载 -->
            </div>
          </div>

          <div class="tab-pane" id="history">
            <div class="empty-state" id="historyEmpty">
              <div class="empty-state-icon">🎬</div>
              <div class="empty-state-text">您还没有观看记录<br>开始观看精彩影视内容吧！</div>
              <a href="/" class="discover-btn">🔍 去发现更多好片</a>
              <button class="discover-btn" onclick="memberCenter.loadWatchHistory()" style="margin-left: 10px; background: rgba(255,255,255,0.1);">🔄 刷新</button>
            </div>
                        <!-- 清除全部按钮 -->
            <div class="history-actions-top" id="historyActionsTop" style="display: none; text-align: left; margin-bottom: 16px;">
              <button class="btn-clear-all" onclick="memberCenter.clearAllWatchHistory()">🧹 清除全部观看记录</button>
            </div>
            <div class="history-list" id="historyList" style="display: none;">
              <!-- 观看历史将通过JavaScript动态加载 -->
            </div>
            <div class="history-loading" id="historyLoading" style="display: none; text-align: center; padding: 40px;">
              <div class="loading-spinner"></div>
              <p style="color: #9aa0aa; margin-top: 20px;">正在加载观看历史...</p>
            </div>
          </div>

          <div class="tab-pane" id="profile">
            <div class="empty-state">
              <div class="empty-state-icon">⚙️</div>
              <div class="empty-state-text">账户信息管理功能开发中<br>敬请期待更多功能！</div>
              <a href="/" class="discover-btn">🏠 返回首页</a>
            </div>
          </div>

          <div class="tab-pane" id="vip">
            <div class="empty-state">
              <div class="empty-state-icon">💎</div>
              <div class="empty-state-text">VIP特权功能享受中</div>
              <a href="/" class="discover-btn">✨ 了解VIP特权</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include dirname(__DIR__) . '/user/components/footer.php'; ?>

  <script>
    // 会员中心数据管理
    class MemberCenter {
      constructor() {
        this.init();
      }

      init() {
        this.loadUserStats();
        this.initTabs();
        this.loadFavorites();
        this.loadWatchHistory();
      }

      // 加载用户统计信息
      async loadUserStats() {
        try {
          // 加载收藏数量
          const favoritesResponse = await fetch('/api/user.php?action=get_favorites&page=1&limit=1');
          const favoritesData = await favoritesResponse.json();
          if (favoritesData.success) {
            document.getElementById('favoritesCount').textContent = favoritesData.total || 0;
          }

          // 加载观看历史数量
          const historyResponse = await fetch('/api/user.php?action=get_watch_history&page=1&limit=1');
          const historyData = await historyResponse.json();
          if (historyData.success) {
            document.getElementById('historyCount').textContent = historyData.total || 0;
          }
        } catch (error) {
          console.error('加载统计信息失败:', error);
        }
      }

      // 加载收藏列表
      async loadFavorites(page = 1) {
        try {
          const response = await fetch(`/api/user.php?action=get_favorites&page=${page}&limit=20`);
          const data = await response.json();
          
          if (data.success && data.favorites && data.favorites.length > 0) {
            this.renderFavorites(data.favorites, data.page, data.pages);
            document.getElementById('favoritesEmpty').style.display = 'none';
            document.getElementById('favoritesList').style.display = 'block';
          } else {
            document.getElementById('favoritesEmpty').style.display = 'block';
            document.getElementById('favoritesList').style.display = 'none';
          }
        } catch (error) {
          console.error('加载收藏失败:', error);
          document.getElementById('favoritesEmpty').style.display = 'block';
          document.getElementById('favoritesList').style.display = 'none';
        }
      }

      // 渲染收藏列表
      renderFavorites(favorites, currentPage = 1, totalPages = 1) {
        const container = document.getElementById('favoritesList');
        
        // 渲染列表项
        const listHtml = favorites.map(favorite => `
          <div class="favorite-item">
            <div class="favorite-poster">
              <img src="${favorite.poster || '/template/yuyuyy/asset/img/default-poster.jpg'}" 
                   alt="${favorite.title}" 
                   onerror="this.src='/template/yuyuyy/asset/img/default-poster.jpg'; console.log('图片加载失败，使用占位图片:', '${favorite.title}')">
            </div>
            <div class="favorite-info">
              <div class="favorite-title">${favorite.title || '未知标题'}</div>
              <div class="favorite-meta">
                ${favorite.year ? `<span class="year-badge">${favorite.year}年</span>` : ''} 
                ${favorite.region ? `<span class="region-badge">${favorite.region}</span>` : ''}
                ${favorite.actor ? `<span class="actor-badge">${favorite.actor}</span>` : ''}
              </div>
              <div class="favorite-actions">
                <a href="/?page=play&id=${favorite.video_id}" class="btn-play">▶️ 继续观看</a>
                <button class="btn-remove" onclick="memberCenter.removeFavorite(${favorite.video_id})">💔 取消收藏</button>
              </div>
            </div>
          </div>
        `).join('');
        
        // 渲染分页
        const paginationHtml = this.renderPagination(currentPage, totalPages, 'favorites');
        
        container.innerHTML = listHtml + paginationHtml;
      }

      // 加载观看历史
      async loadWatchHistory(page = 1) {
        try {
          console.log('开始加载观看历史...');
          
          // 显示加载状态
          document.getElementById('historyEmpty').style.display = 'none';
          document.getElementById('historyList').style.display = 'none';
          document.getElementById('historyLoading').style.display = 'block';
          
          const response = await fetch(`/api/user.php?action=get_watch_history&page=${page}&limit=20`);
          const data = await response.json();
          
          console.log('观看历史API响应:', data);
          
          // 隐藏加载状态
          document.getElementById('historyLoading').style.display = 'none';
          
          if (data.success && data.history && data.history.length > 0) {
            console.log(`找到 ${data.history.length} 条观看记录`);
            this.renderWatchHistory(data.history, data.page, data.pages);
            document.getElementById('historyList').style.display = 'block';
            document.getElementById('historyActionsTop').style.display = 'block';
          } else {
            console.log('暂无观看记录或API返回失败');
            // 显示空状态
            document.getElementById('historyEmpty').style.display = 'block';
            document.getElementById('historyActionsTop').style.display = 'none';
          }
        } catch (error) {
          console.error('加载观看历史失败:', error);
          // 隐藏加载状态
          document.getElementById('historyLoading').style.display = 'none';
          // 显示错误状态
          document.getElementById('historyEmpty').style.display = 'block';
        }
      }

      // 渲染观看历史
      renderWatchHistory(history, currentPage = 1, totalPages = 1) {
        const container = document.getElementById('historyList');
        
        if (!Array.isArray(history) || history.length === 0) {
          console.warn('观看历史数据无效或为空');
          return;
        }
        
        console.log('开始渲染观看历史:', history);
        console.log('历史记录数量:', history.length);
        
        // 渲染列表项
        const listHtml = history.map((item, index) => {
          // 安全地获取数据，提供默认值
          const videoId = item.video_id || item.id || 0;
          const title = item.title || '未知标题';
          // 优先使用数据库中的图片路径，失败时使用占位图片
          const poster = item.poster || '/template/yuyuyy/asset/img/default-poster.jpg';
          const episode = item.episode || 1;
          const progress = item.progress || 0;
          const lastWatched = item.last_watched || new Date().toISOString();
          
          console.log(`渲染第${index + 1}项:`, { videoId, title, poster, episode, progress, lastWatched });
          
          return `
            <div class="history-item" data-video-id="${videoId}">
              <div class="history-poster">
                <img src="${poster}" 
                     alt="${title}" 
                     onload="console.log('图片加载成功:', '${title}')"
                     onerror="this.src='/template/yuyuyy/asset/img/default-poster.jpg'; console.log('图片加载失败，使用占位图片:', '${title}')">
                <div class="episode-badge">第${episode}集</div>
              </div>
              <div class="history-info">
                <div class="history-title">${title}</div>
                <div class="history-meta">
                  <span class="time-badge">${this.formatDate(lastWatched)}</span>
                  ${progress > 0 ? `<span class="progress-badge">播放至 ${this.formatTime(progress)}</span>` : ''}
                </div>
                            <div class="history-actions">
              <a href="/?page=play&id=${videoId}${episode > 1 ? '&episode=' + episode : ''}" class="btn-play">▶️ 继续观看</a>
              <button class="btn-remove" onclick="memberCenter.removeWatchHistory(${videoId})">🗑️ 删除记录</button>
            </div>
              </div>
            </div>
          `;
        }).join('');
        
        // 渲染分页
        const paginationHtml = this.renderPagination(currentPage, totalPages, 'history');
        
        container.innerHTML = listHtml + paginationHtml;
        
        console.log('观看历史渲染完成，共渲染', history.length, '项');
        console.log('容器内容长度:', container.innerHTML.length);
      }

      // 取消收藏
      async removeFavorite(videoId) {
        if (!confirm('确定要取消收藏吗？')) return;

        try {
          const formData = new FormData();
          formData.append('video_id', videoId);
          
          const response = await fetch('/api/user.php?action=remove_favorite', {
            method: 'POST',
            body: formData
          });
          
          const data = await response.json();
          if (data.success) {
            // 重新加载收藏列表和统计
            this.loadFavorites();
            this.loadUserStats();
            this.showMessage('已取消收藏', 'success');
          } else {
            this.showMessage(data.message || '操作失败', 'error');
          }
        } catch (error) {
          this.showMessage('网络错误，请重试', 'error');
        }
      }

      // 删除单条观看记录
      async removeWatchHistory(videoId) {
        if (!confirm('确定要删除这条观看记录吗？')) return;

        try {
          const formData = new FormData();
          formData.append('video_id', videoId);
          
          const response = await fetch('/api/user.php?action=remove_watch_history', {
            method: 'POST',
            body: formData
          });
          
          const data = await response.json();
          if (data.success) {
            // 重新加载观看历史和统计
            this.loadWatchHistory();
            this.loadUserStats();
            this.showMessage('观看记录已删除', 'success');
          } else {
            this.showMessage(data.message || '操作失败', 'error');
          }
        } catch (error) {
          this.showMessage('网络错误，请重试', 'error');
        }
      }

      // 清除所有观看记录
      async clearAllWatchHistory() {
        if (!confirm('确定要清除所有观看记录吗？此操作不可恢复！')) return;

        try {
          const response = await fetch('/api/user.php?action=clear_watch_history', {
            method: 'POST'
          });
          
          const data = await response.json();
          if (data.success) {
            // 重新加载观看历史和统计
            this.loadWatchHistory();
            this.loadUserStats();
            this.showMessage('所有观看记录已清除', 'success');
          } else {
            this.showMessage(data.message || '操作失败', 'error');
          }
        } catch (error) {
          this.showMessage('网络错误，请重试', 'error');
        }
      }

      // 格式化日期
      formatDate(dateString) {
        try {
          if (!dateString) return '未知时间';
          
          const date = new Date(dateString);
          
          // 检查日期是否有效
          if (isNaN(date.getTime())) {
            return '未知时间';
          }
          
          const now = new Date();
          const diff = now - date;
          const days = Math.floor(diff / (1000 * 60 * 60 * 24));
          
          if (days === 0) return '今天';
          if (days === 1) return '昨天';
          if (days < 7) return days + '天前';
          if (days < 30) return Math.floor(days / 7) + '周前';
          if (days < 365) return Math.floor(days / 30) + '个月前';
          
          return date.toLocaleDateString('zh-CN', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
          });
        } catch (error) {
          console.warn('日期格式化失败:', error, dateString);
          return '未知时间';
        }
      }

      // 格式化时间（秒转分:秒）
      formatTime(seconds) {
        try {
          if (!seconds || isNaN(seconds) || seconds < 0) return '0:00';
          
          const totalSeconds = Math.floor(seconds);
          const minutes = Math.floor(totalSeconds / 60);
          const remainingSeconds = totalSeconds % 60;
          
          return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        } catch (error) {
          console.warn('时间格式化失败:', error, seconds);
          return '0:00';
        }
      }

      // 渲染分页
      renderPagination(currentPage, totalPages, type) {
        console.log(`渲染分页 - 类型: ${type}, 当前页: ${currentPage}, 总页数: ${totalPages}`);
        
        // 即使只有1页也显示分页信息
        let paginationHtml = '<div class="pagination">';
        
        // 显示分页信息
        paginationHtml += `<div class="pagination-info">第 ${currentPage} 页，共 ${totalPages} 页</div>`;
        
        // 分页按钮容器
        paginationHtml += '<div class="pagination-buttons">';
        
        // 上一页
        if (currentPage > 1) {
          paginationHtml += `<button class="page-btn prev" onclick="memberCenter.load${type.charAt(0).toUpperCase() + type.slice(1)}(${currentPage - 1})">上一页</button>`;
        }
        
        // 页码
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);
        
        for (let i = startPage; i <= endPage; i++) {
          if (i === currentPage) {
            paginationHtml += `<span class="page-btn current">${i}</span>`;
          } else {
            paginationHtml += `<button class="page-btn" onclick="memberCenter.load${type.charAt(0).toUpperCase() + type.slice(1)}(${i})">${i}</button>`;
          }
        }
        
        // 下一页
        if (currentPage < totalPages) {
          paginationHtml += `<button class="page-btn next" onclick="memberCenter.load${type.charAt(0).toUpperCase() + type.slice(1)}(${currentPage + 1})">下一页</button>`;
        }
        
        paginationHtml += '</div></div>';
        return paginationHtml;
      }

      // 显示消息提示
      showMessage(message, type = 'info') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-tip ${type}`;
        messageDiv.textContent = message;
        messageDiv.style.cssText = `
          position: fixed;
          top: 20px;
          right: 20px;
          padding: 12px 20px;
          border-radius: 8px;
          color: white;
          font-weight: 500;
          z-index: 10000;
          animation: slideIn 0.3s ease;
        `;

        if (type === 'success') {
          messageDiv.style.background = '#34c759';
        } else if (type === 'error') {
          messageDiv.style.background = '#ff3b30';
        } else {
          messageDiv.style.background = '#007aff';
        }

        document.body.appendChild(messageDiv);

        setTimeout(() => {
          messageDiv.remove();
        }, 3000);
      }

      // 初始化标签页
      initTabs() {
      const items = document.querySelectorAll('.tab-nav-item');
      const panes = document.querySelectorAll('.tab-pane');
      const nav = document.getElementById('tabNav');

        function activate(target) {
          items.forEach(i => i.classList.toggle('active', i.dataset.tab === target));
          panes.forEach(p => p.classList.toggle('active', p.id === target));
          
        // 移动端把激活项滚到可视区域
          const active = [...items].find(i => i.dataset.tab === target);
          if (active && window.matchMedia('(max-width: 767px)').matches) {
            const left = active.offsetLeft - (nav.clientWidth - active.clientWidth) / 2;
            nav.scrollTo({ left, behavior: 'smooth' });
          }
        }

        items.forEach(i => i.addEventListener('click', () => activate(i.dataset.tab)));
      }
    }

    // 页面加载完成后初始化
    document.addEventListener('DOMContentLoaded', function() {
      window.memberCenter = new MemberCenter();
    });

    // 退出登录（与 header 保持一致）
    function logout(){
      if(!confirm('确定要退出登录吗？')) return;
      fetch('/api/user.php?action=logout')
        .then(r=>r.json())
        .then(d=>{ if(d?.success || d?.code===0) location.href='/'; else location.href='/'; })
        .catch(()=> location.href='/');
    }
  </script>
</body>
</html>

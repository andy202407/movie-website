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
    .member-info-card::before{
      content:"";
      position:absolute; left:0; right:0; top:0; height:3px;
      background:linear-gradient(90deg,var(--pri),var(--pri2),#f093fb);
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

    /* ====== 大屏细化 ====== */
    @media (min-width: 1024px){
      .member-stats{ grid-template-columns: repeat(4, 1fr); max-width:640px; margin-inline:auto }
    }
    @media (min-width: 768px) and (max-width: 1023px){
      .member-stats{ grid-template-columns: repeat(4, 1fr); }
    }

    /* ====== 移动端：独立布局优化 ====== */
    @media (max-width: 767px){
      /* 信息卡片顶部：两列布局（左头像，右昵称+密码），下方统计满行 */
      .member-info-card{
        display:grid;
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
      .member-stats{
        grid-column: 1 / -1;                            /* 关键：让统计区占满两列 */
        display: grid;
        width: 100%;
        grid-template-columns: repeat(2, minmax(140px, 1fr));
        gap: 12px;
        margin-top:14px; margin-bottom:18px;
      }
      .stat-item{ 
        padding:12px 8px; 
        min-width: 0;                                   /* 防止子项把列撑爆 */
        min-height: auto;
      }
      .stat-number{ 
        font-size:16px; 
        margin-bottom:4px;
      }
      .stat-label{ 
        font-size:11px; 
        line-height:1.2;
      }

      /* 超小屏：如果太挤再改回 1 列 */
      @media (max-width: 380px){
        .member-info-card .member-stats{
          grid-template-columns: repeat(2, minmax(120px, 1fr));
        }
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

    /* 超小屏（<=480px）：统计改 1 列，防止拥挤 */
    @media (max-width: 480px){
      .member-stats{ grid-template-columns: 1fr; }
      .member-name{ font-size:20px }
      .member-avatar{ width:65px; height:65px; font-size:26px }
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
            <span class="stat-number">0</span>
            <div class="stat-label">我的收藏</div>
          </div>
          <div class="stat-item">
            <span class="stat-number">0</span>
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
          <div class="tab-nav-item active" data-tab="favorites">我的收藏</div>
          <div class="tab-nav-item" data-tab="history">观看历史</div>
          <!-- <div class="tab-nav-item" data-tab="profile">账户信息</div> -->
          <div class="tab-nav-item" data-tab="vip">VIP特权</div>
        </div>

        <div class="tab-content">
          <div class="tab-pane active" id="favorites">
            <div class="empty-state">
              <div class="empty-state-icon">⭐</div>
              <div class="empty-state-text">您还没有收藏任何影片<br>开始收藏您喜欢的影视作品吧！</div>
              <a href="/" class="discover-btn">去发现更多好片</a>
            </div>
          </div>

          <div class="tab-pane" id="history">
            <div class="empty-state">
              <div class="empty-state-icon">📺</div>
              <div class="empty-state-text">您还没有观看记录<br>开始观看精彩影视内容吧！</div>
              <a href="/" class="discover-btn">去发现更多好片</a>
            </div>
          </div>

          <div class="tab-pane" id="profile">
            <div class="empty-state">
              <div class="empty-state-icon">👤</div>
              <div class="empty-state-text">账户信息管理功能开发中<br>敬请期待更多功能！</div>
              <a href="/" class="discover-btn">返回首页</a>
            </div>
          </div>

          <div class="tab-pane" id="vip">
            <div class="empty-state">
              <div class="empty-state-icon">👑</div>
              <div class="empty-state-text">VIP特权功能享受中</div>
              <a href="/" class="discover-btn">了解VIP特权</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include dirname(__DIR__) . '/user/components/footer.php'; ?>

  <script>
    // 标签页切换 + 移动端滚动对齐
    document.addEventListener('DOMContentLoaded', function () {
      const items = document.querySelectorAll('.tab-nav-item');
      const panes = document.querySelectorAll('.tab-pane');
      const nav = document.getElementById('tabNav');

      function activate(target){
        items.forEach(i=>i.classList.toggle('active', i.dataset.tab === target));
        panes.forEach(p=>p.classList.toggle('active', p.id === target));
        // 移动端把激活项滚到可视区域
        const active = [...items].find(i=>i.dataset.tab === target);
        if(active && window.matchMedia('(max-width: 767px)').matches){
          const left = active.offsetLeft - (nav.clientWidth - active.clientWidth)/2;
          nav.scrollTo({ left, behavior:'smooth' });
        }
      }

      items.forEach(i=>{
        i.addEventListener('click', ()=> activate(i.dataset.tab));
      });
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

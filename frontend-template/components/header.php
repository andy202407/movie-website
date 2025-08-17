<?php
// 引入必要的类
require_once dirname(__DIR__) . '/../Database.php';
require_once dirname(__DIR__) . '/../VideoModel.php';

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
<style>
/* 新的水平导航样式 */
.header-container {
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(20px);
}

/* 第一行：Logo + 功能图标 */
.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    /* border-bottom: 1px solid rgba(255, 255, 255, 0.05); */
    height: 50px;
}

/* PC端header高度响应式 */
@media (min-width: 1024px) {
    .header-top {
        height: 60px;
        padding: 1.2rem 2rem;
    }
}

@media (min-width: 1440px) {
    .header-top {
        height: 70px;
        padding: 1.5rem 2rem;
    }
}

@media (min-width: 1920px) {
    .header-top {
        height: 80px;
        padding: 1.8rem 2rem;
    }
}

.logo-section {
    display: flex;
    align-items: center;
    flex-shrink: 0;
    min-width: 160px;
}

.logo-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #fff;
    transition: all 0.3s ease;
    padding: 0.5rem;
    border-radius: 12px;
    position: relative;
    overflow: hidden;
}

.logo-brand::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
    transition: left 0.6s ease;
}

.logo-brand:hover::before {
    left: 100%;
}

.logo-brand:hover {
    color: #667eea;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.logo1, .logo2 {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    filter: drop-shadow(0 2px 8px rgba(102, 126, 234, 0.2));
    display: block !important;
}

.logo-brand:hover .logo1,
.logo-brand:hover .logo2 {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.logo-text {
    font-size: 1.4rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: none;
    letter-spacing: 0.8px;
    position: relative;
    white-space: nowrap;
    display: inline-block;
}

/* 移除发光边框效果 */

/* PC端logo响应式尺寸 */
@media (min-width: 1024px) {
    .logo1, .logo2 {
        width: 45px;
        height: 45px;
        border-radius: 11px;
    }
    
    .logo-text {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 0.9px;
    }
    
    .logo-section {
        min-width: 180px;
    }
}

@media (min-width: 1440px) {
    .logo1, .logo2 {
        width: 50px;
        height: 50px;
        border-radius: 12px;
    }
    
    .logo-text {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: 1px;
    }
    
    .logo-section {
        min-width: 200px;
    }
}

@media (min-width: 1920px) {
    .logo1, .logo2 {
        width: 55px;
        height: 55px;
        border-radius: 13px;
    }
    
    .logo-text {
        font-size: 1.7rem;
        font-weight: 800;
        letter-spacing: 1.1px;
    }
    
    .logo-section {
        min-width: 220px;
    }
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.this-search {
    position: relative;
}

/* 搜索框容器样式 */
.flex-public {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 0.4rem;
    transition: all 0.3s ease;
    max-width: 500px;
}

.flex-public:hover,
.flex-public:focus-within {
    background: rgba(255, 255, 255, 0.15);
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* 搜索类型选择器 */
.this-select {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.8rem;
    color: #fff;
    text-decoration: none;
    font-size: 0.85rem;
    border-radius: 16px;
    background: rgba(102, 126, 234, 0.2);
    transition: all 0.3s ease;
    white-space: nowrap;
}

.this-select:hover {
    background: rgba(102, 126, 234, 0.3);
    color: #fff;
    text-decoration: none;
}

.this-select i {
    font-size: 0.8rem;
    transition: transform 0.3s ease;
}

.this-select:hover i {
    transform: rotate(180deg);
}

/* 搜索输入框 */
.this-input {
    flex: 1;
    min-width: 180px;
    padding: 0.4rem 0.8rem;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 0.85rem;
    outline: none;
    margin: 0 0.4rem;
}

.this-input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

/* 右侧按钮组 */
.this-search .flex {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* 排行榜链接 */
.this-hot {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.7rem;
    color: #fff;
    text-decoration: none;
    font-size: 0.8rem;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    white-space: nowrap;
}

.this-hot:hover {
    background: rgba(102, 126, 234, 0.2);
    color: #fff;
    text-decoration: none;
}

.this-hot em {
    font-size: 0.9rem;
}

/* 搜索按钮 */
.this-search button[type="submit"] {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #667eea;
    border: none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.this-search button[type="submit"]:hover {
    background: #5a6fd8;
    transform: scale(1.1);
}

/* 通用flex布局类 */
.flex {
    display: flex;
    align-items: center;
}

.flex-auto {
    flex: 1;
}

.flex-public {
    display: flex;
    align-items: center;
}

/* 颜色类 */
.cor4 {
    color: #fff;
}

.ol2 {
    background: #667eea;
    color: #fff;
}

.r3 {
    color: #ff6b6b;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.mobile-search, .gen-history, .gen-left-list, .user-center {
    position: relative;
}

.mobile-search a, .gen-history a, .gen-left-list a, .user-center a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.mobile-search a:hover, .gen-history a:hover, .gen-left-list a:hover, .user-center a:hover {
    background: rgba(102, 126, 234, 0.2);
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.gen-history i, .gen-left-list i, .user-center i {
    font-size: 1.1rem;
}

/* PC端显示搜索框，隐藏移动端搜索图标 */
.mobile-search {
    display: none;
}

.mobile-search a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.mobile-search a:hover {
    background: rgba(102, 126, 234, 0.2);
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.mobile-search i {
    font-size: 1.1rem;
}

/* 第二行：主导航菜单 */
.header-nav {
    padding: 0.8rem 2rem;
    background: rgba(0, 0, 0, 0.2);
    overflow-x: auto !important; /* 强制启用水平滚动 */
    overflow-y: hidden !important; /* 强制隐藏垂直滚动 */
    position: relative;
    /* 确保容器有固定宽度 */
    width: 100%;
    box-sizing: border-box;
    /* 平滑滚动 */
    scroll-behavior: smooth;
    /* 触摸滚动优化 */
    -webkit-overflow-scrolling: touch;
    /* 隐藏滚动条但保持功能 */
    scrollbar-width: none !important; /* Firefox */
    -ms-overflow-style: none !important; /* IE and Edge */
    /* 强制启用触摸滚动 */
    touch-action: pan-x !important;
}

.header-nav::-webkit-scrollbar {
    display: none; /* Chrome, Safari and Opera */
}

/* 添加滚动测试按钮 */
.scroll-test-btn {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    padding: 0.3rem 0.6rem;
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8rem;
    z-index: 100;
}

/* 滑动指示器样式 */
.scroll-indicator {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    background: rgba(102, 126, 234, 0.8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.scroll-indicator:hover {
    background: rgba(102, 126, 234, 1);
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.scroll-indicator.left {
    left: 0.5rem;
}

.scroll-indicator.right {
    right: 0.5rem;
}

.scroll-indicator i {
    font-size: 0.9rem;
}

.scroll-indicator.left i {
    transform: rotate(180deg);
}

.main-nav {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    min-width: max-content;
    /* 确保内容可以超出容器 */
    flex-shrink: 0;
    width: max-content;
    /* 平滑过渡 */
    transition: all 0.3s ease;
}

.main-nav::-webkit-scrollbar {
    display: none; /* Chrome, Safari and Opera */
}

.main-nav:active {
    cursor: grabbing;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    color: #bdc3c7;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
    white-space: nowrap;
    flex-direction: row;
    min-width: auto;
}

.nav-item:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-1px);
}

.nav-item.active {
    color: #fff;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.nav-item i {
    font-size: 1rem;
}



/* 用户下拉菜单 */
.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: rgba(0, 0, 0, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 0.5rem 0;
    min-width: 120px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    z-index: 1001;
    backdrop-filter: blur(10px);
}

.user-center:hover .user-dropdown {
    display: block;
}

.dropdown-item {
    display: block;
    padding: 0.5rem 1rem;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #667eea;
    text-decoration: none;
}

/* 移动端菜单样式 */
.mobile-menu-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 280px;
    height: 100vh;
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    z-index: 10000;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.5);
    overflow-y: auto;
}

.mobile-menu-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.mobile-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 1.5rem 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.mobile-menu-header h3 {
    color: #fff;
    margin: 0;
    font-size: 1.2rem;
    font-weight: 500;
}

.close-menu-btn {
    background: none;
    border: none;
    color: #bdc3c7;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.close-menu-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.mobile-menu-content {
    padding: 1rem 0;
}

.mobile-menu-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    color: #bdc3c7;
    text-decoration: none;
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.mobile-menu-item:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.05);
    text-decoration: none;
}

.mobile-menu-item.active {
    color: #fff;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.mobile-menu-item i {
    font-size: 1.2rem;
    width: 20px;
    text-align: center;
}

.mobile-menu-item span {
    font-size: 1rem;
    font-weight: 500;
}

/* 响应式设计 */
@media (max-width: 1024px) {
    .header-top {
        padding: 1rem;
    }
    
    .header-nav {
        padding: 0.6rem 1rem;
    }
    
    .search-input {
        width: 250px;
    }
    
    .main-nav {
        gap: 0.3rem;
    }
    
    .nav-item {
        padding: 0.5rem 0.8rem;
        font-size: 0.85rem;
    }
    
    /* 移动端弹窗样式 */
    .pop-1, .pop-2, .pop-bj {
        display: none;
    }
    
    /* 弹窗显示时的样式 - 使用!important覆盖common.css */
    .pop-list-body {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        right: auto !important;
        transform: translate(-50%, -50%) !important;
        background: #1a1a1a !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8) !important;
        width: 90vw !important;
        max-width: 400px !important;
        height: auto !important;
        max-height: 80vh !important;
        overflow-y: auto !important;
        z-index: 10001 !important;
        opacity: 1 !important;
        visibility: visible !important;
        transition: none !important;
    }
    
    .box-bg2.pop-bj {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background: rgba(0, 0, 0, 0.7) !important;
        z-index: 10000 !important;
    }
}

@media (max-width: 768px) {
    .header-top {
        /* flex-direction: column; */
        gap: 1rem;
        padding: 1rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    /* 移动端隐藏搜索框 */
    .this-search {
        display: none;
    }
    
    /* 移动端显示搜索图标 */
    .mobile-search {
        display: block;
    }
    
    /* 移动端弹窗样式已在上方定义 */
    
    .header-nav {
        padding: 0.5rem 1rem;
        overflow: hidden;
    }
    
    .main-nav {
        gap: 0.5rem;
        min-width: max-content;
        padding: 0 0.5rem;
    }
    
    .nav-item {
        padding: 0.5rem 0.8rem;
        font-size: 0.85rem;
        flex-direction: row;
        gap: 0.4rem;
        min-width: auto;
        white-space: nowrap;
    }
    
    .nav-item span {
        display: inline;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .nav-item i {
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    /* 移动端logo优化 */
    .logo1, .logo2 {
        width: 40px;
        height: 40px;
        /* margin-right: 10px; */
    }
    
    .logo-text {
        font-size: 1.5rem;
        letter-spacing: 0.5px;
    }
    
    .logo-brand {
        padding: 0.3rem;
    }
    
    /* 移动端滑动指示器优化 */
    .scroll-indicator {
        width: 28px;
        height: 28px;
    }
    
    .scroll-indicator.left {
        left: 0.3rem;
    }
    
    .scroll-indicator.right {
        right: 0.3rem;
    }
    
    .scroll-indicator i {
        font-size: 0.8rem;
    }
}

/* 超小屏幕适配 */
@media (max-width: 480px) {
    .header-top {
        padding: 0.8rem;
        gap: 0.8rem;
    }
    
    .header-nav {
        padding: 0.4rem 0.8rem;
    }
    
    .main-nav {
        gap: 0.3rem;
        padding: 0 0.3rem;
    }
    
    .nav-item {
        padding: 0.4rem 0.6rem;
        font-size: 0.75rem;
        gap: 0.3rem;
    }
    
    .nav-item span {
        font-size: 0.75rem;
    }
    
    .nav-item i {
        font-size: 0.9rem;
    }
    
    .logo1, .logo2 {
        width: 40px;
        height: 40px;
        /* margin-right: 8px; */
    }
    
    .logo-text {
        font-size: 1.3rem;
    }
    
    .search-input {
        max-width: 250px;
        padding: 0.5rem 0.8rem;
        font-size: 0.85rem;
    }
    
    .search-btn {
        width: 28px;
        height: 28px;
    }
    
    .gen-history a, .gen-left-list a, .user-center a, .mobile-search a {
        width: 35px;
        height: 35px;
    }
}

/* 隐藏PC端搜索按钮 */
@media (min-width: 768px) {
    /* 隐藏主搜索表单的提交按钮 */
    .this-search button[type="submit"] {
        display: none !important;
    }
    
    /* 隐藏独立的搜索按钮 */
    .gen-search {
        display: none !important;
    }
    
    /* 隐藏任何其他可能的搜索按钮 */
    .this-search .fa.ds-sousuo {
        display: none !important;
    }
    
    /* PC端隐藏移动端搜索图标 */
    .mobile-search {
        display: none;
    }
}

/* Logo样式 */
.site-logo {
    font-size: 18px;
    font-weight: bold;
    color: #667eea;
    text-decoration: none;
    padding: 10px 0;
    display: block;
    text-align: center;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.site-logo:hover {
    color: #fff;
    text-decoration: none;
}

/* 播放记录弹窗样式 */
.gen-history-list {
    max-height: 80vh;
    overflow-y: auto;
}

/* 云端记录链接样式 - 不激活状态 */
.cloud-link {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.cloud-link:hover {
    opacity: 1;
}

.cloud-link a {
    color: #888;
    text-decoration: none;
}

.cloud-link a:hover {
    color: #667eea;
}

/* 用户中心样式 */
.user-center {
    position: relative;
}

.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: rgba(0, 0, 0, 0.9);
    border-radius: 8px;
    padding: 10px 0;
    min-width: 120px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    backdrop-filter: blur(10px);
}

.dropdown-item {
    display: block;
    padding: 8px 20px;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 14px;
}

.dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #667eea;
    text-decoration: none;
}

.user-center:hover .user-dropdown {
    display: block;
}

.play-catalog {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 20px;
}

.play-catalog span {
    padding: 10px 20px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.play-catalog span.on {
    color: #667eea;
    border-bottom: 2px solid #667eea;
}

.play-catalog span:hover {
    color: #667eea;
}

.history-item {
    margin-bottom: 15px;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.history-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.history-link {
    display: flex;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.history-link:hover {
    background: rgba(255, 255, 255, 0.1);
    text-decoration: none;
    color: inherit;
}

.history-poster {
    width: 60px;
    height: 80px;
    margin-right: 15px;
    flex-shrink: 0;
}

.history-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.history-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.history-title {
    font-weight: bold;
    color: #fff;
    margin-bottom: 5px;
    font-size: 14px;
}

.history-time {
    color: #999;
    font-size: 12px;
    margin-bottom: 5px;
}

.history-progress {
    color: #667eea;
    font-size: 12px;
}

.no-history {
    text-align: center;
    color: #999;
    padding: 40px 20px;
    font-size: 14px;
}

/* 云端登录提示样式 */
.cloud-login-prompt {
    text-align: center;
    padding: 40px 20px;
}

.cloud-icon {
    margin-bottom: 20px;
}

.cloud-text {
    color: #999;
    font-size: 16px;
    margin-bottom: 25px;
}

.cloud-login-btn {
    display: inline-block;
    background: #667eea;
    color: #fff;
    padding: 12px 30px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s ease;
}

.cloud-login-btn:hover {
    background: #5a6fd8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    text-decoration: none;
    color: #fff;
}

/* 响应式设计 */
@media (max-width: 768px) {
    .history-poster {
        width: 50px;
        height: 70px;
        margin-right: 12px;
    }
    
    .history-title {
        font-size: 13px;
    }
    
    .history-time,
    .history-progress {
        font-size: 11px;
    }
    
    .cloud-login-prompt {
        padding: 30px 15px;
    }
    
    .cloud-text {
        font-size: 14px;
    }
    
    .cloud-login-btn {
        padding: 10px 25px;
        font-size: 14px;
    }
}
</style>

<!-- Logo显示 -->
<!-- <div class="site-logo">
    <a href="/" style="color: inherit; text-decoration: none;">
        星海影院
    </a>
</div> -->

<!-- 新的水平导航布局 -->
<div class="header-container">
    <!-- 第一行：Logo + 功能图标 -->
    <div class="header-top">
        <div class="logo-section">
            <a class="logo-brand" href="/">
                <img class="logo2" src="/template/yuyuyy/asset/img/logo-2.png" alt="星海影院">
                <span class="logo-text">星海影院</span>
            </a>
        </div>
        <div class="header-actions">
            <!-- PC端搜索框 -->
            <div class="this-search">
                <form id="search2" name="search" class="flex-public" method="get" action="/search">
                    <a href="javascript:" data-id="1" class="this-select">视频<i class="fa">&#xe563;</i></a>
                    <input type="text" name="wd" class="this-input flex-auto cor4" value="<?= $this->escape($_GET['wd'] ?? '') ?>" placeholder="搜索影片..." autocomplete="off">
                    <div class="flex">
                        <a class="this-hot" href="/label/rank.html"><em class="fa ds-pahang2 r3"></em>排行榜</a>
                        <button type="submit" class="fa ds-sousuo ol2"></button>
                    </div>
                </form>
            </div>
            <div class="action-buttons">
                <div class="mobile-search" title="搜索" id="mobileSearchBtn">
                    <a href="javascript:void(0)">
                        <i class="fa ds-sousuo"></i>
                    </a>
                </div>
                <div class="gen-history" title="播放记录">
                    <a href="javascript:">
                <i class="fa ds-lishijilu"></i>
            </a>
        </div>
                <!-- <div class="gen-left-list" title="菜单">
                    <a href="javascript:">
                <i class="fa ds-menu"></i>
            </a>
                </div> -->
                <div class="user-center" id="userCenter">
            <?php if ($isLoggedIn && $userInfo): ?>
                        <a href="/user" id="userBtn" title="用户中心">
                    <i class="fa ds-yonghu"></i>
                </a>
                <div class="user-dropdown" style="display:none;">
                    <a href="/user" class="dropdown-item">个人中心</a>
                    <a href="javascript:void(0)" class="dropdown-item" onclick="logout()">退出登录</a>
                </div>
            <?php else: ?>
                        <a href="/user/login" id="loginBtn" title="登录">
                            <i class="fa ds-yonghu"></i>
                </a>
            <?php endif; ?>
                </div>
        </div>
        </div>
    </div>
    
    <!-- 第二行：主导航菜单 -->
    <div class="header-nav">
        <nav class="main-nav">
            <a href="/" class="nav-item <?= ($_GET['page'] ?? 'home') == 'home' ? 'active' : '' ?>">
                <i class="fa ds-zhuye"></i>
                <span>首页</span>
            </a>
            <?php foreach ($parentCategories as $cat): ?>
            <a href="/?page=list&category=<?= $cat['id'] ?>" class="nav-item <?= (($_GET['page'] ?? '') == 'list' && ($_GET['category'] ?? '') == $cat['id']) ? 'active' : '' ?>">
                <?php if ($cat['name'] == '电影'): ?>
                    <i class="fa ds-dianying"></i>
                <?php elseif ($cat['name'] == '电视剧'): ?>
                    <i class="fa ds-dianshi"></i>
                <?php elseif ($cat['name'] == '综艺'): ?>
                    <i class="fa ds-zongyi"></i>
                <?php elseif ($cat['name'] == '动漫'): ?>
                    <i class="fa ds-dongman"></i>
                <?php elseif ($cat['name'] == '短剧'): ?>
                    <i class="fa ds-dianshi"></i>
                <?php elseif ($cat['name'] == '纪录片'): ?>
                    <i class="fa ds-ziyuan"></i>
                <?php elseif ($cat['name'] == '有声电子书'): ?>
                    <i class="fa ds-ziyuan"></i>
                <?php else: ?>
                    <i class="fa ds-dianying"></i>
                <?php endif; ?>
                <span><?= $this->escape($cat['name']) ?></span>
            </a>
            <?php endforeach; ?>
            <a href="/" class="nav-item">
                <i class="fa ds-zhou"></i>
                <span>每日更新</span>
            </a>
        </nav>
    </div>
</div>

<!-- 移动端搜索弹窗 -->
<div class="box-bg2 pop-bj" style="display:none; z-index: 10000;"></div>
<div class="pop-list-body bj pop-1" style="display:none; z-index: 10001;">
    <div class="wap-head" style="display:none">
        <div class="l">
            <a class="fa pop-bj cor4" href="javascript:">&#xe566;</a>
        </div>
        <h2 class="hidden">
            <a class="cor4 b" href="javascript:">搜索</a>
        </h2>
    </div>
    <div class="wap-head-z"></div>
    <div class="head-search input bj br">
        <form id="search" name="search" method="get" action="/search">
            <input type="text" name="wd" class="search-input mac_wd cor5" value="" placeholder="输入影片名称或关键词..." autocomplete="off" />
            <button type="submit" class="search-input-sub button fa ds-sousuo"></button>
            <button type="button" class="select-name cor5" data-id="1">视频<i class="fa cor5">&#xe563;</i></button>
        </form>
    </div>
    <div class="select-list bj radius br none">
        <div class="flex between ease cor4">
            <span data-id="1" data-name="视频" data-url="/search">视频</span>
        </div>
    </div>
    <div class="completion cor5 ft3"></div>
    <div class="public-list-new top30">
        <div class="title-m cor4 flex between">
            <h5>搜索历史</h5>
            <a id="re_del" class="cor6 ho" style="font-size:14px"><i class="fa r3 ds-shanchu"></i>删除</a>
        </div>
        <div class="records-list">
            <!-- 搜索历史将通过JavaScript动态添加 -->
        </div>
    </div>
    <div class="wap-diy-vod-e search-hot top30">
        <div class="title-m cor4 flex between">
            <h5>热门搜索</h5>
        </div>
        <div>
            <ul>
                <li><a href="/search?wd=复仇者联盟" class="vod-link br b-b"><span class="vod-on-e-styles key1 cor5">1</span><div class="vod-center">复仇者联盟</div></a></li>
                <li><a href="/search?wd=泰坦尼克号" class="vod-link br b-b"><span class="vod-on-e-styles key2 cor5">2</span><div class="vod-center">泰坦尼克号</div></a></li>
                <li><a href="/search?wd=星际穿越" class="vod-link br b-b"><span class="vod-on-e-styles key3 cor5">3</span><div class="vod-center">星际穿越</div></a></li>
                <li><a href="/search?wd=寄生虫" class="vod-link br b-b"><span class="vod-on-e-styles key4 cor5">4</span><div class="vod-center">寄生虫</div></a></li>
                <li><a href="/search?wd=闪灵" class="vod-link br b-b"><span class="vod-on-e-styles key5 cor5">5</span><div class="vod-center">闪灵</div></a></li>
            </ul>
        </div>
    </div>
</div>

<!-- 播放记录弹窗 -->
<div class="pop-list-body gen-history-list bj pop-2" style="display:none; z-index: 10001;">
    <div class="wap-head" style="display:none">
        <div class="l">
            <a class="fa pop-bj cor4" href="javascript:">&#xe566;</a>
        </div>
        <h2 class="hidden">
            <a class="cor4 b" href="javascript:">播放记录</a>
        </h2>
    </div>
    <div class="wap-head-z"></div>
    <div class="play-catalog flex around ft4 b">
        <span class="rel on" data-tab="local"><a href="javascript:">本地记录</a></span>
        <span class="cloud-link"><a href="/user/">云端记录</a></span>
    </div>
    <div class="top30 mask-1-box">
        <div class="locality-history history check" id="localTab">
            <ul id="localHistoryList">
                <!-- 本地播放记录将通过JavaScript动态添加 -->
            </ul>
            <a id="l_history" href="javascript:" class="button top30 ol2" style="width:100%">清空记录</a>
        </div>
        <div class="user-history history" id="cloudTab" style="display:none;">
            <div class="cloud-login-prompt">
                <div class="cloud-icon">
                    <i class="fa ds-yonghu" style="font-size: 48px; color: #667eea;"></i>
                </div>
                <div class="cloud-text">请登录后查看</div>
                <a href="javascript:" class="cloud-login-btn">立即登录</a>
            </div>
        </div>
    </div>
</div>

<!-- 搜索功能JavaScript -->
<script>
// 全局变量
let isDragging = false;
let startX = 0;
let startScrollLeft = 0;

// 测试滚动函数
function testScroll() {
    const headerNav = document.querySelector('.header-nav');
    const mainNav = document.querySelector('.main-nav');
    
    if (!headerNav || !mainNav) return;
    
    const maxScroll = headerNav.scrollWidth - headerNav.offsetWidth;
    
    if (maxScroll > 0) {
        headerNav.scrollLeft = maxScroll;
        
        setTimeout(() => {
            headerNav.scrollLeft = 0;
        }, 2000);
    }
}

// 显示滚动提示效果
function showScrollHint() {
    const headerNav = document.querySelector('.header-nav');
    if (!headerNav) return;
    
    const maxScroll = headerNav.scrollWidth - headerNav.offsetWidth;
    if (maxScroll <= 0) return;
    
    // 先滚动到右边
    headerNav.scrollTo({
        left: maxScroll,
        behavior: 'smooth'
    });
    
    // 1.5秒后滚动回左边
    setTimeout(() => {
        headerNav.scrollTo({
            left: 0,
            behavior: 'smooth'
        });
    }, 1500);
}

// 用户登录状态检查（简化版）
    function checkUserLoginStatus() {
    // 这里可以添加用户登录状态检查逻辑
    // 暂时为空，避免错误
    }
    
    // 移动端搜索功能
    function initMobileSearch() {
        const mobileSearchBtn = document.getElementById('mobileSearchBtn');
        const searchPopup = document.querySelector('.pop-list-body.bj.pop-1');
        const searchBg = document.querySelector('.box-bg2.pop-bj');
        
        if (mobileSearchBtn && searchPopup && searchBg) {
            mobileSearchBtn.addEventListener('click', function() {
                searchPopup.style.display = 'block';
                searchBg.style.display = 'block';
            });
        }
    }
    
    // 移动端历史记录功能
    function initMobileHistory() {
        const historyBtn = document.querySelector('.gen-history a');
        const historyPopup = document.querySelector('.pop-list-body.gen-history-list.bj.pop-2');
        const historyBg = document.querySelector('.box-bg2.pop-bj');
        
        if (historyBtn && historyPopup && historyBg) {
            historyBtn.addEventListener('click', function(e) {
                e.preventDefault();
                historyPopup.style.display = 'block';
                historyBg.style.display = 'block';
            });
        }
    }
    
    // 关闭弹窗功能
    function initClosePopups() {
        const popupBg = document.querySelector('.box-bg2.pop-bj');
        
        if (popupBg) {
            popupBg.addEventListener('click', function() {
                // 隐藏所有弹窗
                const allPopups = document.querySelectorAll('.pop-list-body');
                allPopups.forEach(popup => {
                    popup.style.display = 'none';
                });
                popupBg.style.display = 'none';
            });
        }
        
        // ESC键关闭弹窗
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const allPopups = document.querySelectorAll('.pop-list-body');
                const popupBg = document.querySelector('.box-bg2.pop-bj');
                
                allPopups.forEach(popup => {
                    popup.style.display = 'none';
                });
                if (popupBg) {
                    popupBg.style.display = 'none';
                }
            }
        });
    }
    
    // 页面加载完成后检查用户登录状态
    document.addEventListener('DOMContentLoaded', function() {
    try {
        checkUserLoginStatus();
        initMobileSearch(); // 初始化移动端搜索
        initMobileHistory(); // 初始化移动端历史记录
        initClosePopups(); // 初始化弹窗关闭功能
    } catch (error) {
        console.error('检查用户登录状态时出错:', error);
    }
    
    // 只在首页添加自动滑动提示效果
    const isHome = isHomePage();
    console.log('当前页面是否为首页:', isHome, 'URL:', window.location.href);
    
    if (isHome) {
        setTimeout(showScrollHint, 1000);
    }
});

// 检查是否为首页
function isHomePage() {
    // 检查URL路径
    const path = window.location.pathname;
    
    // 检查URL参数
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page');
    const category = urlParams.get('category');
    
    // 如果是根路径且没有其他参数，或者是明确的首页
    if (path === '/' && !page && !category) {
        return true;
    }
    
    // 如果有明确的page参数，检查是否为home
    if (page === 'home') {
        return true;
    }
    
    // 其他情况都不是首页
    return false;
}
</script>

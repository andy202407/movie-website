<?php
require_once dirname(__DIR__) . '/../Database.php';
require_once dirname(__DIR__) . '/../models/UserModel.php';
require_once dirname(__DIR__) . '/../VideoModel.php';

// 检查是否已登录
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    header('Location: /user/login');
    exit;
}

$userModel = new UserModel();
$userInfo = $userModel->getUserById($_SESSION['user_id']);

if (!$userInfo) {
    session_destroy();
    header('Location: /user/login');
    exit;
}

$pageTitle = '用户中心 - 个人账户管理 - 星河影院';
$pageKeywords = '用户中心,个人中心,账户管理,观看记录,收藏夹,星河影院';
$pageDescription = '星河影院用户中心,管理您的个人账户、观看记录、收藏夹、观看历史等个人信息,享受个性化的观影体验。';
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
    <meta name="theme-color" content="#1a1a1a" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <title><?= $pageTitle ?></title>
    <meta name="keywords" content="<?= $pageKeywords ?>">
    <meta name="description" content="<?= $pageDescription ?>">
    <link rel="stylesheet" href="/template/yuyuyy/asset/css/common.css">
    <script src="/template/yuyuyy/asset/js/jquery.js"></script>
    <script src="/template/yuyuyy/asset/js/assembly.js"></script>
    <style>
        /* 用户中心专用样式 */
        .user-center-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 0;
        }
        
        .user-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .user-header-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .user-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
        }
        
        .user-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            margin: 0 auto 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            border: 4px solid rgba(255, 255, 255, 0.8);
        }
        
        .user-name {
            font-size: 32px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .user-email {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .user-stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-bottom: 30px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            min-width: 120px;
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
        }
        
        .user-tabs {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .tab-nav {
            display: flex;
            background: linear-gradient(90deg, #f8f9fa, #e9ecef);
            border-bottom: 1px solid #dee2e6;
        }
        
        .tab-nav-item {
            flex: 1;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            color: #666;
        }
        
        .tab-nav-item.active {
            background: #fff;
            color: #667eea;
            border-bottom-color: #667eea;
        }
        
        .tab-nav-item:hover {
            background: rgba(102, 126, 234, 0.1);
        }
        
        .tab-content {
            padding: 40px;
            min-height: 400px;
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-state-icon {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-state-text {
            font-size: 18px;
            margin-bottom: 30px;
            color: #999;
        }
        
        .discover-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .discover-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .user-content {
                padding: 0 15px;
            }
            
            .user-header-card {
                padding: 30px 20px;
            }
            
            .user-avatar {
                width: 100px;
                height: 100px;
                font-size: 36px;
            }
            
            .user-name {
                font-size: 24px;
            }
            
            .user-stats {
                flex-direction: column;
                gap: 20px;
            }
            
            .stat-item {
                min-width: auto;
            }
            
            .tab-nav {
                flex-direction: column;
            }
            
            .tab-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body class="theme2">
    <!-- 引入头部组件 -->
    <?php include dirname(__DIR__) . '/components/header.php'; ?>

    <!-- 用户中心主体内容 -->
    <div class="user-center-container">
        <div class="user-content">
            <!-- 用户信息卡片 -->
            <div class="user-header-card">
                <div class="user-avatar">
                    <?= strtoupper(substr($userInfo['username'], 0, 1)) ?>
                </div>
                <div class="user-name"><?= htmlspecialchars($userInfo['username']) ?></div>
                <div class="user-email"><?= htmlspecialchars($userInfo['email']) ?></div>
                
                <div class="user-stats">
                    <div class="stat-item">
                        <span class="stat-number">0</span>
                        <div class="stat-label">收藏</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">0</span>
                        <div class="stat-label">观看记录</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= date('Y-m-d', strtotime($userInfo['created_at'])) ?></span>
                        <div class="stat-label">注册时间</div>
                    </div>
                </div>
                
                <a href="/api/user.php?action=logout" class="logout-btn">退出登录</a>
            </div>

            <!-- 功能标签页 -->
            <div class="user-tabs">
                <div class="tab-nav">
                    <div class="tab-nav-item active" data-tab="favorites">我的收藏</div>
                    <div class="tab-nav-item" data-tab="history">观看历史</div>
                    <div class="tab-nav-item" data-tab="profile">账户信息</div>
                </div>
                
                <div class="tab-content">
                    <!-- 我的收藏 -->
                    <div class="tab-pane active" id="favorites">
                        <div class="empty-state">
                            <div class="empty-state-icon">⭐</div>
                            <div class="empty-state-text">您还没有收藏任何影片</div>
                            <a href="/" class="discover-btn">去发现更多好片</a>
                        </div>
                    </div>
                    
                    <!-- 观看历史 -->
                    <div class="tab-pane" id="history">
                        <div class="empty-state">
                            <div class="empty-state-icon">📺</div>
                            <div class="empty-state-text">您还没有观看记录</div>
                            <a href="/" class="discover-btn">去发现更多好片</a>
                        </div>
                    </div>
                    
                    <!-- 账户信息 -->
                    <div class="tab-pane" id="profile">
                        <div class="empty-state">
                            <div class="empty-state-icon">👤</div>
                            <div class="empty-state-text">账户信息管理功能开发中</div>
                            <a href="/" class="discover-btn">返回首页</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 引入底部组件 -->
    <?php include dirname(__DIR__) . '/components/footer.php'; ?>

    <script>
        // 标签页切换功能
        document.addEventListener('DOMContentLoaded', function() {
            const tabItems = document.querySelectorAll('.tab-nav-item');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabItems.forEach(item => {
                item.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // 移除所有active类
                    tabItems.forEach(tab => tab.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    
                    // 添加active类到当前标签
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>

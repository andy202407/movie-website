<?php
require_once dirname(__DIR__) . '/../Database.php';
require_once dirname(__DIR__) . '/../models/UserModel.php';
require_once dirname(__DIR__) . '/../VideoModel.php';

// 检查是否已登录
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
    header('Location: /user/');
    exit;
}

// 会员登录页面
$pageTitle = '会员登录 - 星海影院';
$pageKeywords = '会员登录,用户登录,星海影院';
$pageDescription = '星海影院会员登录页面，登录后享受更多专属服务。';
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover">
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
        /* 会员登录暗黑模式样式 */
        body {
            background: #0a0a0a;
            color: #ffffff;
            font-family: 'Microsoft YaHei', Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        /* 登录页面主体 */
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.05) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        
        .login-card {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(10, 10, 10, 0.95) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 50px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 2;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
            border-radius: 24px 24px 0 0;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .login-title {
            font-size: 28px;
            color: #fff;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .login-subtitle {
            color: #888;
            font-size: 16px;
        }
        
        .login-form {
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            color: #ccc;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-input::placeholder {
            color: #666;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .checkbox-input {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }
        
        .checkbox-label {
            color: #ccc;
            font-size: 14px;
            cursor: pointer;
        }
        
        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .login-divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        
        .login-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .divider-text {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(10, 10, 10, 0.95) 100%);
            padding: 0 20px;
            color: #888;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .register-text {
            color: #888;
            font-size: 14px;
        }
        
        .register-btn {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .register-btn:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .login-card {
                padding: 40px 30px;
                margin: 20px;
            }
            
            .login-logo {
                width: 70px;
                height: 70px;
                font-size: 35px;
            }
            
            .login-title {
                font-size: 24px;
            }
            
            .form-input {
                padding: 14px 18px;
                font-size: 15px;
            }
            
            .login-btn {
                padding: 15px 18px;
                font-size: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .login-logo {
                width: 60px;
                height: 60px;
                font-size: 30px;
            }
            
            .login-title {
                font-size: 22px;
            }
            
            .form-input {
                padding: 12px 16px;
                font-size: 14px;
            }
            
            .login-btn {
                padding: 14px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- 引入会员中心专用Header -->
    <?php include dirname(__DIR__) . '/user/components/header.php'; ?>

    <!-- 登录页面主体 -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">⭐</div>
                <h1 class="login-title">欢迎回来</h1>
                <p class="login-subtitle">登录您的星海影院账户</p>
            </div>
            
            <form class="login-form" id="loginForm">
                <div class="form-group">
                    <label class="form-label" for="username">用户名</label>
                    <input type="text" id="username" name="username" class="form-input" placeholder="请输入用户名" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">密码</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="请输入密码" required>
                </div>
                
                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember" class="checkbox-input" value="1">
                        <label for="remember" class="checkbox-label">记住我</label>
                    </div>
                    <a href="#" class="forgot-link">忘记密码？</a>
                </div>
                
                <button type="submit" class="login-btn">登录</button>
            </form>
            
            <div class="login-divider">
                <span class="divider-text">还没有账户？</span>
            </div>
            
            <div class="register-link">
                <span class="register-text">立即</span>
                <a href="/user/register" class="register-btn">注册账户</a>
            </div>
        </div>
    </div>

    <!-- 引入会员中心专用Footer -->
    <?php include dirname(__DIR__) . '/user/components/footer.php'; ?>

    <script>
        // 登录表单处理
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            
            if (!username || !password) {
                alert('请填写完整的登录信息');
                return;
            }
            
            // 显示加载状态
            const loginBtn = document.querySelector('.login-btn');
            const originalText = loginBtn.textContent;
            loginBtn.textContent = '登录中...';
            loginBtn.disabled = true;
            
            // 发送登录请求
            const formData = new FormData();
            formData.append('username', username);
            formData.append('password', password);
            formData.append('remember', remember ? '1' : '0');
            
            fetch('/api/user.php?action=login', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 登录成功
                    alert('登录成功！');
                    window.location.href = '/user';
                } else {
                    // 登录失败
                    alert(data.message || '登录失败，请检查用户名和密码');
                }
            })
            .catch(error => {
                console.error('登录请求失败:', error);
                alert('网络错误，请重试');
            })
            .finally(() => {
                // 恢复按钮状态
                loginBtn.textContent = originalText;
                loginBtn.disabled = false;
            });
        });
        
        // 输入框焦点效果
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>

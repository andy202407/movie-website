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

$pageTitle = '用户登录 - 星海影院';
$pageKeywords = '用户登录,账户登录,星海影院';
$pageDescription = '星海影院用户登录页面，登录您的个人账户，享受个性化观影体验。';
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <meta name="keywords" content="<?= $pageKeywords ?>">
    <meta name="description" content="<?= $pageDescription ?>">
    <link rel="stylesheet" href="/template/yuyuyy/asset/css/common.css">
    <script src="/template/yuyuyy/asset/js/jquery.js"></script>
    <script src="/template/yuyuyy/asset/js/assembly.js"></script>
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }
        
        .form-group .error {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .remember-me input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }
        
        .remember-me label {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .login-btn:hover {
            background: #5a6fd8;
        }
        
        .login-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .success-message {
            color: #27ae60;
            text-align: center;
            margin-top: 15px;
            display: none;
        }
        
        .error-message {
            color: #e74c3c;
            text-align: center;
            margin-top: 15px;
            display: none;
        }
        
        .loading {
            display: none;
            text-align: center;
            margin-top: 15px;
        }
        
        .loading span {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #667eea;
            animation: loading 1.4s infinite ease-in-out both;
        }
        
        .loading span:nth-child(1) { animation-delay: -0.32s; }
        .loading span:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes loading {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
        
        @media (max-width: 768px) {
            .login-container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body class="theme2">
    <!-- 引入头部组件 -->
    <?php include dirname(__DIR__) . '/components/header.php'; ?>
    
    <div class="login-container">
        <div class="login-header">
            <h1>用户登录</h1>
            <p>登录您的星海影院账户</p>
        </div>
        
        <form id="loginForm">
            <div class="form-group">
                <label for="username">用户名/邮箱</label>
                <input type="text" id="username" name="username" placeholder="请输入用户名或邮箱" required>
                <div class="error" id="username-error"></div>
            </div>
            
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" placeholder="请输入密码" required>
                <div class="error" id="password-error"></div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">记住我</label>
            </div>
            
            <button type="submit" class="login-btn" id="submitBtn">立即登录</button>
        </form>
        
        <div class="success-message" id="successMessage"></div>
        <div class="error-message" id="errorMessage"></div>
        <div class="loading" id="loading">
            <span></span>
            <span></span>
        </div>
        
        <div class="register-link">
            还没有账户？<a href="/user/register">立即注册</a>
        </div>
    </div>
    
    <!-- 引入底部组件 -->
    <?php include dirname(__DIR__) . '/components/footer.php'; ?>
    
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // 清除之前的错误信息
            clearErrors();
            
            // 获取表单数据
            const formData = new FormData(this);
            const username = formData.get('username').trim();
            const password = formData.get('password');
            
            // 验证输入
            if (!username || !password) {
                showError('username-error', '请填写完整信息');
                return;
            }
            
            // 提交登录
            submitLogin(formData);
        });
        
        function submitLogin(formData) {
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            // 显示加载状态
            submitBtn.disabled = true;
            submitBtn.textContent = '登录中...';
            loading.style.display = 'block';
            successMessage.style.display = 'none';
            errorMessage.style.display = 'none';
            
            // 发送登录请求
            fetch('/api/user.php?action=login', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successMessage.textContent = data.message;
                    successMessage.style.display = 'block';
                    
                    // 延迟跳转到用户中心
                    setTimeout(() => {
                        window.location.href = '/user';
                    }, 1500);
                } else {
                    errorMessage.textContent = data.message;
                    errorMessage.style.display = 'block';
                }
            })
            .catch(error => {
                errorMessage.textContent = '网络错误，请重试';
                errorMessage.style.display = 'block';
            })
            .finally(() => {
                // 恢复按钮状态
                submitBtn.disabled = false;
                submitBtn.textContent = '立即登录';
                loading.style.display = 'none';
            });
        }
        
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
        
        function clearErrors() {
            const errorElements = document.querySelectorAll('.error');
            errorElements.forEach(element => {
                element.style.display = 'none';
            });
        }
    </script>
</body>
</html>

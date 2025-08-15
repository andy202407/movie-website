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

$pageTitle = '用户注册 - 星海影院';
$pageKeywords = '用户注册,账户注册,星海影院';
$pageDescription = '星海影院用户注册页面，创建您的个人账户，享受个性化观影体验。';
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
        .register-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .register-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .register-header p {
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
        
        .register-btn {
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
        
        .register-btn:hover {
            background: #5a6fd8;
        }
        
        .register-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
        }
        
        .login-link a:hover {
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
            .register-container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body class="theme2">
    <!-- 引入头部组件 -->
    <?php include dirname(__DIR__) . '/components/header.php'; ?>
    
    <div class="register-container">
        <div class="register-header">
            <h1>用户注册</h1>
            <p>创建您的星海影院账户</p>
        </div>
        
        <form id="registerForm">
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" placeholder="请输入3-20位用户名" required>
                <div class="error" id="username-error"></div>
            </div>
            
            <div class="form-group">
                <label for="email">邮箱</label>
                <input type="email" id="email" name="email" placeholder="请输入邮箱地址" required>
                <div class="error" id="email-error"></div>
            </div>
            
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" placeholder="请输入至少6位密码" required>
                <div class="error" id="password-error"></div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">确认密码</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="请再次输入密码" required>
                <div class="error" id="confirm_password-error"></div>
            </div>
            
            <button type="submit" class="register-btn" id="submitBtn">立即注册</button>
        </form>
        
        <div class="success-message" id="successMessage"></div>
        <div class="error-message" id="errorMessage"></div>
        <div class="loading" id="loading">
            <span></span>
            <span></span>
        </div>
        
        <div class="login-link">
            已有账户？<a href="/user/login">立即登录</a>
        </div>
    </div>
    
    <!-- 引入底部组件 -->
    <?php include dirname(__DIR__) . '/components/footer.php'; ?>
    
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // 清除之前的错误信息
            clearErrors();
            
            // 获取表单数据
            const formData = new FormData(this);
            const username = formData.get('username').trim();
            const email = formData.get('email').trim();
            const password = formData.get('password');
            const confirmPassword = formData.get('confirm_password');
            
            // 验证输入
            let hasError = false;
            
            if (username.length < 3 || username.length > 20) {
                showError('username-error', '用户名长度应在3-20个字符之间');
                hasError = true;
            }
            
            if (!isValidEmail(email)) {
                showError('email-error', '请输入有效的邮箱地址');
                hasError = true;
            }
            
            if (password.length < 6) {
                showError('password-error', '密码长度至少6位');
                hasError = true;
            }
            
            if (password !== confirmPassword) {
                showError('confirm_password-error', '两次输入的密码不一致');
                hasError = true;
            }
            
            if (hasError) {
                return;
            }
            
            // 提交注册
            submitRegister(formData);
        });
        
        function submitRegister(formData) {
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            // 显示加载状态
            submitBtn.disabled = true;
            submitBtn.textContent = '注册中...';
            loading.style.display = 'block';
            successMessage.style.display = 'none';
            errorMessage.style.display = 'none';
            
            // 发送注册请求
            fetch('/api/user.php?action=register', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successMessage.textContent = data.message;
                    successMessage.style.display = 'block';
                    
                    // 延迟跳转到登录页
                    setTimeout(() => {
                        window.location.href = '/user/login';
                    }, 2000);
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
                submitBtn.textContent = '立即注册';
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
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</body>
</html>

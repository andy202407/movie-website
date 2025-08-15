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

// 会员注册页面
$pageTitle = '会员注册 - 星海影院';
$pageKeywords = '会员注册,用户注册,星海影院';
$pageDescription = '星海影院会员注册页面，使用用户名和密码注册成为会员享受更多专属服务。';
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
        /* 会员注册暗黑模式样式 */
        body {
            background: #0a0a0a;
            color: #ffffff;
            font-family: 'Microsoft YaHei', Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        /* 注册页面主体 */
        .register-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .register-container::before {
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
        
        .register-card {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(10, 10, 10, 0.95) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 50px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 2;
        }
        

        
        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .register-logo {
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
        
        .register-title {
            font-size: 28px;
            color: #fff;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .register-subtitle {
            color: #888;
            font-size: 16px;
        }
        
        .register-form {
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
        
        .form-input.error {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
        }
        
        .error-message {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        
        .password-strength {
            margin-top: 10px;
            padding: 10px;
            border-radius: 8px;
            font-size: 12px;
            display: none;
        }
        
        .strength-weak {
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(255, 107, 107, 0.2);
        }
        
        .strength-medium {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        
        .strength-strong {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
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
        
        .terms-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .terms-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .register-btn {
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
        
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }
        
        .register-btn:active {
            transform: translateY(0);
        }
        
        .register-btn:disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .register-divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }
        
        .register-divider::before {
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
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-text {
            color: #888;
            font-size: 14px;
        }
        
        .login-btn {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-btn:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .register-card {
                padding: 40px 30px;
                margin: 20px;
            }
            
            .register-logo {
                width: 70px;
                height: 70px;
                font-size: 35px;
            }
            
            .register-title {
                font-size: 24px;
            }
            
            .form-input {
                padding: 14px 18px;
                font-size: 15px;
            }
            
            .register-btn {
                padding: 15px 18px;
                font-size: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .register-card {
                padding: 30px 20px;
            }
            
            .register-logo {
                width: 60px;
                height: 60px;
                font-size: 30px;
            }
            
            .register-title {
                font-size: 22px;
            }
            
            .form-input {
                padding: 12px 16px;
                font-size: 14px;
            }
            
            .register-btn {
                padding: 14px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- 引入会员中心专用Header -->
    <?php include dirname(__DIR__) . '/user/components/header.php'; ?>

    <!-- 注册页面主体 -->
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="register-logo">⭐</div>
                <h1 class="register-title">加入星海影院</h1>
                <p class="register-subtitle">注册成为会员享受更多专属服务</p>
            </div>
            
            <form class="register-form" id="registerForm">
                <div class="form-group">
                    <label class="form-label" for="username">用户名</label>
                    <input type="text" id="username" name="username" class="form-input" placeholder="请输入3-20位用户名" required>
                    <div class="error-message" id="username-error"></div>
                </div>
                

                
                <div class="form-group">
                    <label class="form-label" for="password">密码</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="请输入至少6位密码" required>
                    <div class="password-strength" id="password-strength"></div>
                    <div class="error-message" id="password-error"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="confirm_password">确认密码</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="请再次输入密码" required>
                    <div class="error-message" id="confirm_password-error"></div>
                </div>
                
                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="agree" name="agree" class="checkbox-input" value="1" required>
                        <label for="agree" class="checkbox-label">我已阅读并同意</label>
                    </div>
                    <a href="#" class="terms-link">服务条款</a>
                </div>
                
                <button type="submit" class="register-btn" id="registerBtn">立即注册</button>
            </form>
            
            <div class="register-divider">
                <span class="divider-text">已有账户？</span>
            </div>
            
            <div class="login-link">
                <span class="login-text">立即</span>
                <a href="/user/login" class="login-btn">登录账户</a>
            </div>
        </div>
    </div>

    <!-- 引入会员中心专用Footer -->
    <?php include dirname(__DIR__) . '/user/components/footer.php'; ?>

    <script>
        // 注册表单处理
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // 清除之前的错误信息
            clearErrors();
            
            // 获取表单数据
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const agree = document.getElementById('agree').checked;
            
            // 验证输入
            let hasError = false;
            
            if (username.length < 3 || username.length > 20) {
                showError('username-error', '用户名长度应在3-20个字符之间');
                hasError = true;
            }
            
            // 检测用户名是否包含手机号格式
            const phonePattern = /1[3-9]\d{9}/;
            if (phonePattern.test(username)) {
                showError('username-error', '用户名不能包含手机号格式，请使用其他用户名');
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
            
            if (!agree) {
                alert('请阅读并同意服务条款');
                return;
            }
            
            if (hasError) {
                return;
            }
            
            // 提交注册
            submitRegister();
        });
        
        // 用户名实时验证
        document.getElementById('username').addEventListener('input', function() {
            const username = this.value.trim();
            const errorElement = document.getElementById('username-error');
            const inputElement = this;
            
            // 清除之前的错误状态
            errorElement.style.display = 'none';
            inputElement.classList.remove('error');
            
            if (username.length > 0) {
                // 检测是否包含手机号格式
                const phonePattern = /1[3-9]\d{9}/;
                if (phonePattern.test(username)) {
                    showError('username-error', '用户名不能包含手机号格式，请使用其他用户名');
                }
            }
        });
        
        // 密码强度检测
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthElement = document.getElementById('password-strength');
            
            if (password.length === 0) {
                strengthElement.style.display = 'none';
                return;
            }
            
            let strength = 0;
            let message = '';
            let className = '';
            
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            if (strength <= 2) {
                message = '密码强度：弱';
                className = 'strength-weak';
            } else if (strength <= 4) {
                message = '密码强度：中等';
                className = 'strength-medium';
            } else {
                message = '密码强度：强';
                className = 'strength-strong';
            }
            
            strengthElement.textContent = message;
            strengthElement.className = 'password-strength ' + className;
            strengthElement.style.display = 'block';
        });
        
        function submitRegister() {
            const registerBtn = document.getElementById('registerBtn');
            const originalText = registerBtn.textContent;
            
            // 显示加载状态
            registerBtn.textContent = '注册中...';
            registerBtn.disabled = true;
            
            // 准备表单数据
            const formData = new FormData();
            formData.append('username', document.getElementById('username').value.trim());
            formData.append('password', document.getElementById('password').value);
            
            // 发送注册请求
            fetch('/api/user.php?action=register', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 注册成功
                    alert('注册成功！即将跳转到登录页面');
                    setTimeout(() => {
                        window.location.href = '/user/login';
                    }, 1500);
                } else {
                    // 注册失败
                    alert(data.message || '注册失败，请重试');
                }
            })
            .catch(error => {
                console.error('注册请求失败:', error);
                alert('网络错误，请重试');
            })
            .finally(() => {
                // 恢复按钮状态
                registerBtn.textContent = originalText;
                registerBtn.disabled = false;
            });
        }
        
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            const inputElement = document.getElementById(elementId.replace('-error', ''));
            
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            inputElement.classList.add('error');
        }
        
        function clearErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            const inputElements = document.querySelectorAll('.form-input');
            
            errorElements.forEach(element => {
                element.style.display = 'none';
            });
            
            inputElements.forEach(element => {
                element.classList.remove('error');
            });
        }
        

        
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

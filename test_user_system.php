<?php
// 测试用户系统
echo "<h1>用户系统测试</h1>";

// 测试1：数据库连接
echo "<h2>1. 数据库连接测试</h2>";
try {
    require_once 'Database.php';
    $db = Database::getInstance();
    $connection = $db->getConnection();
    echo "<p style='color: green;'>✓ 数据库连接成功</p>";
    
    // 测试用户表
    $stmt = $connection->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✓ 用户表存在，共有 {$result['count']} 个用户</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ 数据库连接失败: " . $e->getMessage() . "</p>";
}

// 测试2：模型加载
echo "<h2>2. 模型加载测试</h2>";
try {
    require_once 'models/UserModel.php';
    $userModel = new UserModel();
    echo "<p style='color: green;'>✓ UserModel 加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ UserModel 加载失败: " . $e->getMessage() . "</p>";
}

// 测试3：控制器加载
echo "<h2>3. 控制器加载测试</h2>";
try {
    require_once 'controllers/UserController.php';
    $userController = new UserController();
    echo "<p style='color: green;'>✓ UserController 加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ UserController 加载失败: " . $e->getMessage() . "</p>";
}

// 测试4：文件路径测试
echo "<h2>4. 文件路径测试</h2>";
$files = [
    'frontend-template/user/login.php',
    'frontend-template/user/register.php',
    'frontend-template/user/index.php',
    'api/user.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✓ {$file} 存在</p>";
    } else {
        echo "<p style='color: red;'>✗ {$file} 不存在</p>";
    }
}

// 测试5：路由测试
echo "<h2>5. 路由测试</h2>";
echo "<p>当前URL: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";
echo "<p>当前脚本: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "</p>";

// 测试6：API测试
echo "<h2>6. API测试</h2>";
echo "<p><a href='/api/user.php?action=check_favorite&video_id=1'>测试收藏检查API</a></p>";

// 测试7：用户页面测试
echo "<h2>7. 用户页面测试</h2>";
echo "<p><a href='/user/login'>用户登录页面</a></p>";
echo "<p><a href='/user/register'>用户注册页面</a></p>";

// 测试8：直接包含测试
echo "<h2>8. 直接包含测试</h2>";
echo "<p>尝试直接包含用户登录页面：</p>";
echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
try {
    ob_start();
    include 'frontend-template/user/login.php';
    $content = ob_get_clean();
    echo "<p style='color: green;'>✓ 用户登录页面包含成功</p>";
    echo "<div style='max-height: 200px; overflow: auto;'>";
    echo htmlspecialchars(substr($content, 0, 500)) . "...";
    echo "</div>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ 用户登录页面包含失败: " . $e->getMessage() . "</p>";
}
echo "</div>";

echo "<h2>9. 系统信息</h2>";
echo "<p>PHP版本: " . PHP_VERSION . "</p>";
echo "<p>当前目录: " . getcwd() . "</p>";
echo "<p>文档根目录: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "</p>";
?>

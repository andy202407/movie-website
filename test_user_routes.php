<?php
// 测试用户路由
echo "<h1>用户路由测试</h1>";

// 测试数据库连接
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

// 测试模型加载
try {
    require_once 'models/UserModel.php';
    $userModel = new UserModel();
    echo "<p style='color: green;'>✓ UserModel 加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ UserModel 加载失败: " . $e->getMessage() . "</p>";
}

// 测试控制器加载
try {
    require_once 'controllers/UserController.php';
    $userController = new UserController();
    echo "<p style='color: green;'>✓ UserController 加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ UserController 加载失败: " . $e->getMessage() . "</p>";
}

// 测试API文件
try {
    require_once 'api/user.php';
    echo "<p style='color: green;'>✓ API文件加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ API文件加载失败: " . $e->getMessage() . "</p>";
}

echo "<h2>测试链接</h2>";
echo "<p><a href='/user/login'>用户登录页面</a></p>";
echo "<p><a href='/user/register'>用户注册页面</a></p>";
echo "<p><a href='/api/user.php?action=check_favorite&video_id=1'>测试API接口</a></p>";

echo "<h2>当前URL信息</h2>";
echo "<p>REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";
echo "<p>SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "</p>";
echo "<p>PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'N/A') . "</p>";
?>

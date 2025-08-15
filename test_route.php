<?php
// 测试路由逻辑
echo "<h1>路由测试</h1>";

// 模拟不同的URL路径
$testPaths = [
    '',
    'home',
    'user',
    'user/login',
    'user/register',
    'api/user'
];

foreach ($testPaths as $testPath) {
    echo "<h3>测试路径: '{$testPath}'</h3>";
    
    // 模拟 $_SERVER['REQUEST_URI']
    $_SERVER['REQUEST_URI'] = '/' . $testPath;
    
    // 解析路径
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = ltrim($path, '/');
    
    echo "<p>原始路径: '{$_SERVER['REQUEST_URI']}'</p>";
    echo "<p>处理后路径: '{$path}'</p>";
    
    // 测试用户路径匹配
    if (strpos($path, 'user/') === 0) {
        $userAction = substr($path, 5);
        echo "<p style='color: green;'>✓ 匹配用户路径，action: '{$userAction}'</p>";
    } else {
        echo "<p style='color: red;'>✗ 不匹配用户路径</p>";
    }
    
    // 测试API路径匹配
    if (strpos($path, 'api/') === 0) {
        echo "<p style='color: green;'>✓ 匹配API路径</p>";
    } else {
        echo "<p style='color: red;'>✗ 不匹配API路径</p>";
    }
    
    echo "<hr>";
}

// 测试文件包含
echo "<h2>文件包含测试</h2>";

try {
    require_once 'Database.php';
    echo "<p style='color: green;'>✓ Database.php 加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database.php 加载失败: " . $e->getMessage() . "</p>";
}

try {
    require_once 'models/UserModel.php';
    echo "<p style='color: green;'>✓ UserModel.php 加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ UserModel.php 加载失败: " . $e->getMessage() . "</p>";
}

try {
    require_once 'controllers/UserController.php';
    echo "<p style='color: green;'>✓ UserController.php 加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ UserController.php 加载失败: " . $e->getMessage() . "</p>";
}

echo "<h2>当前环境</h2>";
echo "<p>当前目录: " . getcwd() . "</p>";
echo "<p>REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";
echo "<p>SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "</p>";
?>

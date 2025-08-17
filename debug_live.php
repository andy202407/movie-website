<?php
// 实时缓存调试
require_once 'CacheManager.php';

echo "<h1>实时缓存调试</h1>";

// 获取当前请求信息
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$getParams = $_GET;

echo "<h2>1. 当前请求信息</h2>";
echo "REQUEST_URI: <strong>{$requestUri}</strong><br>";
echo "REQUEST_METHOD: {$requestMethod}<br>";
echo "GET参数: " . json_encode($getParams) . "<br>";

// 启动会话
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'none';
echo "用户ID: {$userId}<br>";

echo "<h2>2. 缓存管理器状态</h2>";
$cacheManager = new CacheManager();

echo "缓存目录: " . $cacheManager->getStats()['cache_dir'] . "<br>";
echo "shouldCache(): <strong>" . ($cacheManager->shouldCache() ? 'true' : 'false') . "</strong><br>";

echo "<h2>3. 缓存路径生成</h2>";

// 使用反射获取私有方法
$reflection = new ReflectionClass($cacheManager);
$method = $reflection->getMethod('generateCachePath');
$method->setAccessible(true);

$cachePath = $method->invoke($cacheManager, $requestUri, $getParams);

echo "目录: <strong>{$cachePath['dir']}</strong><br>";
echo "文件名: <strong>{$cachePath['file']}</strong><br>";
echo "完整路径: <strong>{$cachePath['fullPath']}</strong><br>";

echo "<h2>4. 缓存文件检查</h2>";
echo "缓存文件存在: <strong>" . (file_exists($cachePath['fullPath']) ? '是' : '否') . "</strong><br>";

if (file_exists($cachePath['fullPath'])) {
    $fileSize = filesize($cachePath['fullPath']);
    $fileTime = date('Y-m-d H:i:s', filemtime($cachePath['fullPath']));
    $fileAge = time() - filemtime($cachePath['fullPath']);
    echo "文件大小: {$fileSize} 字节<br>";
    echo "修改时间: {$fileTime}<br>";
    echo "文件年龄: {$fileAge} 秒<br>";
    
    // 检查是否过期
    if ($fileAge >= 86400) {
        echo "<span style='color: red;'>⚠️ 缓存已过期！</span><br>";
    } else {
        echo "<span style='color: green;'>✅ 缓存有效</span><br>";
    }
}

echo "<h2>5. 缓存读取测试</h2>";
$cachedContent = $cacheManager->get($requestUri, $getParams);
echo "读取缓存结果: <strong>" . ($cachedContent !== false ? '成功' : '失败') . "</strong><br>";

if ($cachedContent !== false) {
    echo "缓存内容长度: " . strlen($cachedContent) . " 字符<br>";
    echo "缓存内容开头: " . htmlspecialchars(substr($cachedContent, 0, 100)) . "...<br>";
} else {
    echo "<span style='color: red;'>❌ 缓存读取失败</span><br>";
}

echo "<h2>6. 缓存写入测试</h2>";
$testContent = "测试内容 - " . date('Y-m-d H:i:s');
$result = $cacheManager->set($requestUri, $testContent, $getParams);
echo "写入缓存结果: <strong>" . ($result ? '成功' : '失败') . "</strong><br>";

echo "<h2>7. 问题诊断</h2>";

if ($cacheManager->shouldCache() === false) {
    echo "<span style='color: red;'>❌ 缓存被禁用，原因可能是：</span><br>";
    echo "- 用户已登录<br>";
    echo "- 请求方法不是GET<br>";
    echo "- 是API请求<br>";
} elseif (file_exists($cachePath['fullPath']) === false) {
    echo "<span style='color: red;'>❌ 缓存文件不存在</span><br>";
} elseif ($cachedContent === false) {
    echo "<span style='color: red;'>❌ 缓存文件存在但读取失败</span><br>";
} else {
    echo "<span style='color: green;'>✅ 缓存系统正常工作</span><br>";
}

echo "<h2>8. 刷新测试</h2>";
echo "<p>刷新此页面，观察缓存状态变化。</p>";
echo "<p>当前时间: " . date('Y-m-d H:i:s') . "</p>";
?>

<?php
// 缓存系统详细调试
require_once 'CacheManager.php';

echo "<h1>缓存系统详细调试</h1>";

// 获取当前请求信息
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$getParams = $_GET;
$sessionStatus = session_status();

echo "<h2>1. 请求信息</h2>";
echo "REQUEST_URI: {$requestUri}<br>";
echo "REQUEST_METHOD: {$requestMethod}<br>";
echo "GET参数: " . json_encode($getParams) . "<br>";
echo "会话状态: {$sessionStatus}<br>";

// 启动会话检查用户状态
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'none';
echo "用户ID: {$userId}<br>";

echo "<h2>2. 缓存管理器测试</h2>";
$cacheManager = new CacheManager();

echo "缓存目录: " . $cacheManager->getStats()['cache_dir'] . "<br>";
echo "shouldCache(): " . ($cacheManager->shouldCache() ? 'true' : 'false') . "<br>";

echo "<h2>3. 缓存键生成测试</h2>";
$cacheKey1 = md5($requestUri);
$cacheKey2 = md5($requestUri . '?' . http_build_query($getParams));

echo "URI: {$requestUri}<br>";
echo "缓存键1 (仅URI): {$cacheKey1}<br>";
echo "缓存键2 (URI+参数): {$cacheKey2}<br>";

echo "<h2>4. 缓存文件检查</h2>";
$cacheFile1 = 'cache/pages/' . $cacheKey1 . '.html';
$cacheFile2 = 'cache/pages/' . $cacheKey1 . '.html';

echo "缓存文件1: {$cacheFile1}<br>";
echo "文件1存在: " . (file_exists($cacheFile1) ? '是' : '否') . "<br>";
echo "缓存文件2: {$cacheFile2}<br>";
echo "文件2存在: " . (file_exists($cacheFile2) ? '是' : '否') . "<br>";

echo "<h2>5. 现有缓存文件</h2>";
$cacheFiles = glob('cache/pages/*.html');
echo "缓存文件总数: " . count($cacheFiles) . "<br>";

foreach ($cacheFiles as $file) {
    $fileName = basename($file);
    $fileSize = filesize($file);
    $fileTime = date('Y-m-d H:i:s', filemtime($file));
    $fileAge = time() - filemtime($file);
    echo "文件: {$fileName}, 大小: {$fileSize} 字节, 时间: {$fileTime}, 年龄: {$fileAge}秒<br>";
}

echo "<h2>6. 缓存测试</h2>";
$testContent = "调试测试内容 - " . date('Y-m-d H:i:s');
$testResult = $cacheManager->set($requestUri, $testContent, $getParams);
echo "设置缓存结果: " . ($testResult ? '成功' : '失败') . "<br>";

$readResult = $cacheManager->get($requestUri, $getParams);
echo "读取缓存结果: " . ($readResult !== false ? '成功' : '失败') . "<br>";

if ($readResult !== false) {
    echo "缓存内容: " . htmlspecialchars(substr($readResult, 0, 100)) . "...<br>";
}

echo "<h2>7. 问题分析</h2>";
echo "<p>如果缓存系统正常工作，刷新此页面应该能看到缓存内容被读取。</p>";
echo "<p>如果每次都是新内容，说明缓存键生成或匹配有问题。</p>";
?>

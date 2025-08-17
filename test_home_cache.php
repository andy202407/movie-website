<?php
// 测试首页缓存
require_once 'CacheManager.php';

echo "<h1>首页缓存测试</h1>";

$cacheManager = new CacheManager();

// 模拟首页请求
$homeUrl = '/';
$homeParams = [];

echo "<h2>1. 首页缓存路径生成</h2>";

// 使用反射获取私有方法
$reflection = new ReflectionClass($cacheManager);
$method = $reflection->getMethod('generateCachePath');
$method->setAccessible(true);

$cachePath = $method->invoke($cacheManager, $homeUrl, $homeParams);

echo "首页URL: {$homeUrl}<br>";
echo "目录: {$cachePath['dir']}<br>";
echo "文件名: {$cachePath['file']}<br>";
echo "完整路径: {$cachePath['fullPath']}<br>";

echo "<h2>2. 缓存文件检查</h2>";
echo "缓存文件存在: " . (file_exists($cachePath['fullPath']) ? '是' : '否') . "<br>";

if (file_exists($cachePath['fullPath'])) {
    $fileSize = filesize($cachePath['fullPath']);
    $fileTime = date('Y-m-d H:i:s', filemtime($cachePath['fullPath']));
    $fileAge = time() - filemtime($cachePath['fullPath']);
    echo "文件大小: {$fileSize} 字节<br>";
    echo "修改时间: {$fileTime}<br>";
    echo "文件年龄: {$fileAge} 秒<br>";
}

echo "<h2>3. 缓存读取测试</h2>";
$cachedContent = $cacheManager->get($homeUrl, $homeParams);
echo "读取缓存结果: " . ($cachedContent !== false ? '成功' : '失败') . "<br>";

if ($cachedContent !== false) {
    echo "缓存内容长度: " . strlen($cachedContent) . " 字符<br>";
    echo "缓存内容开头: " . htmlspecialchars(substr($cachedContent, 0, 100)) . "...<br>";
}

echo "<h2>4. 现有缓存文件对比</h2>";
$existingFiles = glob('cache/pages/home/*.html');
echo "home目录下的缓存文件:<br>";
foreach ($existingFiles as $file) {
    $fileName = basename($file);
    $fileSize = filesize($file);
    $fileTime = date('Y-m-d H:i:s', filemtime($file));
    echo "- {$fileName} ({$fileSize} 字节, {$fileTime})<br>";
}

echo "<h2>5. 问题分析</h2>";
if ($cachedContent === false) {
    echo "<span style='color: red;'>❌ 首页缓存读取失败</span><br>";
    echo "可能原因：<br>";
    echo "1. 缓存键不匹配<br>";
    echo "2. 缓存文件过期<br>";
    echo "3. 缓存路径错误<br>";
} else {
    echo "<span style='color: green;'>✅ 首页缓存读取成功</span><br>";
}
?>

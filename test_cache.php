<?php
// 测试缓存系统
require_once 'CacheManager.php';

echo "<h1>缓存系统测试</h1>";

$cacheManager = new CacheManager();

// 测试URL
$testUrl = '/test_cache';
$testParams = ['test' => '1'];

echo "<h2>1. 缓存状态检查</h2>";
echo "shouldCache(): " . ($cacheManager->shouldCache() ? 'true' : 'false') . "<br>";
echo "当前时间: " . date('Y-m-d H:i:s') . "<br>";

echo "<h2>2. 缓存键生成</h2>";
$cacheKey = md5($testUrl . '?' . http_build_query($testParams));
echo "测试URL: {$testUrl}<br>";
echo "测试参数: " . json_encode($testParams) . "<br>";
echo "缓存键: {$cacheKey}<br>";

echo "<h2>3. 缓存文件检查</h2>";
$cacheFile = 'cache/pages/' . $cacheKey . '.html';
echo "缓存文件路径: {$cacheFile}<br>";
echo "文件存在: " . (file_exists($cacheFile) ? '是' : '否') . "<br>";

if (file_exists($cacheFile)) {
    echo "文件大小: " . filesize($cacheFile) . " 字节<br>";
    echo "修改时间: " . date('Y-m-d H:i:s', filemtime($cacheFile)) . "<br>";
    echo "文件年龄: " . (time() - filemtime($cacheFile)) . " 秒<br>";
}

echo "<h2>4. 缓存内容测试</h2>";
$testContent = "这是测试内容，时间: " . date('Y-m-d H:i:s');
echo "测试内容: {$testContent}<br>";

// 设置缓存
$result = $cacheManager->set($testUrl, $testContent, $testParams);
echo "设置缓存结果: " . ($result ? '成功' : '失败') . "<br>";

// 读取缓存
$cachedContent = $cacheManager->get($testUrl, $testParams);
echo "读取缓存结果: " . ($cachedContent !== false ? '成功' : '失败') . "<br>";

if ($cachedContent !== false) {
    echo "缓存内容: " . htmlspecialchars($cachedContent) . "<br>";
}

echo "<h2>5. 缓存目录状态</h2>";
$cacheFiles = glob('cache/pages/*.html');
echo "缓存文件总数: " . count($cacheFiles) . "<br>";

foreach (array_slice($cacheFiles, 0, 5) as $file) {
    $fileName = basename($file);
    $fileSize = filesize($file);
    $fileTime = date('Y-m-d H:i:s', filemtime($file));
    echo "文件: {$fileName}, 大小: {$fileSize} 字节, 时间: {$fileTime}<br>";
}

echo "<h2>6. 刷新测试</h2>";
echo "<p>刷新此页面，应该能看到缓存内容被读取。</p>";
echo "<p>当前时间: " . date('Y-m-d H:i:s') . "</p>";
?>

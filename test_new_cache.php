<?php
// 测试新的分层缓存系统
require_once 'CacheManager.php';

echo "<h1>新缓存系统测试</h1>";

$cacheManager = new CacheManager();

// 测试不同的URL路径
$testUrls = [
    '/' => '首页',
    '/category?id=1' => '分类页',
    '/video?id=123' => '视频页',
    '/play?id=456' => '播放页',
    '/search?wd=测试' => '搜索页'
];

echo "<h2>1. 缓存路径生成测试</h2>";

foreach ($testUrls as $url => $description) {
    echo "<h3>{$description}: {$url}</h3>";
    
    // 使用反射获取私有方法
    $reflection = new ReflectionClass($cacheManager);
    $method = $reflection->getMethod('generateCachePath');
    $method->setAccessible(true);
    
    $cachePath = $method->invoke($cacheManager, $url, []);
    
    echo "目录: {$cachePath['dir']}<br>";
    echo "文件名: {$cachePath['file']}<br>";
    echo "完整路径: {$cachePath['fullPath']}<br>";
    echo "<hr>";
}

echo "<h2>2. 缓存写入测试</h2>";

$testContent = "测试内容 - " . date('Y-m-d H:i:s');
$testUrl = '/test_new_cache';

$result = $cacheManager->set($testUrl, $testContent, []);
echo "写入缓存结果: " . ($result ? '成功' : '失败') . "<br>";

echo "<h2>3. 缓存读取测试</h2>";

$cachedContent = $cacheManager->get($testUrl, []);
echo "读取缓存结果: " . ($cachedContent !== false ? '成功' : '失败') . "<br>";

if ($cachedContent !== false) {
    echo "缓存内容: " . htmlspecialchars($cachedContent) . "<br>";
}

echo "<h2>4. 缓存目录结构</h2>";

$cacheDir = 'cache/pages/';
if (is_dir($cacheDir)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($cacheDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $relativePath = str_replace($cacheDir, '', $file->getPathname());
            $size = filesize($file->getPathname());
            $time = date('Y-m-d H:i:s', filemtime($file->getPathname()));
            echo "文件: {$relativePath}, 大小: {$size} 字节, 时间: {$time}<br>";
        }
    }
}

echo "<h2>5. 刷新测试</h2>";
echo "<p>刷新此页面，应该能看到缓存内容被读取。</p>";
echo "<p>当前时间: " . date('Y-m-d H:i:s') . "</p>";
?>

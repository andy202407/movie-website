<?php
// 测试缓存设置从数据库读取
require_once 'CacheManager.php';
require_once 'Database.php';

echo "<h1>缓存设置数据库读取测试</h1>";

echo "<h2>1. 数据库连接测试</h2>";
try {
    $db = Database::getInstance();
    echo "✅ 数据库连接成功<br>";
} catch (Exception $e) {
    echo "❌ 数据库连接失败: " . $e->getMessage() . "<br>";
    exit;
}

echo "<h2>2. cache_settings表数据</h2>";
try {
    $settings = $db->fetchAll("SELECT * FROM cache_settings ORDER BY id");
    echo "数据库中的缓存设置:<br>";
    foreach ($settings as $setting) {
        $value = $setting['key'] === 'cache_enabled' ? ($setting['value'] ? '启用' : '关闭') : $setting['value'];
        echo "- {$setting['key']}: {$value} ({$setting['description']})<br>";
    }
} catch (Exception $e) {
    echo "❌ 查询失败: " . $e->getMessage() . "<br>";
}

echo "<h2>3. CacheManager实例化测试</h2>";
try {
    $cacheManager = new CacheManager();
    echo "✅ CacheManager实例化成功<br>";
} catch (Exception $e) {
    echo "❌ CacheManager实例化失败: " . $e->getMessage() . "<br>";
}

echo "<h2>4. 缓存设置获取测试</h2>";
try {
    $stats = $cacheManager->getStats();
    echo "缓存统计信息:<br>";
    echo "- 缓存启用状态: " . ($stats['cache_enabled'] ? '启用' : '关闭') . "<br>";
    echo "- 缓存过期时间: {$stats['cache_ttl_hours']} 小时 ({$stats['cache_ttl']} 秒)<br>";
    echo "- 缓存目录: {$stats['cache_dir']}<br>";
} catch (Exception $e) {
    echo "❌ 获取统计信息失败: " . $e->getMessage() . "<br>";
}

echo "<h2>5. 缓存状态检查测试</h2>";
try {
    $shouldCache = $cacheManager->shouldCache();
    echo "shouldCache() 返回: " . ($shouldCache ? 'true' : 'false') . "<br>";
    
    if ($shouldCache) {
        echo "✅ 缓存系统正常工作<br>";
    } else {
        echo "❌ 缓存系统被禁用<br>";
    }
} catch (Exception $e) {
    echo "❌ 缓存状态检查失败: " . $e->getMessage() . "<br>";
}

echo "<h2>6. 实时设置更新测试</h2>";
echo "<p>注意：当前实现会在每次实例化时重新读取数据库设置。</p>";
echo "<p>如果需要实时更新，需要重新创建CacheManager实例。</p>";

echo "<h2>7. 性能测试</h2>";
$startTime = microtime(true);
for ($i = 0; $i < 10; $i++) {
    $testManager = new CacheManager();
    $testStats = $testManager->getStats();
}
$endTime = microtime(true);
$executionTime = ($endTime - $startTime) * 1000;

echo "10次实例化耗时: " . number_format($executionTime, 2) . " 毫秒<br>";
echo "平均每次: " . number_format($executionTime / 10, 4) . " 毫秒<br>";

echo "<h2>8. 建议优化</h2>";
echo "<p>当前实现每次实例化都会查询数据库，建议：</p>";
echo "<ul>";
echo "<li>使用静态缓存，避免重复查询</li>";
echo "<li>添加设置变更监听机制</li>";
echo "<li>实现配置热重载</li>";
echo "</ul>";

echo "<h2>9. 刷新测试</h2>";
echo "<p>刷新此页面，观察设置是否正常读取。</p>";
echo "<p>当前时间: " . date('Y-m-d H:i:s') . "</p>";
?>

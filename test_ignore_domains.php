<?php
// 测试忽略域名的数据库读取功能
require_once 'Database.php';
require_once 'visitor_config.php';

echo "<h1>忽略域名数据库读取测试</h1>";

echo "<h2>1. 数据库连接测试</h2>";
try {
    $db = Database::getInstance();
    echo "✅ 数据库连接成功<br>";
} catch (Exception $e) {
    echo "❌ 数据库连接失败: " . $e->getMessage() . "<br>";
    exit;
}

echo "<h2>2. 忽略域名表结构</h2>";
try {
    $tableInfo = $db->fetchAll("DESCRIBE ignore_domains");
    echo "表结构:<br>";
    foreach ($tableInfo as $column) {
        echo "- {$column['Field']} ({$column['Type']}) - {$column['Comment']}<br>";
    }
} catch (Exception $e) {
    echo "❌ 获取表结构失败: " . $e->getMessage() . "<br>";
}

echo "<h2>3. 当前忽略域名数据</h2>";
try {
    $domains = $db->fetchAll("SELECT * FROM ignore_domains ORDER BY id");
    echo "数据库中的忽略域名:<br>";
    if (empty($domains)) {
        echo "暂无数据<br>";
    } else {
        foreach ($domains as $domain) {
            $status = $domain['status'] === 'active' ? '✅ 启用' : '❌ 禁用';
            echo "- ID: {$domain['id']}, 域名: {$domain['domain']}, 状态: {$status}, 描述: {$domain['description']}, 创建时间: {$domain['created_at']}<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ 查询数据失败: " . $e->getMessage() . "<br>";
}

echo "<h2>4. 函数调用测试</h2>";
try {
    $ignoredDomains = getIgnoredDomainsFromDB();
    echo "getIgnoredDomainsFromDB() 返回结果:<br>";
    if (empty($ignoredDomains)) {
        echo "暂无忽略域名<br>";
    } else {
        foreach ($ignoredDomains as $domain) {
            echo "- {$domain}<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ 函数调用失败: " . $e->getMessage() . "<br>";
}

echo "<h2>5. 全局变量测试</h2>";
echo "全局变量 \$EXCLUDED_DOMAINS 内容:<br>";
if (empty($EXCLUDED_DOMAINS)) {
    echo "暂无忽略域名<br>";
} else {
    foreach ($EXCLUDED_DOMAINS as $domain) {
        echo "- {$domain}<br>";
    }
}

echo "<h2>6. 域名匹配测试</h2>";
$testDomains = [
    'm.ql83.com',
    'm.ql82.com', 
    'test.example.com',
    'admin.ql83.com'
];

echo "测试域名匹配结果:<br>";
foreach ($testDomains as $testDomain) {
    $isExcluded = in_array($testDomain, $EXCLUDED_DOMAINS);
    $status = $isExcluded ? '❌ 排除统计' : '✅ 正常统计';
    echo "- {$testDomain}: {$status}<br>";
}

echo "<h2>7. 性能测试</h2>";
$startTime = microtime(true);
for ($i = 0; $i < 100; $i++) {
    $ignoredDomains = getIgnoredDomainsFromDB();
}
$endTime = microtime(true);
$executionTime = ($endTime - $startTime) * 1000;

echo "100次函数调用耗时: " . number_format($executionTime, 2) . " 毫秒<br>";
echo "平均每次调用: " . number_format($executionTime / 100, 4) . " 毫秒<br>";

echo "<h2>8. 缓存建议</h2>";
echo "当前实现每次都会查询数据库，建议添加缓存机制:<br>";
echo "- 使用文件缓存<br>";
echo "- 使用内存缓存<br>";
echo "- 设置合理的缓存过期时间<br>";
?>

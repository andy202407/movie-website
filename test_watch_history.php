<?php
// 测试观看记录保存功能
echo "<h1>📺 观看记录保存功能测试</h1>";

// 测试数据库连接
try {
    require_once 'Database.php';
    $db = Database::getInstance();
    $connection = $db->getConnection();
    echo "<p style='color: green;'>✓ 数据库连接成功</p>";
    
    // 测试观看历史表
    $stmt = $connection->query("SELECT COUNT(*) as count FROM user_watch_history");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✓ 观看历史表存在，共有 {$result['count']} 条观看记录</p>";
    
    // 显示最近的观看记录
    $stmt = $connection->query("SELECT * FROM user_watch_history ORDER BY last_watched DESC LIMIT 5");
    $recentHistory = $stmt->fetchAll();
    
    if (!empty($recentHistory)) {
        echo "<h3>📋 最近的观看记录：</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>用户ID</th><th>视频ID</th><th>剧集</th><th>进度(秒)</th><th>最后观看时间</th></tr>";
        foreach ($recentHistory as $record) {
            echo "<tr>";
            echo "<td>{$record['user_id']}</td>";
            echo "<td>{$record['video_id']}</td>";
            echo "<td>{$record['episode']}</td>";
            echo "<td>{$record['progress']}</td>";
            echo "<td>{$record['last_watched']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ 暂无观看记录</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ 数据库连接失败: " . $e->getMessage() . "</p>";
}

// 测试API接口
echo "<h2>🧪 API接口测试</h2>";
echo "<p><a href='/api/user.php?action=get_watch_history&page=1&limit=5' target='_blank'>测试获取观看历史API</a></p>";

echo "<h2>📱 功能测试步骤</h2>";
echo "<ol>";
echo "<li>确保用户已登录（在header中显示用户名而不是'登录'）</li>";
echo "<li>访问播放页面：<a href='/?page=play&id=1' target='_blank'>测试播放页面</a></li>";
echo "<li>播放视频至少5秒</li>";
echo "<li>切换到其他标签页或最小化浏览器（触发页面可见性变化）</li>";
echo "<li>关闭播放页面（触发beforeunload事件）</li>";
echo "<li>返回此测试页面查看观看记录是否已保存</li>";
echo "</ol>";

echo "<h2>🔍 触发观看记录保存的事件</h2>";
echo "<ul>";
echo "<li><strong>页面可见性变化</strong>：当用户切换到其他标签页或应用时</li>";
echo "<li><strong>页面卸载</strong>：当用户关闭页面或刷新时</li>";
echo "<li><strong>播放结束</strong>：当视频播放完成时</li>";
echo "<li><strong>定时保存</strong>：每30秒自动保存一次</li>";
echo "</ul>";

echo "<h2>⚠️ 注意事项</h2>";
echo "<ul>";
echo "<li>只有登录用户才会保存观看记录到数据库</li>";
echo "<li>播放时间少于5秒不会保存记录</li>";
echo "<li>观看记录会同时保存到本地存储和数据库</li>";
echo "<li>检查浏览器控制台是否有相关日志信息</li>";
echo "</ul>";

echo "<h2>📊 当前系统状态</h2>";
echo "<p>当前时间: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP版本: " . PHP_VERSION . "</p>";
echo "<p>服务器: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";

echo "<h2>🔄 刷新测试</h2>";
echo "<p><a href='javascript:location.reload()'>点击刷新此页面</a> 查看最新的观看记录</p>";

echo "<h2>💡 调试建议</h2>";
echo "<ol>";
echo "<li>打开浏览器开发者工具的控制台</li>";
echo "<li>查看是否有'保存观看记录'相关的日志</li>";
echo "<li>检查Network标签页中的API请求</li>";
echo "<li>确认用户登录状态检测是否正常</li>";
echo "</ol>";
?>

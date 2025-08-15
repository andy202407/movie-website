<?php
// 测试会员中心观看历史显示功能
echo "<h1>🎬 会员中心观看历史显示测试</h1>";

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

echo "<h2>🔗 功能测试链接</h2>";
echo "<p><a href='/user/' target='_blank'>会员中心主页</a></p>";
echo "<p><a href='/user/?tab=history' target='_blank'>观看历史标签页</a></p>";

echo "<h2>🧪 API接口测试</h2>";
echo "<p><a href='/api/user.php?action=get_watch_history&page=1&limit=5' target='_blank'>测试获取观看历史API</a></p>";

echo "<h2>📱 测试步骤</h2>";
echo "<ol>";
echo "<li>确保用户已登录（在header中显示用户名而不是'登录'）</li>";
echo "<li>访问播放页面并观看视频：<a href='/?page=play&id=1' target='_blank'>测试播放页面</a></li>";
echo "<li>播放视频至少5秒，然后切换标签页或关闭页面</li>";
echo "<li>访问会员中心：<a href='/user/' target='_blank'>会员中心</a></li>";
echo "<li>点击'观看历史'标签页</li>";
echo "<li>查看观看记录是否正确显示</li>";
echo "</ol>";

echo "<h2>🔍 调试信息</h2>";
echo "<ul>";
echo "<li>打开浏览器开发者工具的控制台</li>";
echo "<li>查看是否有'开始加载观看历史'、'观看历史API响应'等日志</li>";
echo "<li>检查Network标签页中的API请求是否成功</li>";
echo "<li>确认观看历史数据是否正确渲染</li>";
echo "</ul>";

echo "<h2>⚠️ 常见问题</h2>";
echo "<ul>";
echo "<li><strong>显示空白</strong>：检查用户是否已登录，API是否返回数据</li>";
echo "<li><strong>数据不显示</strong>：检查控制台是否有错误信息</li>";
echo "<li><strong>图片不显示</strong>：检查海报URL是否正确，是否有默认图片</li>";
echo "<li><strong>时间格式错误</strong>：检查日期和时间数据格式</li>";
echo "</ul>";

echo "<h2>📊 当前系统状态</h2>";
echo "<p>当前时间: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP版本: " . PHP_VERSION . "</p>";
echo "<p>服务器: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";

echo "<h2>🔄 刷新测试</h2>";
echo "<p><a href='javascript:location.reload()'>点击刷新此页面</a> 查看最新的观看记录</p>";

echo "<h2>💡 预期结果</h2>";
echo "<ol>";
echo "<li>会员中心统计卡片显示正确的观看记录数量</li>";
echo "<li>观看历史标签页显示观看过的影片列表</li>";
echo "<li>每个观看记录显示：海报、标题、观看时间、剧集、进度</li>";
echo "<li>点击'继续观看'能跳转到正确的播放页面</li>";
echo "<li>支持手动刷新观看历史数据</li>";
echo "</ol>";

echo "<h2>🚀 下一步</h2>";
echo "<p>如果观看历史能正确显示，说明整个数据链条已经打通：</p>";
echo "<ul>";
echo "<li>✅ 前端播放页面能正确保存观看记录</li>";
echo "<li>✅ 后端API能正确处理和存储数据</li>";
echo "<li>✅ 会员中心能正确加载和显示数据</li>";
echo "<li>✅ 用户可以在会员中心管理观看历史</li>";
echo "</ul>";
?>

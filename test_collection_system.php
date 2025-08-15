<?php
// 测试收藏系统完整功能
echo "<h1>🎬 影视收藏系统测试页面</h1>";

// 测试数据库连接
try {
    require_once 'Database.php';
    $db = Database::getInstance();
    $connection = $db->getConnection();
    echo "<p style='color: green;'>✓ 数据库连接成功</p>";
    
    // 测试用户表
    $stmt = $connection->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✓ 用户表存在，共有 {$result['count']} 个用户</p>";
    
    // 测试收藏表
    $stmt = $connection->query("SELECT COUNT(*) as count FROM user_favorites");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✓ 收藏表存在，共有 {$result['count']} 条收藏记录</p>";
    
    // 测试观看历史表
    $stmt = $connection->query("SELECT COUNT(*) as count FROM user_watch_history");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✓ 观看历史表存在，共有 {$result['count']} 条观看记录</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ 数据库连接失败: " . $e->getMessage() . "</p>";
}

// 测试模型加载
try {
    require_once 'models/UserModel.php';
    $userModel = new UserModel();
    echo "<p style='color: green;'>✓ UserModel 加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ UserModel 加载失败: " . $e->getMessage() . "</p>";
}

// 测试控制器加载
try {
    require_once 'controllers/UserController.php';
    $userController = new UserController();
    echo "<p style='color: green;'>✓ UserController 加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ UserController 加载失败: " . $e->getMessage() . "</p>";
}

// 测试API文件
try {
    require_once 'api/user.php';
    echo "<p style='color: green;'>✓ API文件加载成功</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ API文件加载失败: " . $e->getMessage() . "</p>";
}

echo "<h2>🔗 功能测试链接</h2>";
echo "<p><a href='/user/login.php' target='_blank'>用户登录页面</a></p>";
echo "<p><a href='/user/register.php' target='_blank'>用户注册页面</a></p>";
echo "<p><a href='/user/' target='_blank'>会员中心</a></p>";

echo "<h2>🧪 API接口测试</h2>";
echo "<p><a href='/api/user.php?action=get_favorites&page=1&limit=5' target='_blank'>测试获取收藏列表</a></p>";
echo "<p><a href='/api/user.php?action=get_watch_history&page=1&limit=5' target='_blank'>测试获取观看历史</a></p>";

echo "<h2>📱 前端页面测试</h2>";
echo "<p><a href='/?page=play&id=1' target='_blank'>测试播放页面收藏功能</a></p>";

echo "<h2>📊 系统状态</h2>";
echo "<p>当前时间: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP版本: " . PHP_VERSION . "</p>";
echo "<p>服务器: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";

echo "<h2>⚠️ 注意事项</h2>";
echo "<p>1. 确保用户已登录才能测试收藏功能</p>";
echo "<p>2. 播放页面的收藏按钮需要JavaScript支持</p>";
echo "<p>3. 会员中心会自动加载用户的收藏和历史记录</p>";
echo "<p>4. 如果遇到问题，请检查浏览器控制台的错误信息</p>";

echo "<h2>🚀 完整功能流程</h2>";
echo "<ol>";
echo "<li>用户注册/登录</li>";
echo "<li>在播放页面点击收藏按钮</li>";
echo "<li>收藏数据保存到数据库</li>";
echo "<li>会员中心显示收藏数量</li>";
echo "<li>收藏标签页显示收藏列表</li>";
echo "<li>可以取消收藏或继续观看</li>";
echo "</ol>";

echo "<h2>💡 测试建议</h2>";
echo "<p>1. 先注册一个测试账号</p>";
echo "<p>2. 登录后访问播放页面</p>";
echo "<p>3. 点击收藏按钮测试收藏功能</p>";
echo "<p>4. 访问会员中心查看收藏列表</p>";
echo "<p>5. 测试取消收藏功能</p>";
echo "<p>6. 测试观看历史记录功能</p>";
?>
